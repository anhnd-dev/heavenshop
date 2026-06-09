<?php

namespace App\Services\Admin\Dashboard;

use App\Models\Order;
use Carbon\Carbon;

class RevenueService
{
    public function getData(): array
    {
        return [
            'revenueByDay'   => $this->getRevenueByDay(),
            'revenueByMonth' => $this->getRevenueByMonth(),
            'growthRate'     => $this->getGrowthRate(),
        ];
    }

    private function getRevenueByDay(): array
    {
        $revenues = Order::query()
            ->selectRaw("
                DATE(created_at) as day,
                SUM(grand_total) as revenue
            ")
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('revenue', 'day')
            ->toArray();

        $result = [];

        for ($i = 29; $i >= 0; $i--) {

            $day = Carbon::now()
                ->subDays($i)
                ->format('Y-m-d');

            $result[$day] = $revenues[$day] ?? 0;
        }

        return $result;
    }

    private function getRevenueByMonth(): array
    {
        $revenues = Order::query()
            ->selectRaw("
                DATE_FORMAT(created_at,'%Y-%m') as month,
                SUM(grand_total) as revenue
            ")
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        $result = [];

        for ($i = 11; $i >= 0; $i--) {

            $month = Carbon::now()
                ->subMonths($i)
                ->format('Y-m');

            $result[$month] = $revenues[$month] ?? 0;
        }

        return $result;
    }

    private function getGrowthRate(): array
    {
        $currentMonthRevenue = Order::query()
            ->where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('grand_total');

        $previousMonthRevenue = Order::query()
            ->where('payment_status', 'paid')
            ->whereYear(
                'created_at',
                now()->copy()->subMonth()->year
            )
            ->whereMonth(
                'created_at',
                now()->copy()->subMonth()->month
            )
            ->sum('grand_total');

        $growth = $previousMonthRevenue > 0
            ? (
                ($currentMonthRevenue - $previousMonthRevenue)
                / $previousMonthRevenue
            ) * 100
            : 0;

        return [
            'current' => $currentMonthRevenue,
            'previous' => $previousMonthRevenue,
            'growth' => round($growth, 2),
        ];
    }
}
