<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserEnergySnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserEnergySnapshot>
 */
class UserEnergySnapshotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEnergySnapshot::class);
    }

    public function getUnregisteredConsumptionPerDay(User $user): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
                s.date,
                ROUND(s.used - COALESCE(d.logged, 0), 3) AS difference
            FROM (
                SELECT DATE(timestamp) AS date,
                       MAX(consumption_kwh) - MIN(consumption_kwh) AS used
                FROM user_energy_snapshot
                WHERE user_id = :userId
                GROUP BY DATE(timestamp)
            ) s
            LEFT JOIN (
                SELECT DATE(dul.started_at) AS date,
                       SUM(dul.energy_used_kwh) AS logged
                FROM device_usage_log dul
                INNER JOIN device dev ON dev.id = dul.device_id
                WHERE dev.user_id = :userId
                GROUP BY DATE(dul.started_at)
            ) d ON s.date = d.date
            ORDER BY s.date ASC
        ';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['userId' => $user->getId()]);

        return $result->fetchAllAssociative(); // date => difference
    }

    public function getEnergyUsagePerDay(User $user, \DateTimeInterface $day): array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.user = :user')
            ->andWhere('s.timestamp BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', (clone $day)->setTime(7, 59, 59))
            ->setParameter('end', (clone $day)->setTime(23, 59, 59))
            ->orderBy('s.timestamp', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getEnergyUsagePerDay2(User $user, \DateTimeInterface $day): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT 
            s.timestamp,
            s.consumption_delta
        FROM user_energy_snapshot s
        WHERE s.user_id = :userId
          AND s.timestamp BETWEEN :start AND :end
        ORDER BY s.timestamp ASC
    ';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'userId' => $user->getId(),
            'start' => $day->format('Y-m-d 00:00:00'),
            'end' => $day->format('Y-m-d 23:59:59'),
        ]);

        return $result->fetchAllAssociative(); // ['timestamp' => ..., 'consumption_delta' => ...]
    }


    //    /**
    //     * @return UserEnergySnapshot[] Returns an array of UserEnergySnapshot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserEnergySnapshot
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
