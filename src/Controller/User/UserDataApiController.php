<?php

namespace App\Controller\User;

use App\Service\UserDashboardDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserDataApiController extends AbstractController
{
    public function __construct(
        private readonly UserDashboardDataService $dashboardData
    ) {}

    #[Route('/ajax/daily-device-usage', name: 'ajax_daily_device_usage')]
    public function ajaxDailyDeviceUsage(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $day = $request->query->get('day')
            ? new \DateTimeImmutable($request->query->get('day'))
            : new \DateTimeImmutable();

        $data = $this->dashboardData->getDailyDeviceUsageGraphData($user, $day);
        return $this->json($data);
    }


    #[Route('/ajax/weekly-device-usage', name: 'ajax_weekly_device_usage')]
    public function ajaxWeeklyDeviceUsage(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $start = $request->query->get('start')
            ? new \DateTimeImmutable($request->query->get('start'))
            : (new \DateTimeImmutable('-6 days'))->setTime(0, 0, 0);
        $end = $request->query->get('end')
            ? new \DateTimeImmutable($request->query->get('end'))
            : new \DateTimeImmutable();

        $data = $this->dashboardData->getWeeklyDeviceUsageGraphData($user, $start, $end);

        return $this->json($data);
    }

    #[Route('/ajax/daily-energy-usage', name: 'ajax_daily_energy_usage')]
    public function ajaxDailyEnergyUsage(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $day = $request->query->get('day')
            ? new \DateTimeImmutable($request->query->get('day'))
            : new \DateTimeImmutable();

        $data = $this->dashboardData->getDailyEnergyUsageGraphData($user, $day);
        return $this->json($data);
    }

    #[Route('/ajax/monthly-energy-usage', name: 'ajax_monthly_energy_usage')]
    public function ajaxMonthlyEnergyUsage(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $end = $request->query->get('end')
            ? new \DateTimeImmutable($request->query->get('end'))
            : new \DateTimeImmutable('now');
        $start = $request->query->get('start')
            ? new \DateTimeImmutable($request->query->get('start'))
            : (new \DateTimeImmutable('now'))->modify('-6 months')->modify('first day of this month');

        $data = $this->dashboardData->getMonthlyEnergyUsageGraphData($user, $start, $end);

        return $this->json($data);
    }

    #[Route('/ajax/monthly-energy-price', name: 'ajax_monthly_energy_price')]
    public function ajaxMonthlyEnergyPrice(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $start = $request->query->get('start')
            ? new \DateTimeImmutable($request->query->get('start'))
            : (new \DateTimeImmutable('first day of this month'))->setTime(0, 0, 0);
        $end = $request->query->get('end')
            ? new \DateTimeImmutable($request->query->get('end'))
            : new \DateTimeImmutable();

        $data = $this->dashboardData->getPeriodEnergyPriceGraphData($user, $start, $end, 'month');
        return $this->json($data);
    }

    #[Route('/ajax/period-energy-price', name: 'ajax_period_energy_price')]
    public function ajaxPeriodEnergyPrice(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $start = $request->query->get('start')
            ? new \DateTimeImmutable($request->query->get('start'))
            : new \DateTimeImmutable('2025-01-21');
        $end   = $request->query->get('end')
            ? new \DateTimeImmutable($request->query->get('end'))
            : new \DateTimeImmutable('2025-05-30');
        $periodType = $request->query->get('periodType', 'week'); // or 'month'

        $data = $this->dashboardData->getPeriodEnergyPriceGraphData($user, $start, $end, $periodType);
        return $this->json($data);
    }

}
