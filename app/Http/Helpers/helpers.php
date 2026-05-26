<?php

use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('customPagination')) {

    /**
     * Render custom pagination.
     */
    function customPagination(LengthAwarePaginator $paginator): string
    {
        if (!$paginator->hasPages()) {
            return '';
        }

        $html = '
            <div class="w-100 d-flex mt-4 justify-content-center md-mt-30px">
                <ul class="pagination pagination-style-01 fs-13 fw-500 mb-0">
        ';

        /*
        |--------------------------------------------------------------------------
        | Previous Button
        |--------------------------------------------------------------------------
        */
        $html .= '
            <li class="page-item ' . ($paginator->onFirstPage() ? 'disabled' : '') . '">
                <a
                    class="page-link"
                    href="' . ($paginator->previousPageUrl() ?? 'javascript:void(0)') . '"
                    aria-label="Previous"
                >
                    <i class="feather icon-feather-arrow-left fs-18 d-xs-none"></i>
                </a>
            </li>
        ';

        /*
        |--------------------------------------------------------------------------
        | Pagination Numbers
        |--------------------------------------------------------------------------
        */
        foreach (
            $paginator->appends(request()->except('page'))
                ->getUrlRange(1, $paginator->lastPage()) as $page => $url
        ) {

            $active = $page == $paginator->currentPage() ? 'active' : '';

            $html .= '
                <li class="page-item ' . $active . '">
                    <a class="page-link" href="' . $url . '">
                        ' . $page . '
                    </a>
                </li>
            ';
        }

        /*
        |--------------------------------------------------------------------------
        | Next Button
        |--------------------------------------------------------------------------
        */
        $html .= '
            <li class="page-item ' . (!$paginator->hasMorePages() ? 'disabled' : '') . '">
                <a
                    class="page-link"
                    href="' . ($paginator->nextPageUrl() ?? 'javascript:void(0)') . '"
                    aria-label="Next"
                >
                    <i class="feather icon-feather-arrow-right fs-18 d-xs-none"></i>
                </a>
            </li>
        ';

        $html .= '
                </ul>
            </div>
        ';

        return $html;
    }
}

if (!function_exists('set_active')) {

    /**
     * Set active class for current route.
     */
    function set_active(
        string|array $routeNames,
        string $output = 'active'
    ): string {

        $currentRoute = request()->route()?->getName();

        if (is_array($routeNames)) {
            return in_array($currentRoute, $routeNames)
                ? $output
                : '';
        }

        return $currentRoute === $routeNames
            ? $output
            : '';
    }
}

if (!function_exists('shortenText')) {

    /**
     * Shorten text with ellipsis.
     */
    function shortenText(?string $text, int $maxLength = 50): string
    {
        $text = trim($text ?? '');

        return mb_strlen($text) > $maxLength
            ? mb_substr($text, 0, $maxLength) . '...'
            : $text;
    }
}

if (!function_exists('imagePath')) {

    /**
     * Get image paths configuration.
     */
    function imagePath(): array
    {
        return [

            'image' => [
                'default' => 'default.png',
            ],

            'language' => [
                'path' => 'assets/images/lang',
                'size' => '64x64',
            ],

            'logoIcon' => [
                'path' => 'uploads/logoIcon',
            ],

            'favicon' => [
                'path' => 'uploads/favicon',
            ],

            'contact' => [
                'path' => 'uploads/contact',
            ],

            'extensions' => [
                'path' => 'assets/images/extensions',
                'size' => '36x36',
            ],

            'seo' => [
                'path' => 'uploads/seo',
            ],

            'profile' => [

                'user' => [
                    'path' => 'assets/images/user/profile',
                    'size' => '350x300',
                ],

                'admin' => [
                    'path' => 'assets/laramin/images/profile',
                    'size' => '400x400',
                ],
            ],

            'location' => [
                'path' => 'assets/images/location',
                'size' => '740x1140',
            ],

            'property' => [
                'path' => 'assets/images/property',
                'size' => '990x740',
            ],

            'property_type' => [
                'path' => 'assets/images/property_type',
                'size' => '990x740',
            ],
        ];
    }
}

if (!function_exists('getImage')) {

    /**
     * Get image URL or placeholder.
     */
    function getImage(string|null $image, ?string $size = null): string
    {
        if ($image && file_exists($image) && is_file($image)) {
            return asset($image);
        }

        return $size
            ? route('placeholder.image', $size)
            : asset('default.png');
    }
}

if (!function_exists('getImageDimensions')) {

    /**
     * Get image width and height.
     */
    function getImageDimensions(string $imagePath): ?array
    {
        if (!file_exists($imagePath)) {
            return null;
        }

        $imageSize = getimagesize($imagePath);

        if (!$imageSize) {
            return null;
        }

        return [
            'width'  => $imageSize[0],
            'height' => $imageSize[1],
        ];
    }
}
