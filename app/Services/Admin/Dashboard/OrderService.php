<?php

namespace App\Services\Admin\Dashboard;

use App\Models\Order;
use Carbon\Carbon;

class OrderService
{
    public function getData(): array
    {
        return [
            'orderStatus' => $this->getOrderStatus(),
            'orderTrend' => $this->getOrderTrend(),
            'deliveryRate' => $this->getDeliveryRate(),
            'processingTime' => $this->getProcessingTime(),
        ];
    }

    private function getOrderStatus(): array
    {
        return Order::query()
            ->selectRaw("
                order_status,
                COUNT(*) as total
            ")
            ->groupBy('order_status')
            ->pluck('total', 'order_status')
            ->toArray();
    }

    private function getOrderTrend(): array
    {
        $orders = Order::query()
            ->selectRaw("
                DATE(created_at) as day,
                COUNT(*) as total
            ")
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $result = [];

        for ($i = 29; $i >= 0; $i--) {

            $day = Carbon::now()
                ->subDays($i)
                ->format('Y-m-d');

            $result[$day] = $orders[$day] ?? 0;
        }

        return $result;
    }

    private function getDeliveryRate(): array
    {
        $totalOrders = Order::count();

        $deliveredOrders = Order::where(
            'order_status',
            'delivered'
        )->count();

        $cancelledOrders = Order::where(
            'order_status',
            'cancelled'
        )->count();

        $returnedOrders = Order::where(
            'order_status',
            'returned'
        )->count();

        return [
            'total' => $totalOrders,
            'delivered' => $deliveredOrders,
            'cancelled' => $cancelledOrders,
            'returned' => $returnedOrders,
            'rate' => $totalOrders > 0
                ? round(
                    ($deliveredOrders / $totalOrders) * 100,
                    2
                )
                : 0,
        ];
    }

    private function getProcessingTime(): array
    {
        $stats = Order::query()
            ->whereNotNull('confirmed_at')
            ->whereNotNull('shipped_at')
            ->whereNotNull('delivered_at')
            ->selectRaw("
            AVG(TIMESTAMPDIFF(HOUR, confirmed_at, shipped_at)) as confirm_to_ship,
            AVG(TIMESTAMPDIFF(HOUR, shipped_at, delivered_at)) as ship_to_delivery,
            AVG(TIMESTAMPDIFF(HOUR, confirmed_at, delivered_at)) as confirm_to_delivery
        ")
            ->first();

        return [
            'confirm_to_ship' => round($stats->confirm_to_ship ?? 0, 1),
            'ship_to_delivery' => round($stats->ship_to_delivery ?? 0, 1),
            'confirm_to_delivery' => round($stats->confirm_to_delivery ?? 0, 1),
        ];
    }
}
