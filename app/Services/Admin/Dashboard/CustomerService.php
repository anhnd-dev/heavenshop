<?php

namespace App\Services\Admin\Dashboard;

use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function getData(): array
    {
        return [
            'customerSummary'   => $this->getCustomerSummary(),
            'newCustomers'      => $this->getNewCustomers(),
            'returningCustomers' => $this->getReturningCustomers(),
            'topSpenders'       => $this->getTopSpenders(),
            'customerGrowth'        => $this->getCustomerGrowth(),
            'averageCustomerValue'  => $this->getAverageCustomerValue(),
        ];
    }

    private function getCustomerSummary(): array
    {
        $totalCustomers = Customer::count();

        $newCustomers = Customer::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $returningCustomers = Order::query()
            ->select('customer_id')
            ->groupBy('customer_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        return [
            'totalCustomers' => $totalCustomers,
            'newCustomers' => $newCustomers,
            'returningCustomers' => $returningCustomers,

            'returnRate' => $totalCustomers > 0
                ? round(
                    ($returningCustomers / $totalCustomers) * 100,
                    2
                )
                : 0,

            'averageCustomerValue'
            => $this->getAverageCustomerValue(),
        ];
    }

    private function getNewCustomers()
    {
        return Customer::query()
            ->latest()
            ->limit(20)
            ->get();
    }

    private function getReturningCustomers()
    {
        return Order::query()
            ->select([
                'customer_id',
                DB::raw('COUNT(*) as total_orders'),
            ])
            ->with('customer')
            ->groupBy('customer_id')
            ->havingRaw('COUNT(*) > 1')
            ->orderByDesc('total_orders')
            ->limit(20)
            ->get();
    }

    private function getTopSpenders()
    {
        return Order::query()
            ->select([
                'customer_id',
                DB::raw('SUM(grand_total) as spending'),
                DB::raw('COUNT(*) as total_orders'),
            ])
            ->with('customer')
            ->where('payment_status', 'paid')
            ->groupBy('customer_id')
            ->orderByDesc('spending')
            ->limit(10)
            ->get();
    }

    private function getAverageCustomerValue(): float
    {
        $totalRevenue = Order::query()
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $totalCustomers = Order::query()
            ->distinct('customer_id')
            ->count('customer_id');

        if ($totalCustomers === 0) {
            return 0;
        }

        return round(
            $totalRevenue / $totalCustomers,
            0
        );
    }

    private function getCustomerGrowth(): array
    {
        $customers = Customer::query()
            ->selectRaw("
            DATE_FORMAT(created_at,'%Y-%m') as month,
            COUNT(*) as total
        ")
            ->where(
                'created_at',
                '>=',
                now()->subMonths(11)->startOfMonth()
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $result = [];

        for ($i = 11; $i >= 0; $i--) {

            $month = Carbon::now()
                ->subMonths($i)
                ->format('Y-m');

            $result[$month] =
                $customers[$month] ?? 0;
        }

        return $result;
    }
}
