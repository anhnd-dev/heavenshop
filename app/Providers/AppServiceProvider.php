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
        /*
    |--------------------------------------------------------------------------
    | Header, SEO & Logo
    |--------------------------------------------------------------------------
    */
        View::composer(
            [
                'frontend.layouts.partials.seo',
                'frontend.layouts.partials.header',
                'frontend.layouts.partials.header_cart',
                'admin.layouts.partials.seo',
                'admin.layouts.partials.header',

            ],
            function ($view) {

                $frontendData = Frontend::query()
                    ->whereIn('data_key', [
                        'seo.data',
                        'logo_icon.data',
                    ])
                    ->get()
                    ->keyBy('data_key');

                $seo = optional($frontendData->get('seo.data'), function ($item) {
                    return json_decode($item->data_value);
                });

                $logoIcon = optional($frontendData->get('logo_icon.data'), function ($item) {
                    return json_decode($item->data_value);
                });

                $view->with(compact('seo', 'logoIcon'));
            }
        );

        /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */
        View::composer('frontend.layouts.partials.footer', function ($view) {

            $frontendData = Frontend::query()
                ->whereIn('data_key', [
                    'logo_icon.data',
                    'contact_us.content',
                ])
                ->get()
                ->keyBy('data_key');

            $logoIcon = optional($frontendData->get('logo_icon.data'), function ($item) {
                return json_decode($item->data_value);
            });

            $contact = optional($frontendData->get('contact_us.content'), function ($item) {
                return json_decode($item->data_value);
            });

            $socialIcons = Frontend::query()
                ->where('data_key', 'social_icon.element')
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get();

            $categories = Category::query()
                ->where('type', 'product')
                ->where('is_active', true)
                ->take(5)
                ->get();

            $view->with(compact(
                'logoIcon',
                'contact',
                'socialIcons',
                'categories'
            ));
        });

        /*
    |--------------------------------------------------------------------------
    | Social Icons
    |--------------------------------------------------------------------------
    */
        View::composer('frontend.layouts.partials.social', function ($view) {

            $socialIcons = Frontend::query()
                ->where('data_key', 'social_icon.element')
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get();

            $view->with(compact('socialIcons'));
        });

        /*
    |--------------------------------------------------------------------------
    | Menu Categories
    |--------------------------------------------------------------------------
    */
        View::composer('frontend.layouts.partials.header', function ($view) {

            $menuCategories = Category::query()
                ->whereNull('parent_id')
                ->where('type', 'product')
                ->where('is_active', true)
                ->with('childrenRecursive')
                ->orderBy('name')
                ->get();

            $view->with(compact('menuCategories'));
        });
    }
}
