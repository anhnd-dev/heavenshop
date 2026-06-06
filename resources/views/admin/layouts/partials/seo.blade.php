@if (!empty($seo) || !empty($logoIcon))

    @php
        $seoImage = !empty($seo['image'] ?? null) ? getImage(imagePath()['seo']['path'] . '/' . $seo['image']) : null;
    @endphp

    {{-- BASIC SEO --}}
    <meta name="title" content="{{ $seo['title'] ?? 'Lifestyle - Trang bán hàng thời trang trực tuyến' }}">
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <meta name="keywords" content="{{ isset($seo['keywords']) ? implode(',', (array) $seo['keywords']) : '' }}">

    {{-- FAVICON --}}
    <link rel="shortcut icon" href="{{ getImage(imagePath()['favicon']['path'] . '/' . ($logoIcon['favicon'] ?? '')) }}"
        type="image/png">

    <link rel="apple-touch-icon"
        href="{{ getImage(imagePath()['favicon']['path'] . '/' . ($logoIcon['favicon_57x'] ?? '')) }}">

    <link rel="apple-touch-icon" sizes="72x72"
        href="{{ getImage(imagePath()['favicon']['path'] . '/' . ($logoIcon['favicon_72x'] ?? '')) }}">

    <link rel="apple-touch-icon" sizes="114x114"
        href="{{ getImage(imagePath()['favicon']['path'] . '/' . ($logoIcon['favicon_114x'] ?? '')) }}">

    {{-- ITEMPROP --}}
    <meta itemprop="name" content="{{ $seo['title'] ?? '' }}">
    <meta itemprop="description" content="{{ $seo['description'] ?? '' }}">
    <meta itemprop="image" content="{{ $seoImage }}">

    {{-- OPEN GRAPH --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seo['social_title'] ?? ($seo['title'] ?? '') }}">
    <meta property="og:description" content="{{ $seo['social_description'] ?? ($seo['description'] ?? '') }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:url" content="{{ url()->current() }}">

    @if ($seoImage)
        @php
            $extension = pathinfo($seoImage, PATHINFO_EXTENSION);
        @endphp

        <meta property="og:image:type" content="image/{{ $extension }}">
    @endif

@endif
