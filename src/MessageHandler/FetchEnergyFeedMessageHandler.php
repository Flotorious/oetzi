<?php

namespace App\MessageHandler;

use App\Entity\UserEnergySnapshot;
use App\Message\FetchEnergyFeedMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final readonly class FetchEnergyFeedMessageHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private HttpClientInterface $http
    ) {}

    public function __invoke(FetchEnergyFeedMessage $message): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            if (!$url = $user->getFeedUrl()) continue;

            try {
                $response = $this->http->request('GET', $url, [
                    'headers' => [
                        'Host' => 'energyviz.localhost',
                    ],
                ]);
                $data = $response->toArray();

                // Assume JSON format: ['timestamp' => ..., 'consumption_kwh' => ...]
                // If the JSON format changes, adapt the import feed
                $snapshot = new UserEnergySnapshot();
                $snapshot->setUser($user);
                $snapshot->setTimestamp(new \DateTimeImmutable($data['timestamp']));
                $snapshot->setConsumptionKwh($data['consumption_kwh']);

                $previousSnapshot = $this->em->getRepository(UserEnergySnapshot::class)
                    ->createQueryBuilder('s')
                    ->where('s.user = :user')
                    ->andWhere('s.timestamp < :timestamp')
                    ->setParameter('user', $user)
                    ->setParameter('timestamp', $snapshot->getTimestamp())
                    ->orderBy('s.timestamp', 'DESC')
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();

                if ($previousSnapshot) {
                    $delta = $snapshot->getConsumptionKwh() - $previousSnapshot->getConsumptionKwh();
                    $snapshot->setConsumptionDelta($delta >= 0 ? round($delta, 4) : 0);
                } else {
                    $snapshot->setConsumptionDelta(null);
                }

                $this->em->persist($snapshot);
            } catch (\Throwable $e) {}
        }

        $this->em->flush();
    }
}
