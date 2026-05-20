<?php

use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('customPagination')) {
    /**
     * Generate custom pagination HTML.
     *
     * @param LengthAwarePaginator $paginator
     * @return string
     */

    function customPagination(LengthAwarePaginator $paginator)
    {
        if ($paginator->hasPages()) {
            $html = '<div class="w-100 d-flex mt-4 justify-content-center md-mt-30px"><ul class="pagination pagination-style-01 fs-13 fw-500 mb-0">';

            // Previous Page Link
            $html .= '<li class="page-item ' . ($paginator->onFirstPage() ? 'disabled' : '') . '">';
            $html .= '<a class="page-link" href="' . ($paginator->previousPageUrl() ?? 'javascript:void(0)') . '" aria-label="Previous" style="' . ($paginator->onFirstPage() ? 'color: #6c757d' : '') . '">';
            $html .= '<i class="feather icon-feather-arrow-left fs-18 d-xs-none"></i></a></li>';

            // Pagination Links
            foreach ($paginator->appends(request()->except('page'))->getUrlRange(1, $paginator->lastPage()) as $page => $url) {
                $html .= '<li class="page-item ' . ($page == $paginator->currentPage() ? 'active' : '') . '">';
                $html .= '<a class="page-link" href="' . $url . '">' . $page . '</a></li>';
            }

            // Next Page Link
            $html .= '<li class="page-item ' . ($paginator->hasMorePages() ? '' : 'disabled') . '">';
            $html .= '<a href="' . ($paginator->nextPageUrl() ?? 'javascript:void(0)') . '" class="page-link" aria-label="Next" style="' . ($paginator->hasMorePages() ? '' : 'color: #6c757d') . '">';
            $html .= '<i class="feather icon-feather-arrow-right fs-18 d-xs-none"></i></a></li>';

            $html .= '</ul></div>';

            return $html;
        }

        return '';
    }
}

if (!function_exists('set_active')) {
    /**
     * Determines if the current route matches any given route names and returns a specified CSS class if it does.
     *
     * @param mixed $routeNames A single route name (string) or an array of route names.
     * @param string $output The CSS class to return if the route matches. Default is 'mm-active'.
     * @return string The CSS class if a match is found, otherwise an empty string.
     */
    function set_active($routeNames, $output = 'active')
    {
        // Check if $routeNames is an array
        if (is_array($routeNames)) {
            // If any route in the array matches the current route name, return $output
            if (in_array(request()->route()->getName(), $routeNames)) {
                return $output;
            }
        } else {
            // If $routeNames is a single string, check if it matches the current route name
            if (request()->route()->getName() == $routeNames) {
                return $output;
            }
        }

        // Return an empty string if no match is found
        return '';
    }
}

function shortenText($text, $maxLength) {
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    }
    return $text;
}

function imagePath()
{
    $data['image'] = [
        'default' => 'default.png',
    ];
    $data['language'] = [
        'path' => 'assets/images/lang',
        'size' => '64x64'
    ];
    $data['logoIcon'] = [
        'path' => 'uploads/logoIcon',
    ];
    $data['favicon'] = [
        'path' => 'uploads/favicon',
    ];
    $data['contact'] = [
        'path' => 'uploads/contact',
    ];
    $data['extensions'] = [
        'path' => 'assets/images/extensions',
        'size' => '36x36',
    ];
    $data['seo'] = [
        'path' => 'uploads/seo',
    ];
    $data['profile'] = [
        'user' => [
            'path' => 'assets/images/user/profile',
            'size' => '350x300'
        ],
        'admin' => [
            'path' => 'assets/laramin/images/profile',
            'size' => '400x400'
        ]
    ];
    $data['location'] = [
        'path' => 'assets/images/location',
        'size' => '740x1140',
    ];
    $data['property'] = [
        'path' => 'assets/images/property',
        'size' => '990x740',
    ];
    $data['property_type'] = [
        'path' => 'assets/images/property_type',
        'size' => '990x740',
    ];

    return $data;
}

function getImage($image, $size = null)
{
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }
    if ($size) {
        return route('placeholder.image', $size);
    }
    return asset('/default.png');
}

function getImageDimensions($imagePath)
{
    // Kiểm tra xem tệp hình ảnh có tồn tại không
    if (file_exists($imagePath)) {
        // Lấy thông tin về kích thước của hình ảnh
        $imageSize = getimagesize($imagePath);

        // Kiểm tra xem có lấy được thông tin không
        if ($imageSize !== false) {
            // Lấy chiều dài và chiều rộng từ kích thước hình ảnh
            $width = $imageSize[0];
            $height = $imageSize[1];

            // Trả về một mảng chứa chiều dài và chiều rộng
            return array('width' => $width, 'height' => $height);
        } else {
            // Trả về null nếu không lấy được thông tin
            return null;
        }
    } else {
        // Trả về null nếu tệp không tồn tại
        return null;
    }
}

