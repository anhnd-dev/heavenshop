<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Slider;
use App\Models\Product;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Frontend;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() // :GET
    {
        $sliders = Slider::query()
            ->display()
            ->where('position', 'home_top')
            ->orderBy('sort_order')
            ->get();

        $categories = Category::query()
            ->with('children')
            ->where([
                'type' => 'product',
                'is_active' => 1,
            ])
            ->whereNull('parent_id')
            ->take(3)
            ->get();

        $brands = Brand::whereNull('deleted_at')->take(5)->get();

        $free_ship = Frontend::getSetting(
            'shipping_free_threshold',
            0
        );

        $bestSellers = Product::query()
            ->withMin('variants', 'price')
            ->withMin('variants', 'sale_price')
            ->orderByDesc('sold_count')
            ->take(10)
            ->get();

        // $bestSellers = Product::query()
        //     ->select('products.*')
        //     ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
        //     ->join('order_items', 'product_variants.id', '=', 'order_items.product_variant_id')
        //     ->groupBy('products.id')
        //     ->orderByRaw('SUM(order_items.quantity) DESC')
        //     ->limit(10)
        //     ->get();

        $featuredProducts = Product::query()
            ->withMin('variants', 'price')
            ->withMin('variants', 'sale_price')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        $blogs = Blog::whereNull('deleted_at')->inRandomOrder()->take(5)->get();

        $coupon = Coupon::whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->inRandomOrder()
            ->first(['start_date', 'end_date', 'code']);

        $collections = Category::query()
            ->where('type', 'product')
            ->where('is_active', 1)
            ->whereIn('parent_id', function ($query) {
                $query->select('id')
                    ->from('categories')
                    ->whereNull('parent_id');
            })
            ->get();

        return view('frontend.home.index', compact('sliders', 'categories', 'brands', 'free_ship', 'bestSellers', 'featuredProducts', 'blogs', 'coupon', 'collections'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($query) use ($keyword) {

                $query->where('name', 'like', "%{$keyword}%")

                    ->orWhereHas('variants', function ($q) use ($keyword) {
                        $q->where('sku', 'like', "%{$keyword}%");
                    });
            })
            ->limit(10)
            ->get()
            ->map(function ($product) {

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image_url' => asset('/uploads/product/' . $product->image),
                ];
            });

        return response()->json($products);
    }
}
