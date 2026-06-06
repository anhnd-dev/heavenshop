<?php

namespace App\Services\Frontend;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Frontend;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CollectionService
{
    public function getCollectionData(
        Request $request,
        string $path
    ): array {

        $shippingFreeThreshold = Frontend::getSetting(
            'shipping_free_threshold',
            0
        );


        // =====================================================
        // CATEGORY
        // =====================================================

        $category = Category::query()

            ->select(
                'id',
                'name',
                'slug',
                'parent_id'
            )

            ->with('children:id,name,slug,parent_id')

            ->where('slug', $path)

            ->firstOrFail();

        // =====================================================
        // CATEGORY IDS
        // =====================================================

        $categoryIds = $category->getAllChildrenIds();

        // =====================================================
        // FILTERS
        // =====================================================

        $filters = $this->getFilters($request);

        // =====================================================
        // PRODUCTS QUERY
        // =====================================================

        $query = Product::query()

            ->select([
                'id',
                'name',
                'slug',
                'image',
                'brand_id',
                'category_id',
                'created_at',
            ])

            ->with([

                'brand:id,name',

                'category:id,name,slug',

                'variants' => function ($q) {

                    $q->select([
                        'id',
                        'product_id',
                        'color_id',
                        'size_id',
                        'price',
                        'sale_price',
                        'stock',
                    ])

                        ->where('is_active', true);
                },

                'variants.color:id,name,code',

                'variants.size:id,name',
            ])

            ->withMin([
                'variants as variants_min_price' => function ($q) {

                    $q->where('is_active', true)
                        ->where('stock', '>', 0);
                }
            ], 'price')

            ->withMin([
                'variants as variants_min_sale_price' => function ($q) {

                    $q->where('is_active', true)
                        ->where('stock', '>', 0)
                        ->whereNotNull('sale_price');
                }
            ], 'sale_price')

            ->where('is_active', true)

            ->whereIn('category_id', $categoryIds)

            ->whereHas('variants', function ($q) {

                $q->where('is_active', true)
                    ->where('stock', '>', 0);
            });

        // =====================================================
        // APPLY FILTERS
        // =====================================================

        $this->applyFilters(
            query: $query,
            filters: $filters
        );

        // =====================================================
        // APPLY SORT
        // =====================================================

        $this->applySorting(
            query: $query,
            order: $filters['order']
        );

        // =====================================================
        // PRODUCTS
        // =====================================================

        $products = $query

            ->paginate($filters['psize'])

            ->withQueryString();

        // =====================================================
        // RELATED CATEGORIES
        // =====================================================

        $relatedCategories = $category->children()
            ->select('id', 'name', 'slug', 'parent_id')
            ->get();

        $productCounts = Product::query()
            ->selectRaw('category_id, COUNT(*) as total')
            ->where('is_active', true)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $relatedCategories->each(function ($item) use ($productCounts) {

            $ids = $item->getAllChildrenIds();

            $item->products_count = collect($ids)
                ->sum(fn($id) => $productCounts[$id] ?? 0);
        });

        // =====================================================
        // FILTER DATA
        // =====================================================

        $brands = Cache::remember(
            'collection_brands',
            now()->addHours(12),

            function () {

                return Brand::query()

                    ->select(
                        'id',
                        'name'
                    )

                    ->where('is_active', true)

                    ->withCount('products')

                    ->orderBy('name')

                    ->get();
            }
        );

        $colors = Cache::remember(
            'collection_colors',
            now()->addHours(12),

            function () {

                return Color::query()

                    ->select(
                        'id',
                        'name',
                        'code'
                    )

                    ->where('is_active', true)

                    ->withCount('products')

                    ->orderBy('name')

                    ->get();
            }
        );

        $sizes = Cache::remember(
            'collection_sizes',
            now()->addHours(12),

            function () {

                return Size::query()

                    ->select(
                        'id',
                        'name'
                    )

                    ->where('is_active', true)

                    ->withCount('products')

                    ->orderBy('name')

                    ->get();
            }
        );

        // =====================================================
        // RETURN
        // =====================================================

        return [

            'category' => $category,

            'products' => $products,

            'relatedCategories' => $relatedCategories,

            'brands' => $brands,

            'colors' => $colors,

            'sizes' => $sizes,

            'psize' => $filters['psize'],
            'order' => $filters['order'],

            'q_brands' => $filters['q_brands'],
            'q_colors' => $filters['q_colors'],
            'q_sizes' => $filters['q_sizes'],

            'activeCategoryIds' => [$category->id],

            'shippingFreeThreshold' => $shippingFreeThreshold
        ];
    }

    /**
     * =====================================================
     * FILTERS
     * =====================================================
     */

    protected function getFilters(
        Request $request
    ): array {

        $allowedPageSizes = [12, 24, 48];

        $psize = in_array(
            (int) $request->query('psize'),
            $allowedPageSizes
        )
            ? (int) $request->query('psize')
            : 12;

        $order = (int) $request->query('order', 1);

        $q_brands = $request->query('brands', '');
        $q_colors = $request->query('colors', '');
        $q_sizes  = $request->query('sizes', '');

        return [

            'psize' => $psize,

            'order' => $order,

            'q_brands' => $q_brands,
            'q_colors' => $q_colors,
            'q_sizes' => $q_sizes,

            'brandIds' => $this->sanitizeIds($q_brands),

            'colorIds' => $this->sanitizeIds($q_colors),

            'sizeIds' => $this->sanitizeIds($q_sizes),
        ];
    }

    /**
     * =====================================================
     * APPLY FILTERS
     * =====================================================
     */

    protected function applyFilters(
        Builder $query,
        array $filters
    ): void {

        // BRANDS
        if (!empty($filters['brandIds'])) {

            $query->whereIn(
                'brand_id',
                $filters['brandIds']
            );
        }

        // COLORS
        if (!empty($filters['colorIds'])) {

            $query->whereHas(
                'variants',
                function ($q) use ($filters) {

                    $q->whereIn(
                        'color_id',
                        $filters['colorIds']
                    )

                        ->where('is_active', true)

                        ->where('stock', '>', 0);
                }
            );
        }

        // SIZES
        if (!empty($filters['sizeIds'])) {

            $query->whereHas(
                'variants',
                function ($q) use ($filters) {

                    $q->whereIn(
                        'size_id',
                        $filters['sizeIds']
                    )

                        ->where('is_active', true)

                        ->where('stock', '>', 0);
                }
            );
        }
    }

    /**
     * =====================================================
     * SORTING
     * =====================================================
     */

    protected function applySorting(
        Builder $query,
        int $order
    ): void {

        switch ($order) {

            // NEWEST
            case 1:

                $query->latest();

                break;

            // OLDEST
            case 2:

                $query->oldest();

                break;

            // PRICE LOW TO HIGH
            case 3:

                $query->orderBy(
                    'variants_min_sale_price',
                    'ASC'
                );

                break;

            // PRICE HIGH TO LOW
            case 4:

                $query->orderBy(
                    'variants_min_sale_price',
                    'DESC'
                );

                break;

            // DEFAULT
            default:

                $query->latest();

                break;
        }
    }

    /**
     * =====================================================
     * SANITIZE IDS
     * =====================================================
     */

    protected function sanitizeIds(
        ?string $value
    ): array {

        if (!$value) {

            return [];
        }

        return array_unique(
            array_filter(
                array_map(
                    'intval',
                    explode(',', $value)
                )
            )
        );
    }
}
