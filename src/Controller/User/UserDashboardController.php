<?php

namespace App\Controller\User;

use App\Controller\Admin\PriceRatePeriodCrudController;
use App\Controller\Admin\UserCrudController;
use App\Entity\Device;
use App\Entity\DeviceUsageLog;
use App\Entity\PriceRatePeriod;
use App\Entity\UserEnergySnapshot;
use App\Repository\DeviceRepository;
use App\Repository\DeviceUsageLogRepository;
use App\Repository\PriceRatePeriodRepository;
use App\Repository\UserEnergySnapshotRepository;
use App\Utils\ColorHelper;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

#[AdminDashboard(routePath: '/profile', routeName: 'app_user_dashboard')]
class UserDashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly DeviceUsageLogRepository $deviceUsageLogRepository,
        private readonly UserEnergySnapshotRepository $userEnergySnapshotRepository,
        private readonly PriceRatePeriodRepository $priceRatePeriodRepository,
    ) {}

    #[Route('/', name: 'user_dashboard_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        $lastSnapshot = $this->userEnergySnapshotRepository->findOneBy(['user' => $user], ['timestamp' => 'DESC']);
        return $this->render('user_dashboard/index.html.twig', [
            'user' => $user,
            'consumptionKwh' => $lastSnapshot?->getConsumptionKwh(),
        ]);
    }

    #[Route('/weekly-device-usage-graph', name: 'graph_weekly_device_usage')]
    public function weeklyDeviceUsageGraph(): Response
    {
        $user = $this->getUser();

        // Fetch raw data
        $deviceData = $this->deviceUsageLogRepository->getDailyDeviceEnergySummary($user);
        $lastSnapshot = $this->userEnergySnapshotRepository->findOneBy(['user' => $user], ['timestamp' => 'DESC']);
        $unregisteredData = $this->userEnergySnapshotRepository->getUnregisteredConsumptionPerDay($user);

        $labels = array_unique(array_column($deviceData, 'date'));
        sort($labels);

        // Organize device data
        $byDeviceDate = [];
        $deviceIdMap = [];
        $differenceByDate = [];

        foreach ($deviceData as $row) {
            $byDeviceDate[$row['device']][$row['date']] = round($row['energy'], 2);
            $deviceIdMap[$row['device']] = $row['deviceId'];
        }

        // Prepare chart datasets for devices
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

        return $this->render('graphs/weekly_device_usage.html.twig', [
            'user' => $user,
            'consumptionKwh' => $lastSnapshot?->getConsumptionKwh(),
            'dailyEnergySummary' => $deviceData,
            'chartData' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ]
        ]);
    }

    #[Route('/daily-device-usage-graph', name: 'graph_daily_device_usage')]
    public function dailyDeviceUsageGraph(): Response
    {
        $user = $this->getUser();
        $day = new \DateTime('2025-05-21');

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
        sort($labels); // Optional: ensures correct chronological order

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
                'tension' => 0.1,
            ];
        }

        return $this->render('graphs/daily_device_usage.html.twig', [
            'chartData' => [
                'labels' => $labels,
                'datasets' => $datasets,
                'priceRateBands' => $priceRateBands,
            ],
        ]);
    }

    #[Route('/daily-energy-usage-graph', name: 'graph_daily_energy_usage')]
    public function dailyEnergyUsageGraph(): Response
    {
        $user = $this->getUser();
        $day = new \DateTime('2025-05-20');

        //$day = new \DateTime('today');
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

        return $this->render('graphs/daily_energy_usage.html.twig', [
            'chartData' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ],
        ]);
    }

    #[Route('/weekly-energy-price-graph', name: 'graph_weekly_energy_price')]
    public function weeklyEnergyPriceGraph(): Response
    {
        $user = $this->getUser();

        $startDate = new \DateTimeImmutable('2025-01-21 00:00:00');
        $endDate   = new \DateTimeImmutable('2025-05-30 23:59:59');

        $raw = $this->userEnergySnapshotRepository
            ->getWeeklyCostByPeriod($user, $startDate, $endDate);

        $weeks = array_unique(array_column($raw, 'week_start'));
        sort($weeks);

        $periods = array_unique(array_column($raw, 'period'));

        $costBy = [];
        foreach ($raw as $row) {
            $costBy[$row['period']][$row['week_start']] = round((float)$row['cost'], 2);
        }

        $datasets = [];
        foreach ($periods as $periodName) {
            $data = array_map(
                fn($wk) => $costBy[$periodName][$wk] ?? 0,
                $weeks
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

        return $this->render('graphs/weekly_energy_price.html.twig', [
            'chartData' => [
                'labels'   => $weeks,
                'datasets' => $datasets,
            ],
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('User Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        $userId = $this->getUser()->getId();

        yield MenuItem::linkToDashboard('My Dashboard', 'fas fa-chart-line');
        yield MenuItem::subMenu('Graphs', 'fas fa-chart-bar')->setSubItems([
            MenuItem::linkToRoute('Logged Usage / day', 'fas fa-microchip', 'graph_daily_device_usage'),
            MenuItem::linkToRoute('Energy Usage / week', 'fas fa-bolt', 'graph_weekly_device_usage'),
            MenuItem::linkToRoute('Energy Usage / day', 'fas fa-calendar', 'graph_daily_energy_usage'),
            MenuItem::linkToRoute('Energy Price / week', 'fas fa-euro', 'graph_weekly_energy_price'),
        ]);
        yield MenuItem::linkToUrl(
            'Edit Profile',
            'fa fa-user',
            $this->adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setAction('edit')
                ->setEntityId($userId)
                ->generateUrl()
        );
        yield MenuItem::linkToCrud('My Devices', 'fas fa-microchip', Device::class);
        yield MenuItem::linkToCrud('Usage Logs', 'fa fa-clock', DeviceUsageLog::class);
        yield MenuItem::linkToCrud('Energy Logs', 'fa fa-clock', UserEnergySnapshot::class);
        yield MenuItem::linkToCrud('Price Rate', 'fa fa-clock', PriceRatePeriod::class);
    }
}
