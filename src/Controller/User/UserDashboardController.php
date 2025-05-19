<?php

namespace App\Controller\User;

use AllowDynamicProperties;
use App\Controller\Admin\UserCrudController;
use App\Entity\Device;
use App\Entity\DeviceUsageLog;
use App\Entity\User;
use App\Entity\UserEnergySnapshot;
use App\Repository\DeviceRepository;
use App\Repository\DeviceUsageLogRepository;
use App\Repository\UserEnergySnapshotRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[AllowDynamicProperties]
#[AdminDashboard(routePath: '/profile', routeName: 'app_user_dashboard')]
class UserDashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
        DeviceRepository $deviceRepository,
        DeviceUsageLogRepository $deviceUsageLogRepository,
        UserEnergySnapshotRepository $userEnergySnapshotRepository,
        ChartBuilderInterface $chartBuilder,
    )
    {
        $this->deviceRepository = $deviceRepository;
        $this->deviceUsageLogRepository = $deviceUsageLogRepository;
        $this->userEnergySnapshotRepository = $userEnergySnapshotRepository;
        $this->chartBuilder = $chartBuilder;
    }

    #[Route('/dashboard', name: 'user_dashboard_index')]
    public function index(): Response
    {
        $data = $this->deviceUsageLogRepository->getDailyDeviceEnergySummary($this->getUser());

        $byDateDevice = [];
        $deviceIdMap = [];

        foreach ($data as $row) {
            $date = $row['date'];
            $device = $row['device'];
            $deviceId = $row['deviceId'];
            $energy = round($row['energy'], 2);

            $byDateDevice[$device][$date] = $energy;
            $deviceIdMap[$device] = $deviceId;
        }

        $labels = array_unique(array_column($data, 'date'));
        sort($labels);

        $datasets = [];
        foreach ($deviceIdMap as $device => $deviceId) {
            $values = [];
            foreach ($labels as $label) {
                $values[] = $byDateDevice[$device][$label] ?? 0;
            }

            $datasets[] = [
                'label' => $device,
                'data' => $values,
                'stack' => 'energy',
                'deviceId' => $deviceId,
            ];
        }

        $lastSnapshot = $this->userEnergySnapshotRepository->findOneBy(
            ['user' => $this->getUser()],
            ['timestamp' => 'DESC']
        );

        $lastKwh = $lastSnapshot ? $lastSnapshot->getConsumptionKwh() : null;

        $userDevices = $this->deviceRepository->findBy(['user' => $this->getUser()]);

        return $this->render('user_dashboard/index.html.twig', [
            'user' => $this->getUser(),
            'consumptionKwh' => $lastKwh,
            'dailyEnergySummary' => $data,
            'chartData' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ],
            'devices' => array_map(fn(Device $d) => [
                'id' => $d->getId(),
                'name' => $d->getName(),
            ], $userDevices),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('User Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        $userId = $this->getUser()->getId();

        $editProfileUrl = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->setAction('edit')
            ->setEntityId($userId)
            ->generateUrl();

        yield MenuItem::linkToDashboard('My Dashboard', 'fas fa-chart-line');
        yield MenuItem::linkToUrl('Edit Profile', 'fa fa-user', $editProfileUrl);
        yield MenuItem::linkToCrud('My Devices', 'fas fa-microchip', Device::class);
        yield MenuItem::linkToCrud('Usage Logs', 'fa fa-clock', DeviceUsageLog::class);
        yield MenuItem::linkToCrud('Energy Logs', 'fa fa-clock', UserEnergySnapshot::class);
    }
}
