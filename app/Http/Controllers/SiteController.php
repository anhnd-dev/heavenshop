<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Frontend;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SiteController extends Controller
{
    public function home() // :GET
    {
        $sliders = Frontend::where([
            ['data_key', 'slider.element'],
            ['is_active', 1],
        ])->take(3)->get();

        $categories = Category::query()
            ->where([
                'type' => 'product',
                'is_active' => 1
            ])
            ->whereNotNull('parent_id')
            ->latest()
            ->get();

        // dd($categories);


        $brands = Brand::whereNull('deleted_at')->take(5)->get();

        $bestSellers = Product::where('sold_count', '>=', 100)->orderBy('sold_count', 'desc')->limit(10)->get();

        $featuredProducts = Product::where('is_featured', true)->limit(5)->get();

        $blogs = Blog::whereNull('deleted_at')->inRandomOrder()->take(5)->get();

        $coupon = Coupon::whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->inRandomOrder()
            ->first(['start_date', 'end_date', 'code']);

        return view('pages.home.index', compact('sliders', 'categories', 'brands', 'bestSellers', 'blogs', 'coupon'));
    }

    public function blog() // :GET
    {
        $blogs = Blog::latest()->paginate(12);
        return view('pages.blog', compact('blogs'));
    }

    public function blogDetails($slug) // :GET
    {
        $blog = Blog::where('slug', $slug)->first();

        $currentBlogTags = explode(',', $blog->tags);

        // Lấy các bài viết có chứa tất cả các tags giống với bài viết hiện tại và loại bỏ bài viết hiện tại
        $relatedBlogs = Blog::where(function ($query) use ($currentBlogTags) {
            foreach ($currentBlogTags as $tag) {
                $query->where('tags', 'LIKE', '%' . $tag . '%');
            }
        })
            ->whereNotIn('id', [$blog->id]) // Loại bỏ bài viết hiện tại
            ->take(3) // Giới hạn số lượng bài viết liên quan
            ->get();

        return view('pages.blog_detail', compact('blog', 'relatedBlogs'));
    }

    public function contact() // :GET
    {
        $contact = Frontend::where('data_key', 'contact_us.content')->first();
        return view('pages.contact', compact('contact'));
    }

    public function about() // :GET
    {
        // $contact = Frontend::where('data_key', 'contact_us.content')->first();
        return view('pages.about');
    }

    public function policy($slug)
    {
        $policy = Frontend::where('data_key', 'policy.element')
            ->where('status', 1)
            ->whereJsonContains('data_value->slug', $slug)
            ->first();

        return view('pages.policy.' . strtr($slug, "-", "_"), ['policy' => $policy]);
    }

    public function faqs() // :GET
    {
        return view('pages.faqs');
    }
}
