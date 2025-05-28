<?php

namespace App\Repository;

use App\Entity\PriceRatePeriod;
use App\Entity\User;
use App\Entity\UserEnergySnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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
            ->setParameter('start', (clone $day)->setTime(0,0,0))
            ->setParameter('end', (clone $day)->setTime(23, 59, 59))
            ->orderBy('s.timestamp', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getMonthlyEnergyUsage(User $user, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
                DATE_FORMAT(s.timestamp, "%Y-%m") AS month,
                SUM(s.consumption_delta) AS total
            FROM user_energy_snapshot s
            WHERE s.user_id = :user
              AND s.timestamp BETWEEN :start AND :end
            GROUP BY month
            ORDER BY month
        ';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'user' => $user->getId(),
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ]);

        return $result->fetchAllAssociative();
    }

    public function getWeeklyCostByPeriod(User $user, \DateTimeImmutable $start, \DateTimeImmutable $end): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
              DATE_SUB(DATE(u.timestamp),
                       INTERVAL WEEKDAY(u.timestamp) DAY
                      )             AS week_start,
              p.name                AS period,
              SUM(u.consumption_delta * p.price_per_kwh) AS cost
            FROM user_energy_snapshot u
            JOIN price_rate_period p
              ON (
                   p.end_time > p.start_time
                   AND TIME(u.timestamp) BETWEEN p.start_time AND p.end_time
                 )
                 OR (
                   p.end_time <= p.start_time
                   AND (
                         TIME(u.timestamp) >= p.start_time
                      OR TIME(u.timestamp) <= p.end_time
                     )
                 )
            WHERE u.user_id = :user_id
              AND u.timestamp BETWEEN :start AND :end
            GROUP BY week_start, p.id, p.name
            ORDER BY week_start ASC, p.id ASC;
        ';

        $stmt   = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'user_id' => $user->getId(),
            'start'   => $start->format('Y-m-d 00:00:00'),
            'end'     => $end->  format('Y-m-d 23:59:59'),
        ]);

        return $result->fetchAllAssociative();
    }

    public function getMonthlyCostByPeriod(User $user, \DateTimeImmutable $start, \DateTimeImmutable $end): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
              DATE_FORMAT(u.timestamp, "%Y-%m") AS month,
              p.name AS period,
              SUM(u.consumption_delta * p.price_per_kwh) AS cost
            FROM user_energy_snapshot u
            JOIN price_rate_period p
              ON (
                  p.end_time > p.start_time
                  AND TIME(u.timestamp) BETWEEN p.start_time AND p.end_time
                 )
                 OR (
                p.end_time <= p.start_time
                AND (
                    TIME(u.timestamp) >= p.start_time
                    OR TIME(u.timestamp) <= p.end_time
                )
            )
            WHERE u.user_id = :user_id
            AND u.timestamp BETWEEN :start AND :end
            GROUP BY month, p.id, p.name
            ORDER BY month ASC, p.id ASC;
        ';

        $stmt   = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'user_id' => $user->getId(),
            'start'   => $start->format('Y-m-d 00:00:00'),
            'end'     => $end->  format('Y-m-d 23:59:59'),
        ]);

        return $result->fetchAllAssociative();
    }

    // TODO merge this 2 functions together
    public function getTotalMonthlyCost(User $user): float
    {
        $start = (new \DateTimeImmutable('first day of this month'))->setTime(0, 0, 0);
        $end = new \DateTimeImmutable();

        $monthlyCosts = $this->getMonthlyCostByPeriod($user, $start, $end);

        $total = 0;
        foreach ($monthlyCosts as $row) {
            $total += (float) $row['cost'];
        }

        return round($total, 2);
    }
    // TODO merge this 2 functions together
    public function getTotalMonthlyCostUntil(User $user, \DateTimeImmutable $referenceDate): float
    {
        $start = (new \DateTimeImmutable($referenceDate->format('Y-m-01')))->setTime(0, 0, 0);
        $end = (new \DateTimeImmutable($referenceDate->format('Y-m-d')))->setTime(23, 59, 59);

        $monthlyCosts = $this->getMonthlyCostByPeriod($user, $start, $end);

        $total = 0;
        foreach ($monthlyCosts as $row) {
            $total += (float) $row['cost'];
        }

        return round($total, 2);
    }

    public function getMonthlyConsumptionUntilDate(User $user, \DateTimeImmutable $referenceDate): float
    {
        $start = (new \DateTimeImmutable($referenceDate->format('Y-m-01')))
            ->setTime(0, 0);
        $end = $referenceDate;

        $qb = $this->createQueryBuilder('s')
            ->select('SUM(s.consumptionDelta)')
            ->where('s.user = :user')
            ->andWhere('s.timestamp BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        return (float) $qb->getQuery()->getSingleScalarResult();
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
