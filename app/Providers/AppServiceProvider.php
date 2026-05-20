<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Frontend;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sử dụng một truy vấn để lấy dữ liệu cho cả seo và logo_icon
        $seoAndLogo = Frontend::whereIn('data_key', ['seo.data', 'logo_icon.data'])->get();

        View::composer(['partials.seo', 'partials.header', 'partials.header_cart', 'admin.layouts.partials.header'], function ($view) use ($seoAndLogo) {
            $data = $seoAndLogo->where('data_key', 'seo.data')->first();
            $seo = $data ? json_decode($data->data_value) : null;

            $data = $seoAndLogo->where('data_key', 'logo_icon.data')->first();
            $logoIcon = $data ? json_decode($data->data_value) : null;

            $view->with(compact('seo', 'logoIcon'));
        });

        // Lấy dữ liệu cho footer
        $footerData = Frontend::whereIn('data_key', ['logo_icon.data', 'contact_us.content'])->get();

        $socialIcons = Frontend::where('data_key', 'social_icon.element')
            ->where('is_active', 1)
            ->take(4)
            ->get();

        $categories = Category::where('type', 'product')->take(5)->get();

        View::composer('partials.footer', function ($view) use ($footerData, $socialIcons, $categories) {
            $logoIcon = $footerData->where('data_key', 'logo_icon.data')->first();
            $logoIcon = $logoIcon ? json_decode($logoIcon->data_value) : null;

            $contact = $footerData->where('data_key', 'contact_us.content')->first();
            $contact = $contact ? json_decode($contact->data_value) : null;

            $view->with(compact('logoIcon', 'contact', 'socialIcons', 'categories'));
        });

        View::composer('partials.social', function ($view) use ($socialIcons) {
            $view->with(compact('socialIcons'));
        });

        View::composer(
            [
                'partials.header',
            ],
            function ($view) {

                $menuCategories = Category::query()

                    ->whereNull('parent_id')

                    ->where('type', 'product')

                    ->where('is_active', true)

                    ->with('childrenRecursive')

                    ->orderBy('name')

                    ->get();

                $view->with(
                    'menuCategories',
                    $menuCategories
                );
            }
        );
    }
}
