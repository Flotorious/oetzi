<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class FakeProviderController extends AbstractController
{
    #[Route('/fake-provider/{userId}', name: 'app_fake_provider')]
    public function fakeFeed(SessionInterface $session, int $userId): JsonResponse
    {

        file_put_contents('/tmp/feed.log', 'called with ID ' . $userId . "\n", FILE_APPEND);

        $key = 'cumulative_kwh_user_' . $userId;

        $previous = $session->get($key, 100 + mt_rand(0, 100) / 100); // start at ~100kWh
        $increase = mt_rand(5, 15) / 100; // simulate 0.05–0.15 kWh every 5 min
        $current = round($previous + $increase, 3);

        $session->set($key, $current);

        $data = [
            'timestamp' => (new \DateTimeImmutable())->format('c'),
            'consumption_kwh' => $current,
        ];

        file_put_contents('/tmp/feed.log', print_r($data, true), FILE_APPEND);

        return $this->json($data);
    }
}
