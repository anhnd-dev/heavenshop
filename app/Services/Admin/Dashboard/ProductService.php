<?php

namespace App\Services\Admin\Dashboard;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getData(): array
    {
        return [
            'productSummary'    => $this->getProductSummary(),
            'bestSellers'       => $this->getBestSellers(),
            'topRevenueProducts' => $this->getTopRevenueProducts(),
            'topVariants'          => $this->getTopVariants(),
            'unsoldVariants'       => $this->getUnsoldVariants(),
            'averageOrderQuantity' => $this->getAverageOrderQuantity(),
        ];
    }

    private function getProductSummary(): array
    {
        return [
            'totalSold' => OrderItem::sum('quantity'),

            'totalRevenue' => OrderItem::sum('total'),

            'totalProducts' => OrderItem::distinct('product_id')
                ->count('product_id'),

            'unsoldProducts' => Product::doesntHave('orderItems')
                ->count(),

            'avgOrderQuantity' => $this->getAverageOrderQuantity(),
        ];
    }

    private function getBestSellers()
    {
        return OrderItem::query()
            ->select([
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as sold'),
            ])
            ->groupBy(
                'product_id',
                'product_name'
            )
            ->orderByDesc('sold')
            ->limit(10)
            ->get();
    }

    private function getTopRevenueProducts()
    {
        return OrderItem::query()
            ->select([
                'product_id',
                'product_name',
                DB::raw('SUM(total) as revenue'),
            ])
            ->groupBy(
                'product_id',
                'product_name'
            )
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();
    }

    private function getUnsoldVariants()
    {
        return ProductVariant::query()
            ->with('product', 'color', 'size')
            ->whereDoesntHave('orderItems')
            ->latest()
            ->limit(20)
            ->get();
    }

    private function getTopVariants()
    {
        return OrderItem::query()
            ->selectRaw("
            product_variant_id,
            product_name,
            color_name,
            size_name,
            SUM(quantity) as sold
        ")
            ->whereNotNull('product_variant_id')
            ->groupBy(
                'product_variant_id',
                'product_name',
                'color_name',
                'size_name'
            )
            ->orderByDesc('sold')
            ->limit(10)
            ->get();
    }

    private function getAverageOrderQuantity(): float
    {
        return round(
            OrderItem::query()
                ->selectRaw("
                AVG(order_quantity.total_qty)
            as avg_qty
            ")
                ->fromSub(
                    OrderItem::query()
                        ->selectRaw("
                        order_id,
                        SUM(quantity) as total_qty
                    ")
                        ->groupBy('order_id'),
                    'order_quantity'
                )
                ->value('avg_qty'),
            2
        );
    }
}
