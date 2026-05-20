@if ($seo || $logoIcon)
    <meta name="title" content="Lifestyle - Trang bán hàng thời trang trực tuyến">
    <meta name="description" content="{{ $seo->description ?? '' }}" />
    <meta name="keywords" content="{{ isset($seo) && isset($seo->keywords) ? implode(',', $seo->keywords) : '' }}">

    <link rel="shortcut icon" href="{{ getImage(imagePath()['favicon']['path'] . '/' . $logoIcon->favicon) }}"
        type="image/png">

    <link rel="apple-touch-icon" href="{{ getImage(imagePath()['favicon']['path'] . '/' . $logoIcon->favicon_57x) }}" />
    <link rel="apple-touch-icon" sizes="72x72"
        href="{{ getImage(imagePath()['favicon']['path'] . '/' . $logoIcon->favicon_72x) }}" />
    <link rel="apple-touch-icon" sizes="114x114"
        href="{{ getImage(imagePath()['favicon']['path'] . '/' . $logoIcon->favicon_114x) }}" />
    {{-- Google / Search Engine Tags --}}
    <meta itemprop="name" content="Lifestyle - Trang bán hàng thời trang trực tuyến">
    <meta itemprop="description" content="{{ $seo->description ?? '' }}">
    <meta itemprop="image"
        content="{{ isset($seo) && isset($seo->image) ? getImage(imagePath()['seo']['path'] . '/' . $seo->image) : '' }}">
    {{-- Facebook Meta Tags --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seo->social_title ?? '' }}">
    <meta property="og:description" content="{{ $seo->social_description ?? '' }}">
    <meta property="og:image"
        content="{{ isset($seo) && isset($seo->image) ? getImage(imagePath()['seo']['path'] . '/' . $seo->image) : '' }}" />
    @php
        $seoImagePath =
            isset($seo) && isset($seo->image) ? getImage(imagePath()['seo']['path'] . '/' . $seo->image) : '';
        $seoImageExtension = $seoImagePath ? pathinfo($seoImagePath, PATHINFO_EXTENSION) : '';
    @endphp

    <meta property="og:image:type" content="{{ $seoImageExtension ? 'image/' . $seoImageExtension : '' }}" />

    @php
        $imagePath = isset($seo) && isset($seo->image) ? imagePath()['seo']['path'] . '/' . $seo->image : '';
        $imageDimensions = $imagePath ? getImageDimensions($imagePath) : ['width' => '', 'height' => ''];
    @endphp

    <meta property="og:image:width" content="{{ $imageDimensions['width'] }}" />
    <meta property="og:image:height" content="{{ $imageDimensions['height'] }}" />
    <meta property="og:url" content="{{ url()->current() }}">
@endif
