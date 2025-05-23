<?php

namespace App\DataFixtures;

use App\Entity\Device;
use App\Entity\DeviceUsageLog;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DeviceUsageLogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $deviceRepo = $manager->getRepository(Device::class);

        // TODO add logs here or import from file
        $logs = [];

        foreach ($logs as $logData) {
            $device = $deviceRepo->findOneBy(['name' => $logData['device_name']]);
            if (!$device) {
                echo "Device not found: {$logData['device_name']}\n";
                continue;
            }

            $log = new DeviceUsageLog();
            $log->setDevice($device);
            $log->setStartedAt(new DateTimeImmutable($logData['start']));
            $log->setEndedAt(new DateTimeImmutable($logData['end']));
            $log->setTitle($logData['title']);

            $manager->persist($log);
        }

        $manager->flush();
    }
}
