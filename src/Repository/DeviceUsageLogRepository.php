<?php

namespace App\Repository;

use App\Entity\DeviceUsageLog;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeviceUsageLog>
 */
class DeviceUsageLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceUsageLog::class);
    }

    public function getDailyDeviceEnergySummary(User $user): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT
              DATE(dul.started_at) AS date,
              d.name AS device,
              d.id AS deviceId,
              SUM(dul.energy_used_kwh) AS energy
            FROM device_usage_log dul
            JOIN device d ON d.id = dul.device_id
            WHERE d.user_id = :user
             -- AND dul.started_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(dul.started_at), d.id, d.name
            ORDER BY date ASC
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['user' => $user->getId()]);

        return $result->fetchAllAssociative();
    }

    public function getDeviceUsagePerIntervalForDay(User $user, \DateTime $day): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT
                d.name AS device,
                d.id AS deviceId,
                DATE_FORMAT(dul.started_at, '%H:%i') AS time_slot,
                SUM(dul.energy_used_kwh) AS energy
            FROM device_usage_log dul
            JOIN device d ON d.id = dul.device_id
            WHERE d.user_id = :user
              AND DATE(dul.started_at) = :day
            GROUP BY device, time_slot
            ORDER BY time_slot ASC
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'user' => $user->getId(),
            'day' => $day->format('Y-m-d'),
        ]);

        return $result->fetchAllAssociative();
    }

    //    /**
    //     * @return DeviceUsageLog[] Returns an array of DeviceUsageLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DeviceUsageLog
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
