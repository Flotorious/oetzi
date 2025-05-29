<?php

namespace App\Command;

use App\Entity\UserEnergySnapshot;
use App\Repository\DeviceUsageLogRepository;
use App\Repository\UserEnergySnapshotRepository;
use App\Repository\UserRepository;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:generate-energy-snapshots', 'Generates energy snapshots for users')]
class GenerateSnapshotsCommand extends Command
{
    private const INTERVAL_MINUTES = 15;
    private const BATCH_SIZE = 100;

    public function __construct(
        private readonly DeviceUsageLogRepository $logRepo,
        private readonly UserEnergySnapshotRepository $energyLogRepo,
        private readonly UserRepository $userRepo,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting energy snapshot generation...');

        $users = $this->userRepo->findAll();
        $today = new DateTimeImmutable('today');

        if (empty($users)) {
            $output->writeln("No users found.");
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            $output->writeln("Processing user: <info>{$user->getEmail()}</info>");
            try {
                $lastSnapshotTimestamp = $this->energyLogRepo->findOneBy(
                    ['user' => $user], ['timestamp' => 'DESC']
                )?->getTimestamp();

                $processingStartDay = null;

                if ($lastSnapshotTimestamp) {
                    $processingStartDay = $lastSnapshotTimestamp->modify('+1 day')->setTime(0, 0, 0);
                    $output->writeln(
                        "    Resuming from last snapshot: {$lastSnapshotTimestamp->format('Y-m-d H:i:s')}"
                    );
                } else {
                    $firstLogDate = $this->logRepo->findFirstLogDateForUser($user)?->setTime(0, 0, 0);
                    if ($firstLogDate) {
                        $processingStartDay = $firstLogDate;
                        $output->writeln(
                            "    No existing snapshots found. Starting from first log: {$firstLogDate->format('Y-m-d')}"
                        );
                    }
                }

                if (!$processingStartDay) {
                    $output->writeln(
                        "    No device usage logs found for user <comment>{$user->getEmail()}</comment>. Skipping."
                    );
                    continue;
                }

                // Check against the start of tomorrow
                if ($processingStartDay > $today->modify('+1 day')) {
                    $output->writeln(
                        "    Processing start day for {$user->getEmail()} is in the future ({$processingStartDay->format('Y-m-d')}). Skipping."
                    );
                    continue;
                }

                for ($day = $processingStartDay; $day <= $today; $day = $day->modify('+1 day')) {
                    $output->writeln("    Processing day: <comment>{$day->format('Y-m-d')}</comment>");

                    $logs = $this->logRepo->findLogsForUserAndDay($user, $day);
                    if (empty($logs)) {
                        $output->writeln(
                            "    No device usage logs for {$user->getEmail()} on {$day->format('Y-m-d')}. Skipping day."
                        );
                        continue;
                    }

                    $interval = new DateInterval('PT' . self::INTERVAL_MINUTES . 'M');


                    $startOfDay = $day->setTime(0, 0, 0);
                    $endOfDay = (clone $day)->modify('+1 day')->setTime(0, 0, 0);

                    $period = new DatePeriod($startOfDay, $interval, $endOfDay);

                    if (!iterator_count($period)) {
                        $output->writeln(
                            "    <comment>Warning: No intervals found for day {$day->format('Y-m-d')}. Check DatePeriod logic.</comment>"
                        );
                        continue;
                    }

                    $totalKwh = 0;

                    $previousKwh = $this->energyLogRepo->findOneBy(
                        ['user' => $user, 'timestamp' => $day->setTime(0, 0, 0)], ['timestamp' => 'DESC']
                    )?->getConsumptionKwh();

                    if ($previousKwh === null) {
                        $previousKwh = 0;
                    }

                    $snapshotCount = 0;

                    foreach ($period as $time) {
                        $output->writeln("        Generating snapshot for: {$time->format('Y-m-d H:i')}");

                        $intervalKwh = 0;

                        foreach ($logs as $log) {
                            $start = $log->getStartedAt();
                            $end = $log->getEndedAt();

                            if ($start <= $time && $end > $time) {
                                $durationSeconds = $log->getDuration();
                                if ($durationSeconds > 0) {
                                    $energyUsedKWh = $log->getEnergyUsedKWh();
                                    // Calculate the energy rate per second
                                    $ratePerSecond = $energyUsedKWh / $durationSeconds;
                                    // Calculate energy consumed during the INTERVAL_MINUTES period
                                    $intervalKwh += $ratePerSecond * (self::INTERVAL_MINUTES * 60); // Convert minutes to seconds
                                }
                            }
                        }

                        $totalKwh += $intervalKwh;

                        $snapshot = new UserEnergySnapshot();

                        // Re-fetching user if it's detached after a flush
                        // Checking $this->em->contains()
                        if (!$this->em->contains($user)) {
                            $user = $this->userRepo->find($user->getId());
                        }
                        $snapshot->setUser($user);
                        $snapshot->setTimestamp($time);
                        $snapshot->setConsumptionKwh($totalKwh);
                        $snapshot->calculateDelta(
                            $previousKwh
                        );

                        $output->writeln(
                            "        Timestamp: <fg=cyan>{$time->format('H:i')}</> | Interval kWh: <info>" . round(
                                $intervalKwh,
                                4
                            ) . "</info> | Total kWh: <comment>" . round(
                                $totalKwh,
                                4
                            ) . "</comment> | Delta kWh: <options=bold>" . round(
                                $snapshot->getConsumptionDelta(),
                                4
                            ) . "</>"
                        );

                        $this->em->persist($snapshot);
                        $previousKwh = $totalKwh; // Update previousKwh for the next snapshot delta calculation
                        $snapshotCount++;

                        // flush and clear the EntityManager
                        if ($snapshotCount % self::BATCH_SIZE === 0) {
                            $this->flushAndClear();
                            $output->writeln(
                                "        <fg=green>Flushed " . self::BATCH_SIZE . " snapshots</> for {$user->getEmail()} on {$day->format('Y-m-d')} at " . $time->format(
                                    'H:i'
                                )
                            );
                            // After clearing, user entity becomes detached -> so re-fetch for the next iteration
                        }
                    }

                    // Flush again if not all unflushed
                    if ($snapshotCount % self::BATCH_SIZE !== 0) {
                        $this->flushAndClear();
                        $output->writeln(
                            "    <fg=green>Flushed remaining " . ($snapshotCount % self::BATCH_SIZE) . " snapshots</> for {$user->getEmail()} on " . $day->format(
                                'Y-m-d'
                            )
                        );
                    }
                    $output->writeln("    Completed snapshots for {$user->getEmail()} on " . $day->format('Y-m-d'));
                }
            } catch (Exception $e) {
                $output->writeln("<error>Error processing user {$user->getEmail()}: {$e->getMessage()}</error>");
            }
        }

        $output->writeln('Energy snapshot generation finished.');
        return Command::SUCCESS;
    }

    /**
     * Flushes all pending changes to the database and clears the EntityManager
     */
    private function flushAndClear(): void
    {
        $this->em->flush();
        $this->em->clear();
    }
}