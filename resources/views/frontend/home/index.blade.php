@extends('frontend.layouts.app')

@section('content')
    @include('frontend.home.sections.sliders')

    @include('frontend.home.sections.hero')

    @include('frontend.home.sections.categories')

    @include('frontend.home.sections.best-sellers')

    @include('frontend.home.sections.promotion')

    @include('frontend.home.sections.collections')

    @include('frontend.home.sections.brands')

    @include('frontend.home.sections.featured-products')

    @include('frontend.home.sections.ads')

    @include('frontend.home.sections.magazine')
@endsection
