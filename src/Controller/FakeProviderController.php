<?php

namespace App\Controller;

use App\Entity\UserEnergySnapshot;
use App\Repository\UserEnergySnapshotRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class FakeProviderController extends AbstractController
{
    #[Route('/fake-provider/{userId}', name: 'app_fake_provider')]
    public function fakeFeed(
        int $userId,
        UserRepository $userRepository,
        UserEnergySnapshotRepository $snapshotRepo,
        EntityManagerInterface $em
    ): JsonResponse
    {
        // Log for debugging
        error_log("FakeProvider called for userId: $userId");
        
        $user = $userRepository->find($userId);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $lastSnapshot = $snapshotRepo->findOneBy(
            ['user' => $user],
            ['timestamp' => 'DESC']
        );

        $lastKwh = $lastSnapshot ? $lastSnapshot->getConsumptionKwh() : 100.0;

        $increase = mt_rand(5, 15) / 100;
        $newKwh = $lastKwh + $increase;

        return $this->json([
            'timestamp' => (new \DateTimeImmutable())->format('c'),
            'consumption_kwh' => $newKwh,
        ]);
    }
}
