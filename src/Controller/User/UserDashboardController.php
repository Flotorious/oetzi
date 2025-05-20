<?php

namespace App\Controller\User;

use App\Controller\Admin\UserCrudController;
use App\Entity\Device;
use App\Entity\DeviceUsageLog;
use App\Entity\UserEnergySnapshot;
use App\Repository\DeviceRepository;
use App\Repository\DeviceUsageLogRepository;
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
        private readonly DeviceRepository $deviceRepository,
        private readonly DeviceUsageLogRepository $deviceUsageLogRepository,
        private readonly UserEnergySnapshotRepository $userEnergySnapshotRepository,
        private readonly ChartBuilderInterface $chartBuilder
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
                'borderWidth' => 1,
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

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('User Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        $userId = $this->getUser()->getId();

        yield MenuItem::linkToDashboard('My Dashboard', 'fas fa-chart-line');
        yield MenuItem::subMenu('Graphs', 'fas fa-chart-bar')->setSubItems([
            MenuItem::linkToRoute('Energy Graph', 'fas fa-bolt', 'graph_weekly_device_usage'),
            MenuItem::linkToRoute('Device Graph', 'fas fa-microchip', 'graph_weekly_device_usage'),
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
    }
}
