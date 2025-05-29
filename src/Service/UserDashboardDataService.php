<?php

namespace App\Service;

use App\Entity\DeviceUsageLog;
use App\Entity\User;
use App\Repository\DeviceUsageLogRepository;
use App\Repository\PriceRatePeriodRepository;
use App\Repository\UserEnergySnapshotRepository;
use App\Utils\ColorHelper;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

class UserDashboardDataService
{
    public function __construct(
        private readonly UserEnergySnapshotRepository $userEnergySnapshotRepository,
        private readonly DeviceUsageLogRepository $deviceUsageLogRepository,
        private readonly PriceRatePeriodRepository $priceRatePeriodRepository,
    ) {
    }

    public function getWeeklyDeviceUsageGraphData(
        User $user,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ): array {
        $deviceData = $this->deviceUsageLogRepository->getDailyDeviceEnergySummary($user, $startDate, $endDate);
        $unregisteredData = $this->userEnergySnapshotRepository->getUnregisteredConsumptionPerDay($user);

        $labels = array_unique(array_column($deviceData, 'date'));
        sort($labels);

        $byDeviceDate = [];
        $deviceIdMap = [];
        $differenceByDate = [];

        foreach ($deviceData as $row) {
            $byDeviceDate[$row['device']][$row['date']] = round($row['energy'], 2);
            $deviceIdMap[$row['device']] = $row['deviceId'];
        }

        $datasets = [];

        foreach ($deviceIdMap as $deviceName => $deviceId) {
            $values = array_map(fn($label) => $byDeviceDate[$deviceName][$label] ?? 0, $labels);

            $datasets[] = [
                'label' => $deviceName,
                'data' => $values,
                'stack' => 'energy',
                'deviceId' => $deviceId,
                'backgroundColor' => ColorHelper::generateColorFromString($deviceName, 0.4),
                'borderColor' => ColorHelper::generateColorFromString($deviceName, 1),
                'borderWidth' => 1.5,
                '_total' => array_sum($values), // used only for sorting
            ];
        }
        // Sort by kwh
        usort($datasets, function($a, $b) {
            return $b['_total'] <=> $a['_total'];
        });

        foreach ($datasets as &$ds) {
            unset($ds['_total']);
        }
        unset($ds);

        foreach ($differenceByDate as $row) {
            $differenceByDate[$row['date']] = round($row['difference'], 2);
        }

        $unregisteredByDate = [];
        foreach ($unregisteredData as $row) {
            $unregisteredByDate[$row['date']] = max(round($row['difference'], 2), 0); // ensure >= 0
        }
        $unregisteredValues = array_map(fn($label) => $unregisteredByDate[$label] ?? 0, $labels);

        $datasets[] = [
            'label' => 'Unregistered Consumption',
            'data' => $unregisteredValues,
            'stack' => 'energy',
        ];

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    /**
     * Generates data for the daily device usage graph.
     *
     * @param User $user The user for whom to retrieve data.
     * @param DateTimeInterface $day The specific day for which to retrieve data.
     * @return array An array containing 'labels' (x-axis timestamps) and 'datasets' (device usage data).
     */
    public function getDailyDeviceUsageGraphData(User $user, DateTimeInterface $day): array
    {
        $tenMinInterval = new DateInterval('PT10M'); // CHANGED HERE
        $dayStart = (clone $day)->setTime(0, 0, 0);
        $dayEndBoundary = (clone $day)->modify('+1 day')->setTime(0, 0, 0); // For DatePeriod end (exclusive)

        // Generate ALL 10-minute labels for the entire day.
        $fullDayLabels = [];
        $periodForFullDay = new DatePeriod($dayStart, $tenMinInterval, $dayEndBoundary);
        foreach ($periodForFullDay as $dt) {
            $fullDayLabels[] = $dt->format('Y-m-d H:i');
        }

        $logs = $this->deviceUsageLogRepository->findLogsByUserAndDay($user, $day);
        $priceRateBandsData = $this->priceRatePeriodRepository->getTimeBandsForChart();

        $priceRateBands = array_map(function ($band) use ($day) {
            $start = (clone $day)->setTime(
                (int) $band['start']->format('H'),
                (int) $band['start']->format('i'),
                0
            );
            $end = (clone $day)->setTime(
                (int) $band['end']->format('H'),
                (int) $band['end']->format('i'),
                0
            );
            return [
                'start' => $start->format('Y-m-d H:i'),
                'end' => $end->format('Y-m-d H:i'),
                'color' => ColorHelper::generateColorFromString('Band-' . $band['start']->format('H:i'), 0.2),
            ];
        }, $priceRateBandsData);


        $datasets = [];

        foreach ($logs as $log) {
            /** @var DeviceUsageLog $log */
            $deviceName = $log->getDevice()->getName();
            $logStart = clone $log->getStartedAt();
            $logEnd = $log->getEndedAt() ? clone $log->getEndedAt() : clone $logStart;
            $energyKWh = $log->getEnergyUsedKWh() ?? 0.0;

            $onSlots = [];
            $onSlotsSet = [];
            $firstOnSlotLabel = null;
            $lastOnSlotLabel = null;

            foreach ($periodForFullDay as $slotStartDateTime) {
                $slotLabel = $slotStartDateTime->format('Y-m-d H:i');
                $slotEndDateTime = (clone $slotStartDateTime)->add($tenMinInterval);

                if ($logStart < $slotEndDateTime && $logEnd >= $slotStartDateTime) {
                    $onSlots[] = $slotLabel;
                    $onSlotsSet[$slotLabel] = true;
                    if ($firstOnSlotLabel === null) {
                        $firstOnSlotLabel = $slotLabel;
                    }
                    $lastOnSlotLabel = $slotLabel;
                }
            }

            $prevSlotLabel = null;
            $nextSlotLabel = null;

            if ($firstOnSlotLabel) {
                $firstOnSlotDateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i', $firstOnSlotLabel);
                if ($firstOnSlotDateTime) {
                    $prevSlotDateTime = $firstOnSlotDateTime->sub($tenMinInterval);
                    if ($prevSlotDateTime >= $dayStart) {
                        $prevSlotLabel = $prevSlotDateTime->format('Y-m-d H:i');
                    }
                }
            }

            if ($lastOnSlotLabel) {
                $lastOnSlotDateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i', $lastOnSlotLabel);
                if ($lastOnSlotDateTime) {
                    $nextSlotDateTime = $lastOnSlotDateTime->add($tenMinInterval);
                    if ($nextSlotDateTime < $dayEndBoundary) {
                        $nextSlotLabel = $nextSlotDateTime->format('Y-m-d H:i');
                    }
                }
            }

            $activeSlotsCount = count($onSlots);
            $avgEnergyPerSlot = ($energyKWh > 0 && $activeSlotsCount > 0) ? $energyKWh / $activeSlotsCount : 0.0;

            $data = [];
            foreach ($fullDayLabels as $labelTimestamp) {
                if (isset($onSlotsSet[$labelTimestamp])) {
                    $data[] = $avgEnergyPerSlot;
                } elseif ($labelTimestamp === $prevSlotLabel || $labelTimestamp === $nextSlotLabel) {
                    $data[] = 0.0;
                } else {
                    $data[] = null;
                }
            }

            $endTimeString = $log->getEndedAt() ? $log->getEndedAt()->format('H:i') : $log->getStartedAt()->format('H:i');
            if ($log->getEndedAt() && $log->getEndedAt() > $dayEndBoundary) {
                $endTimeString = $log->getEndedAt()->format('H:i') . ' (next day)';
            } elseif ($log->getEndedAt() === null) {
                $endTimeString = 'ongoing';
            }

            $displayLabel = sprintf(
                '%s (%s - %s, Total: %s kWh)',
                $deviceName,
                $log->getStartedAt()->format('H:i'),
                $endTimeString,
                round($energyKWh, 3)
            );

            $datasets[] = [
                'label' => $displayLabel,
                'originalDeviceName' => $deviceName,
                'data' => $data,
                'borderColor' => ColorHelper::generateColorFromString($deviceName, 1),
                'backgroundColor' => ColorHelper::generateColorFromString
                ($deviceName, 0.2),
                'fill' => true, // Area under line
                'tension' => 0.2,
                'pointRadius' => 0,
                'pointHoverRadius' => 5,
                'borderWidth' => 2,
            ];
        }

        return [
            'labels' => $fullDayLabels,
            'datasets' => $datasets,
            'priceRateBands' => $priceRateBands
        ];
    }


    public function getDailyEnergyUsageGraphData(User $user, DateTimeInterface $day): array
    {
        $rawData = $this->userEnergySnapshotRepository->getEnergyUsagePerDay($user, $day);

        $labels = [];
        $data = [];

        foreach ($rawData as $snapshot) {
            $labels[] = $snapshot->getTimestamp()->format('H:i');
            $data[] = $snapshot->getConsumptionDelta();
        }

        $datasets = [
            [
                'label' => 'Consumption Δ (kWh)',
                'data' => $data,
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'fill' => true,
                'tension' => 0.3,
            ]
        ];

        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }

    public function getMonthlyEnergyUsageGraphData(
        User $user,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ): array {
        $rawData = $this->userEnergySnapshotRepository->getMonthlyEnergyUsage($user, $startDate, $endDate);

        $months = [];
        $totals = [];

        $period = new DatePeriod(
            $startDate->modify('first day of this month'),
            new DateInterval('P1M'),
            $endDate->modify('first day of next month')
        );

        foreach ($period as $month) {
            $key = $month->format('Y-m');
            $months[] = $key;
            $totals[$key] = 0;
        }

        foreach ($rawData as $row) {
            $totals[$row['month']] = round((float)$row['total'], 2);
        }

        $data = array_values($totals);

        $datasets = [
            [
                'label' => 'Monthly Consumption (kWh)',
                'data' => $data,
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1,
                'fill' => true,
                'tension' => 0.2,
            ]
        ];

        return [
            'labels' => $months,
            'datasets' => $datasets
        ];
    }

    public function getPeriodEnergyPriceGraphData(
        User $user,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        string $periodType = 'week'
    ): array {
        if ($periodType === 'week') {
            $raw = $this->userEnergySnapshotRepository->getWeeklyCostByPeriod($user, $startDate, $endDate);
            $labelKey = 'week_start';
        } elseif ($periodType === 'month') {
            $raw = $this->userEnergySnapshotRepository->getMonthlyCostByPeriod($user, $startDate, $endDate);
            $labelKey = 'month';
        } else {
            throw new InvalidArgumentException('Invalid periodType');
        }

        $labels = array_unique(array_column($raw, $labelKey));
        sort($labels);

        $periods = array_unique(array_column($raw, 'period'));

        $costBy = [];
        foreach ($raw as $row) {
            $costBy[$row['period']][$row[$labelKey]] = round((float)$row['cost'], 2);
        }

        $datasets = [];
        foreach ($periods as $periodName) {
            $data = array_map(
                fn($label) => $costBy[$periodName][$label] ?? 0,
                $labels
            );
            $datasets[] = [
                'label' => $periodName,
                'data' => $data,
                'stack' => 'price',
                'backgroundColor' => ColorHelper::generateColorFromString($periodName, 0.4),
                'borderColor' => ColorHelper::generateColorFromString($periodName, 1),
                'borderWidth' => 1,
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }
}
