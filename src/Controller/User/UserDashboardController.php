<?php

namespace App\Controller\User;

use App\Controller\Admin\UserCrudController;
use App\Entity\Device;
use App\Entity\DeviceUsageLog;
use App\Entity\PriceRatePeriod;
use App\Entity\UserEnergySnapshot;
use App\Repository\DeviceRepository;
use App\Repository\DeviceUsageLogRepository;
use App\Repository\UserEnergySnapshotRepository;
use App\Service\UserDashboardDataService;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AdminDashboard(routePath: '/profile', routeName: 'app_user_dashboard')]
class UserDashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly DeviceUsageLogRepository $deviceUsageLogRepository,
        private readonly DeviceRepository $deviceRepository,
        private readonly UserEnergySnapshotRepository $userEnergySnapshotRepository,
        private readonly UserDashboardDataService $dashboardData
    ) {}


    #[Route('/', name: 'user_dashboard_index')]
    public function index(): Response
    {
        $user = $this->getUser();

        $today = new \DateTimeImmutable();
        $startOfThisMonth = (new \DateTimeImmutable('first day of this month'))->setTime(0, 0, 0);
        $lastMonthSameDay = $today->modify('-1 month');

        $currentMonthConsumption = $this->userEnergySnapshotRepository->getMonthlyConsumptionUntilDay($user, $today);
        $lastMonthConsumption = $this->userEnergySnapshotRepository->getMonthlyConsumptionUntilDay($user, $lastMonthSameDay);

        if ($lastMonthConsumption === 0.0) {
            $percentageChangeMonthlyConsumption = $currentMonthConsumption > 0 ? 100 : 0;
        } else {
            $percentageChangeMonthlyConsumption = (($currentMonthConsumption - $lastMonthConsumption) / $lastMonthConsumption) * 100;
        }

        // TODO use the getMonthlyConsumptionUntilDay instead
        $totalMonthlyConsumption = $this->userEnergySnapshotRepository->getMonthlyConsumption($user);
        $loggedMonthlyConsumption = $this->deviceUsageLogRepository->getLoggedMonthlyConsumption($user);

        // TODO use the getTotalMonthlyCostUntil instead
        $totalMonthlyCost = $this->userEnergySnapshotRepository->getTotalMonthlyCost($user);
        $currentMonthConst = $this->userEnergySnapshotRepository->getTotalMonthlyCostUntil($user, $today);
        $lastMonthConst = $this->userEnergySnapshotRepository->getTotalMonthlyCostUntil($user, $lastMonthSameDay);
        if ($lastMonthConst === 0.0) {
            $changeMonthlyConst = $currentMonthConst > 0 ? 100 : 0;
        } else {
            $changeMonthlyConst = $currentMonthConst - $lastMonthConst;
        }

        $totalDevices = $this->deviceRepository->countAllDevices($user);

        $startDate = (new \DateTimeImmutable('-6 days'))->setTime(0, 0, 0);
        $endDate = new \DateTimeImmutable();

        $dataPrice = $this->dashboardData->getPeriodEnergyPriceGraphData($user, $startOfThisMonth, $today,'month');
        $dataDeviceConsumption = $this->dashboardData->getWeeklyDeviceUsageGraphData($user,$startDate,$endDate);
        $dataEnergyConsumption = $this->dashboardData->getDailyEnergyUsageGraphData($user,$today);

        return $this->render('user/dashboard/index.html.twig', [
            'user' => $user,
            'totalMonthlyConsumption' => $totalMonthlyConsumption,
            'loggedMonthlyConsumption' => $loggedMonthlyConsumption,
            'currentMonthConst' => $currentMonthConst,
            'totalDevices' => $totalDevices,
            'currentMonthConsumption' => $currentMonthConsumption,
            'lastMonthConsumption' => $lastMonthConsumption,
            'percentageChangeMonthlyConsumption'=> sprintf('%+.2f%%', $percentageChangeMonthlyConsumption),
            'changeMonthlyConst'=> $changeMonthlyConst,
            'chartDataPrice'=> $dataPrice,
            'chartDataEnergy'=> $dataEnergyConsumption,
            'chartData'=> $dataDeviceConsumption,
        ]);
    }

    #[Route('/weekly-device-usage-graph', name: 'graph_weekly_device_usage')]
    public function weeklyDeviceUsageGraph(): Response
    {
        $user = $this->getUser();

        $startDate = (new \DateTimeImmutable('-6 days'))->setTime(0, 0, 0);
        $endDate = new \DateTimeImmutable();

        $data = $this->dashboardData->getWeeklyDeviceUsageGraphData($user,$startDate,$endDate);

        return $this->render('user/graphs/weekly_device_usage.html.twig', [
            'chartData' => $data
        ]);
    }

    #[Route('/daily-device-usage-graph', name: 'graph_daily_device_usage')]
    public function dailyDeviceUsageGraph(): Response
    {
        $user = $this->getUser();

        $day = new \DateTimeImmutable('2025-05-20');

        $data = $this->dashboardData->getDailyDeviceUsageGraphData($user, $day);

        return $this->render('user/graphs/daily_device_usage.html.twig', [
            'chartData' => $data,
        ]);
    }

    #[Route('/daily-energy-usage-graph', name: 'graph_daily_energy_usage')]
    public function dailyEnergyUsageGraph(): Response
    {
        $user = $this->getUser();

        $day = new \DateTimeImmutable('now');

        $data = $this->dashboardData->getDailyEnergyUsageGraphData($user, $day);

        return $this->render('user/graphs/daily_energy_usage.html.twig', [
            'chartDataEnergy' => $data
        ]);
    }

    #[Route('/monthly-energy-usage-graph', name: 'graph_monthly_energy_usage')]
    public function monthlyEnergyUsageGraph(): Response
    {
        $user = $this->getUser();

        $endDate = new \DateTimeImmutable('now');
        $startDate = (new \DateTimeImmutable('now'))->modify('-6 months')->modify('first day of this month');

        $data = $this->dashboardData->getMonthlyEnergyUsageGraphData($user, $startDate, $endDate);

        return $this->render('user/graphs/monthly_energy_usage.html.twig', [
            'chartData' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    #[Route('/weekly-energy-price-graph', name: 'graph_weekly_energy_price')]
    public function weeklyEnergyPriceGraph(): Response
    {
        $user = $this->getUser();

        $endDate = new \DateTimeImmutable('today 23:59:59');
        $startDate = $endDate->modify('-3 weeks')->modify('monday this week')->setTime(0, 0, 0);

        $data = $this->dashboardData->getPeriodEnergyPriceGraphData($user, $startDate, $endDate, 'week');

        return $this->render('user/graphs/weekly_energy_price.html.twig', [
            'chartData' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodType' => 'week',
        ]);
    }

    #[Route('/monthly-energy-price-graph', name: 'graph_monthly_energy_price')]
    public function monthlyEnergyPriceGraph(): Response
    {
        $user = $this->getUser();

        $endDate = new \DateTimeImmutable('today 23:59:59');
        $startDate = $endDate->modify('-5 months')->modify('first day of this month')->setTime(0, 0, 0);

        $data = $this->dashboardData->getPeriodEnergyPriceGraphData($user, $startDate, $endDate, 'month');

        return $this->render('user/graphs/weekly_energy_price.html.twig', [
            'chartData' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodType' => 'month'
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('User Dashboard')->disableDarkMode();
    }

    public function configureMenuItems(): iterable
    {
        $userId = $this->getUser()->getId();

        yield MenuItem::linkToDashboard('My Dashboard', 'fas fa-object-group');

        yield MenuItem::section('Account');
        yield MenuItem::linkToCrud('Devices', 'fas fa-laptop', Device::class);
        yield MenuItem::linkToCrud('Price Rates', 'fa fa-credit-card', PriceRatePeriod::class);
        yield MenuItem::linkToUrl(
            'Profile',
            'fa-regular fa-user',
            $this->adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setAction('edit')
                ->setEntityId($userId)
                ->generateUrl()
        );

        yield MenuItem::section('Graphs');
        yield MenuItem::linkToRoute('Device Consump. / day', 'fa fa-chart-line', 'graph_daily_device_usage');
        yield MenuItem::linkToRoute('Device Consump. / week', 'fa fa-chart-line', 'graph_weekly_device_usage');
        yield MenuItem::linkToRoute('SmartMeter Reads / day', 'fa fa-chart-line', 'graph_daily_energy_usage');
        yield MenuItem::linkToRoute('SmartMeter Reads / month', 'fa fa-chart-line', 'graph_monthly_energy_usage');
        yield MenuItem::linkToRoute('SmartMeter Reads Price / week', 'fa fa-chart-line', 'graph_weekly_energy_price');
        yield MenuItem::linkToRoute('SmartMeter Reads Price / month', 'fa fa-chart-line', 'graph_monthly_energy_price');

        yield MenuItem::section('Logs');
        yield MenuItem::linkToCrud('Device Usage', 'fa-regular fa-rectangle-list', DeviceUsageLog::class);
        yield MenuItem::linkToCrud('Energy SmartMeter', 'fa-regular fa-rectangle-list', UserEnergySnapshot::class);

        yield MenuItem::section();
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
    }
}
