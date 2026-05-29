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
            ->latest()
            ->take(3)
            ->get();

        $brands = Brand::whereNull('deleted_at')->take(5)->get();

        $bestSellers = Product::where('sold_count', '>=', 100)->orderBy('sold_count', 'desc')->limit(10)->get();

        $featuredProducts = Product::where('is_featured', true)->limit(5)->get();

        $blogs = Blog::whereNull('deleted_at')->inRandomOrder()->take(5)->get();

        $coupon = Coupon::whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->inRandomOrder()
            ->first(['start_date', 'end_date', 'code']);

        return view('frontend.home.index', compact('sliders', 'categories', 'brands', 'bestSellers', 'blogs', 'coupon'));
    }
}
