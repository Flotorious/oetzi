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

    public function getDailyDeviceEnergySummary(User $user,\DateTimeImmutable $start, \DateTimeImmutable $end): array
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
                AND dul.started_at >= :start
                AND dul.started_at < :end
            GROUP BY DATE(dul.started_at), d.id, d.name
            ORDER BY date ASC
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'user' => $user->getId(),
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ]);

        return $result->fetchAllAssociative();
    }

    // TODO check if result is correct
    public function getLoggedMonthlyConsumptionUntilDate(User $user, \DateTimeImmutable $referenceDate): float
    {
        $start = (new \DateTimeImmutable('first day of this month'))->setTime(0, 0, 0);
        $end = $referenceDate;


        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT SUM(dul.energy_used_kwh) AS total_energy
            FROM device_usage_log dul
            JOIN device d ON d.id = dul.device_id
            WHERE d.user_id = :user
              AND dul.started_at >= :start
              AND dul.started_at < :end
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'user' => $user->getId(),
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ]);

        return (float) ($result->fetchOne() ?? 0);
    }

    public function getDeviceUsagePerIntervalForDay(User $user, \DateTimeInterface $day): array
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

    public function findLogsForUserAndDay(User $user, \DateTimeInterface $day): array
    {
        $start = (clone $day)->setTime(0, 0);
        $end = (clone $day)->setTime(23, 59, 59);

        return $this->createQueryBuilder('log')
            ->join('log.device', 'd')
            ->where('d.user = :user')
            ->andWhere('log.startedAt BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    public function findFirstLogDateForUser(User $user): ?\DateTimeImmutable
    {
        $log = $this->createQueryBuilder('log')
            ->join('log.device', 'd')
            ->where('d.user = :user')
            ->setParameter('user', $user)
            ->orderBy('log.startedAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $log?->getStartedAt();
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
