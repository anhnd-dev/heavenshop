<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Frontend;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerHeaderData();
        $this->registerFooterData();
        $this->registerSocialIcons();
        $this->registerCookie();
    }

    /*
    |--------------------------------------------------------------------------
    | Shared: SEO + Logo (Admin + Frontend)
    |--------------------------------------------------------------------------
    */
    private function registerHeaderData(): void
    {
        View::composer(
            [
                'frontend.layouts.partials.header',
                'admin.layouts.partials.header',
                'frontend.layouts.partials.seo',
                'admin.layouts.partials.seo',
                'admin.pages.order.invoice',
                'admin.pages.order.packing-slip'
            ],
            function ($view) {

                $frontend = $this->getFrontendByKeys([
                    Frontend::SEO,
                    Frontend::LOGO_ICON,
                ]);

                $menuCategories = Category::query()
                    ->whereNull('parent_id')
                    ->where('type', 'product')
                    ->where('is_active', true)
                    ->with('childrenRecursive')
                    ->orderBy('name')
                    ->get();

                $view->with([
                    'seo' => $frontend[Frontend::SEO] ?? null,
                    'logoIcon' => $frontend[Frontend::LOGO_ICON] ?? null,
                    'menuCategories' => $menuCategories,
                ]);
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Footer Data
    |--------------------------------------------------------------------------
    */
    private function registerFooterData(): void
    {
        View::composer('frontend.layouts.partials.footer', function ($view) {

            $data = $this->getFrontendByKeys([
                Frontend::LOGO_ICON,
                Frontend::CONTACT,
            ]);

            $socialIcons = $this->getSocialIcons();
            $categories = $this->getProductCategories();

            $view->with([
                'logoIcon' => $data[Frontend::LOGO_ICON] ?? null,
                'favicon' => $data[Frontend::LOGO_ICON] ?? null,
                'contact' => $data[Frontend::CONTACT] ?? null,
                'socialIcons' => $socialIcons,
                'categories' => $categories,
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Social Icons (Reusable)
    |--------------------------------------------------------------------------
    */
    private function registerSocialIcons(): void
    {
        View::composer('frontend.layouts.partials.social', function ($view) {
            $view->with([
                'socialIcons' => $this->getSocialIcons(),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Cookie Data
    |--------------------------------------------------------------------------
    */
    private function registerCookie(): void
    {
        View::composer('frontend.layouts.partials.cookie', function ($view) {
            $view->with([
                'cookie' => $this->getCookie(),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function getFrontendByKeys(array $keys): array
    {
        return Frontend::query()
            ->whereIn('data_key', $keys)
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->data_key => $item->data_value
            ])
            ->toArray();
    }

    private function getSocialIcons()
    {
        return Frontend::query()
            ->where('data_key', 'social_icon.element')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();
    }

    private function getCookie(): array
    {
        return Frontend::query()
            ->where('data_key', Frontend::COOKIE)
            ->where('is_active', true)
            ->value('data_value') ?? [];
    }

    private function getProductCategories()
    {
        return Category::query()
            ->where('type', 'product')
            ->where('is_active', true)
            ->take(5)
            ->get();
    }
}
