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
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[AllowDynamicProperties]
#[AdminDashboard(routePath: '/profile', routeName: 'app_user_dashboard')]
class UserDashboardController extends AbstractDashboardController
{

    private ChartBuilderInterface $chartBuilder;

    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
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
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //

        $qb = $this->deviceRepository->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->where('d.user = :user')
            ->setParameter('user', $this->getUser());

        $lastSnapshot = $this->userEnergySnapshotRepository->findOneBy(
            ['user' => $this->getUser()],
            ['timestamp' => 'DESC']
        );

        $lastKwh = $lastSnapshot ? $lastSnapshot->getConsumptionKwh() : null;

        $numberDevices = (int) $qb->getQuery()->getSingleScalarResult();

        $data = $this->deviceUsageLogRepository->getDailyEnergySummary($this->getUser());

        return $this->render('user_dashboard/index.html.twig', [
            'user' => $this->getUser(),
            'numberDevices' => $numberDevices,
            'consumptionKwh' => $lastKwh,
            'dailyEnergySummary' => $data,
            'chart' => $this->createChartWithDevice()
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



    private function createChart(): Chart
    {
        $data = $this->deviceUsageLogRepository->getDailyEnergySummary($this->getUser());

        $labels = array_column($data, 'date'); // X-axis: ['2025-04-08', ...]
        $values = array_map(fn($d) => round($d['total_energy'], 2), $data); // Y-axis: [3.4, 4.1, ...]

        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Energy Usage (kWh)',
                    'backgroundColor' => 'rgb(54, 162, 235)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'data' => $values,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ]);

        return $chart;
    }

    private function createChartWithDevice(): Chart
    {
        $data = $this->deviceUsageLogRepository->getDailyDeviceEnergySummary($this->getUser());

        $byDateDevice = [];
        $devices = [];

        foreach ($data as $row) {
            $date = $row['date'];
            $device = $row['device'];
            $energy = round($row['energy'], 2);

            $byDateDevice[$device][$date] = $energy;
            $devices[$device] = true;
        }

        $labels = array_unique(array_column($data, 'date'));
        sort($labels); // sort by date

        $datasets = [];
        foreach (array_keys($devices) as $device) {
            $values = [];

            foreach ($labels as $label) {
                $values[] = $byDateDevice[$device][$label] ?? 0;
            }

            $datasets[] = [
                'label' => $device,
                'data' => $values,
                'stack' => 'energy', // Key to enable stacking
            ];
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);

        $chart->setOptions([
            'responsive' => true,
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Daily Energy Usage by Device',
                ],
            ],
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                ],
            ],
        ]);

        return $chart;
    }

}
