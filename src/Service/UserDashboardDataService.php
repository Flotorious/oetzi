<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\DeviceUsageLogRepository;
use App\Repository\PriceRatePeriodRepository;
use App\Repository\UserEnergySnapshotRepository;
use App\Utils\ColorHelper;

class UserDashboardDataService
{
    public function __construct(
        private readonly UserEnergySnapshotRepository $userEnergySnapshotRepository,
        private readonly DeviceUsageLogRepository $deviceUsageLogRepository,
        private readonly PriceRatePeriodRepository $priceRatePeriodRepository,
    )
    {
    }

    public function getWeeklyDeviceUsageGraphData(
        User $user,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate
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
            $color = ColorHelper::generateColorFromString($deviceName, 0.6);
            $values = array_map(fn($label) => $byDeviceDate[$deviceName][$label] ?? 0, $labels);

            $datasets[] = [
                'label' => $deviceName,
                'data' => $values,
                'stack' => 'energy',
                'deviceId' => $deviceId,
                'backgroundColor' => $color,
                'borderColor' => ColorHelper::generateColorFromString($deviceName, 1),
                'borderWidth' => 1.5,
            ];
        }

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
            'labels'   => $labels,
            'datasets' => $datasets
        ];
    }

    public function getDailyDeviceUsageGraphData(User $user, \DateTimeInterface $day): array {
        
        $rawData = $this->deviceUsageLogRepository->getDeviceUsagePerIntervalForDay($user, $day);
        $priceRateBandsData = $this->priceRatePeriodRepository->getTimeBandsForChart();

        $priceRateBands = array_map(function ($band) {
            return [
                'start' => $band['start']->format('H:i'),
                'end' => $band['end']->format('H:i'),
                'color' => ColorHelper::generateColorFromString('Band-' . $band['start']->format('H:i'), 0.1),
            ];
        }, $priceRateBandsData);

        $labels = array_column($rawData, 'time_slot');
        $labels = array_unique($labels);
        sort($labels);

        $byDevice = [];
        foreach ($rawData as $row) {
            $byDevice[$row['device']][$row['time_slot']] = (float) $row['energy'];
        }

        $datasets = [];
        foreach ($byDevice as $deviceName => $valuesByTime) {
            $values = array_map(fn($label) => $valuesByTime[$label] ?? 0, $labels);

            $datasets[] = [
                'label' => $deviceName,
                'data' => $values,
                'fill' => false,
                'borderColor' => ColorHelper::generateColorFromString($deviceName, 1),
                'backgroundColor' => ColorHelper::generateColorFromString($deviceName, 0.5),
                'borderWidth' => 1.5,
                'tension' => 0.3,
            ];
        }

        return [
            'labels'   => $labels,
            'datasets' => $datasets,
            'priceRateBands' => $priceRateBands
        ];

    }

    public function getDailyEnergyUsageGraphData(User $user, \DateTimeInterface $day): array {

        $rawData = $this->userEnergySnapshotRepository->getEnergyUsagePerDay($user, $day);

        $labels = [];
        $data = [];

        foreach ($rawData as $snapshot) {
            $labels[] = $snapshot->getTimestamp()->format('H:i');
            $data[] = $snapshot->getConsumptionDelta();
        }

        $datasets = [[
            'label' => 'Consumption Δ (kWh)',
            'data' => $data,
            'borderColor' => 'rgba(75, 192, 192, 1)',
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'fill' => true,
            'tension' => 0.3,
        ]];

        return [
            'labels'   => $labels,
            'datasets' => $datasets
        ];
    }

    public function getMonthlyEnergyUsageGraphData(
        User $user,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate
    ): array {

        $rawData = $this->userEnergySnapshotRepository->getMonthlyEnergyUsage($user, $startDate, $endDate);

        $months = [];
        $totals = [];

        $period = new \DatePeriod(
            $startDate->modify('first day of this month'),
            new \DateInterval('P1M'),
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

        $datasets = [[
            'label' => 'Monthly Consumption (kWh)',
            'data' => $data,
            'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
            'borderColor' => 'rgba(54, 162, 235, 1)',
            'borderWidth' => 1,
        ]];

        return [
            'labels'   => $months,
            'datasets' => $datasets
        ];
    }

    public function getPeriodEnergyPriceGraphData(
        User $user,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        string $periodType = 'week'
    ): array {
        if ($periodType === 'week') {
            $raw = $this->userEnergySnapshotRepository->getWeeklyCostByPeriod($user, $startDate, $endDate);
            $labelKey = 'week_start';
        } elseif ($periodType === 'month') {
            $raw = $this->userEnergySnapshotRepository->getMonthlyCostByPeriod($user, $startDate, $endDate);
            $labelKey = 'month';
        } else {
            throw new \InvalidArgumentException('Invalid periodType');
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
                'label'           => $periodName,
                'data'            => $data,
                'stack'           => 'price',
                'backgroundColor' => ColorHelper::generateColorFromString($periodName, 0.6),
                'borderColor'     => ColorHelper::generateColorFromString($periodName, 1),
                'borderWidth'     => 1,
            ];
        }

        return [
            'labels'   => $labels,
            'datasets' => $datasets
        ];
    }
}
