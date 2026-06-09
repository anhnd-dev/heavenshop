<?php

namespace App\Services\Admin\Dashboard;

use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;

class OverviewService
{
    public function getData(): array
    {
        return [
            'kpis'            => $this->getKpis(),
            'monthlyRevenue'  => $this->getMonthlyRevenue(),
            'orderComparison' => $this->getOrderComparison(),
        ];
    }

    private function getKpis(): array
    {
        $totalRevenue = Order::query()
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $totalOrders = Order::count();

        $totalCustomers = Customer::count();

        $avgOrderValue = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;

        return [
            'totalRevenue'   => $totalRevenue,
            'totalOrders'    => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'avgOrderValue'  => $avgOrderValue,
        ];
    }

    private function getMonthlyRevenue(): array
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

    private function getOrderComparison(): array
    {
        $today = Order::whereDate(
            'created_at',
            today()
        )->count();

        $yesterday = Order::whereDate(
            'created_at',
            today()->subDay()
        )->count();

        $growth = $yesterday > 0
            ? (($today - $yesterday) / $yesterday) * 100
            : 0;

        return [
            'today'      => $today,
            'yesterday'  => $yesterday,
            'growth'     => round($growth, 2),
        ];
    }
}
