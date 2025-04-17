<?php

namespace App\Command;

use App\Entity\UserEnergySnapshot;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:fetch-energy-feeds',
    description: 'Fetch the energy feed for each user',
)]
class FetchEnergyFeedsCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
        private readonly HttpClientInterface $http
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            if (!$url = $user->getFeedUrl()) continue;

            try {
                $response = $this->http->request('GET', $url);
                $data = $response->toArray();

                // Assume JSON format: ['timestamp' => ..., 'consumption_kwh' => ...]
                // If the JSON format changes, adapt the import feed
                $snapshot = new UserEnergySnapshot();
                $snapshot->setUser($user);
                $snapshot->setTimestamp(new \DateTimeImmutable($data['timestamp']));
                $snapshot->setConsumptionKwh($data['consumption_kwh']);

                $this->em->persist($snapshot);
            } catch (\Exception $e) {
                $output->writeln("<error>Error for {$user->getEmail()}: {$e->getMessage()}</error>");
            }
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}
