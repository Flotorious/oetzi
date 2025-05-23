<?php

namespace App\Command;

use App\Entity\UserEnergySnapshot;
use App\Repository\DeviceUsageLogRepository;
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
    private const INTERVAL_MINUTES = 5;
    private const BATCH_SIZE = 50;

    public function __construct(
        private readonly DeviceUsageLogRepository $logRepo,
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
            $output->writeln("Processing user: {$user->getEmail()}");
            try {
                $startDay = $this->logRepo->findFirstLogDateForUser($user)?->setTime(0, 0);

                if (!$startDay) {
                    $output->writeln("No logs found for user {$user->getEmail()}.");
                    continue;
                }

                if ($startDay > $today) {
                    $output->writeln(
                        "First log date for {$user->getEmail()} is in the future ({$startDay->format('Y-m-d')})."
                    );
                    continue;
                }


                for ($day = $startDay; $day <= $today; $day = $day->modify('+1 day')) {
                    $output->writeln("    Processing day: <comment>{$day->format('Y-m-d')}</comment>");

                    $logs = $this->logRepo->findLogsForUserAndDay($user, $day);
                    if (empty($logs)) {
                        $output->writeln("No logs found for {$user->getEmail()} on {$day->format('Y-m-d')}.");
                        continue;
                    }

                    // INTERVAL_MINUTES = 5 for EnergyLogs (PT)
                    $interval = new DateInterval('PT' . self::INTERVAL_MINUTES . 'M');
                    $endOfDay = $day->setTime(23, 59, 59);

                    // 5 min intervals throughout the day
                    $period = new DatePeriod($day, $interval, $endOfDay);

                    $totalKwh = 0;
                    $previousKwh = null;    // Stores the totalKwh from the previous snapshot for delta calculation
                    $snapshotCount = 0;     // Counter for the current batch

                    foreach ($period as $time) {
                        $intervalKwh = 0;

                        foreach ($logs as $log) {
                            $start = $log->getStartedAt();
                            $end = $log->getEndedAt();

                            if ($start <= $time && $end > $time) {
                                $durationMinutes = $log->getDuration() / 60;
                                if ($durationMinutes > 0) {
                                    // Calculate rate per minute and multiply by 5
                                    $ratePerMinute = $log->getEnergyUsedKWh() / $durationMinutes;
                                    $intervalKwh += $ratePerMinute * self::INTERVAL_MINUTES;
                                }
                            }
                        }

                        $totalKwh += $intervalKwh;

                        $snapshot = new UserEnergySnapshot();
                        if (!$this->em->contains($user)) {
                            $user = $this->userRepo->find($user->getId());
                        }
                        $snapshot->setUser($user);
                        $snapshot->setTimestamp($time);
                        $snapshot->setConsumptionKwh($totalKwh);
                        $snapshot->calculateDelta($previousKwh);

                        $this->em->persist($snapshot); // Persist the new snapshot
                        $previousKwh = $totalKwh; // Update previousKwh for the next delta calculation
                        $snapshotCount++;

                        // If batch size is reached, flush and clear the EntityManager
                        if ($snapshotCount % self::BATCH_SIZE === 0) {
                            $this->flushAndClear();
                            $output->writeln(
                                "      Flushed <info>" . self::BATCH_SIZE . " snapshots for {$user->getEmail()} on {$day->format('Y-m-d')} at " . $time->format(
                                    'H:i'
                                )
                            );
                        }
                    }

                    // Flush any remaining snapshots for the current day
                    $this->flushAndClear();
                    $output->writeln("Completed snapshots for {$user->getEmail()} on " . $day->format('Y-m-d'));
                }
            } catch (Exception $e) {
                $output->writeln("Error processing user {$user->getEmail()}: {$e->getMessage()}");
            }
        }

        $output->writeln('Energy snapshot generation finished.');
        return Command::SUCCESS;
    }

    /**
     * Flushes all pending changes to the database and clears the EntityManager.
     * Clearing the EntityManager detaches all managed entities, reducing memory usage.
     * This is essential in long-running commands to prevent memory exhaustion.
     */
    private function flushAndClear(): void
    {
        $this->em->flush();
        $this->em->clear();
    }
}