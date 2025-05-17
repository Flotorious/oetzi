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

    public function getDailyEnergySummary(User $user): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT DATE(l.started_at) AS date, SUM(l.energy_used_kwh) AS total_energy
            FROM device_usage_log l
            INNER JOIN device d ON l.device_id = d.id
            WHERE d.user_id = :userId
            GROUP BY DATE(l.started_at)
            ORDER BY DATE(l.started_at)
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['userId' => $user->getId()]);

        return $result->fetchAllAssociative();
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
            GROUP BY DATE(dul.started_at), d.id, d.name
            ORDER BY date ASC
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['user' => $user->getId()]);

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
