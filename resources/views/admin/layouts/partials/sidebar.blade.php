<div class="sidebar">
    <div class="site-width">
        <!-- START: Menu-->
        <ul id="side-menu" class="sidebar-menu">
            <li class="dropdown {{ set_active(['admin.dashboard']) }}">
                <a href="#"><i class="icon-home mr-1"></i>{{ __('admin.sidebar.dashboard') }}</a>
                <ul>
                    <li class="dropdown"><a href="#"><i class="mdi mdi-view-dashboard"
                                style="font-size: 14px;"></i>Dashboard</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('admin.dashboard') }}"><i class="icon-energy"></i> Light</a></li>
                            <li><a href="layout-vertical-semidark.html"><i class="icon-disc"></i> Semi Dark</a>
                            </li>
                            <li><a href="layout-vertical-dark.html"><i class="icon-frame"></i> Dark</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown"><a href="#"><i
                        class="icon-organization mr-1"></i>{{ __('admin.common.manage') }}</a>
                <ul>
                    <li class="{{ set_active(['admin.category.index']) }}"><a
                            href="{{ route('admin.category.index') }}"><i
                                class="icon-menu"></i>{{ __('admin.sidebar.category') }}</a></li>
                    <li class="{{ set_active(['admin.brand.index']) }}"><a href="{{ route('admin.brand.index') }}"><i
                                class="icon-social-foursqare"></i>{{ __('admin.sidebar.brand') }}</a></li>
                    <li class="{{ set_active(['admin.product.index']) }}"><a
                            href="{{ route('admin.product.index') }}"><i
                                class="icofont-brand-lionix"></i>{{ __('admin.sidebar.product') }}</a></li>
                    <li class="{{ set_active(['admin.color.index']) }}"><a href="{{ route('admin.color.index') }}"><i
                                class="icofont-color-bucket"></i>{{ __('admin.sidebar.color') }}</a></li>
                    <li class="{{ set_active(['admin.size.index']) }}"><a href="{{ route('admin.size.index') }}"><i
                                class="icofont-circle-ruler"></i>{{ __('admin.sidebar.size') }}</a></li>
                    <li class="{{ set_active(['admin.coupon.index']) }}"><a
                            href="{{ route('admin.coupon.index') }}"><i
                                class="icofont-sale-discount"></i>{{ __('admin.sidebar.coupon') }}</a></li>
                    <li class="{{ set_active(['admin.blog.index']) }}"><a href="{{ route('admin.blog.index') }}"><i
                                class="mdi mdi-book-open-page-variant"
                                style="font-size: 14px"></i>{{ __('admin.sidebar.blog') }}</a></li>
                    <li class="{{ set_active(['admin.subscriber.index']) }}"><a
                            href="{{ route('admin.subscriber.index') }}"><i
                                class="icon-like"></i>{{ __('admin.sidebar.subscriber') }}</a></li>
                </ul>
            </li>
            <li class="dropdown"><a href="#"><i
                        class="icon-settings mr-1"></i>{{ __('admin.sidebar.setting') }}</a>
                <ul>
                    <li><a href="{{ route('admin.setting.general') }}"><i
                                class="icon-support"></i>{{ __('admin.sidebar.general_setting') }}</a>
                    </li>
                    <li><a href="{{ route('admin.setting.logo.icon') }}"><i class="mdi mdi-puzzle"
                                style="font-size: 14px"></i>{{ __('admin.common.logo_and_favicon') }}</a></li>
                    <li><a href="{{ route('admin.frontend.seo') }}"><i
                                class="icon-globe"></i>{{ __('admin.sidebar.seo_manager') }}</a></li>
                    {{-- <li class="dropdown">
                        <a href="#"><i class="icon-envelope"></i>Email Manager</a>
                        <ul class="sub-menu">
                            <li><a href="chart-morris.html"><i class="icon-energy"></i> Morris Chart</a></li>
                            <li><a href="chart-chartist.html"><i class="icon-disc"></i> Chartist js</a></li>
                            <li><a href="chart-echart.html"><i class="icon-frame"></i> eCharts</a></li>
                        </ul>
                    </li> --}}
                </ul>
            </li>
            <li class="dropdown"><a href="#"><i
                        class="icon-layers mr-1"></i>{{ __('admin.sidebar.frontend_manager') }}</a>
                <ul>
                    <li class="dropdown">
                        <a href="#"><i class="mdi mdi-language-html5"
                                style="font-size: 14px;"></i>{{ __('admin.sidebar.manage_section') }}</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('admin.slider.index') }}"><i
                                        class="icon-energy"></i>{{ __('admin.sidebar.slider_section') }}</a></li>
                            <li><a href="{{ route('admin.frontend.contact') }}"><i
                                        class="icon-energy"></i>{{ __('admin.sidebar.contact_section') }}</a></li>
                            <li><a href="{{ route('admin.frontend.policy.index') }}"><i
                                        class="icon-energy"></i>{{ __('admin.sidebar.policy_section') }}</a></li>
                            <li><a href="{{ route('admin.frontend.social_icon.index') }}"><i
                                        class="icon-energy"></i>{{ __('admin.sidebar.social_icon') }}</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="dropdown"><a href="#"><i
                        class="icon-size-actual mr-1"></i>{{ __('admin.sidebar.extend') }}</a>
                <ul>
                    <li><a href="{{ route('admin.setting.cookie') }}"><i class="mdi mdi-cookie"
                                style="font-size: 14px;"></i>{{ __('admin.common.gdpr_cookie') }}</a></li>
                    <li><a href="{{ route('admin.system.info') }}"><i class="mdi mdi-server"
                                style="font-size: 14px;"></i>{{ __('admin.sidebar.system_info') }}</a></li>
                    <li><a href="{{ route('admin.setting.optimize') }}"><i class="mdi mdi-broom"
                                style="font-size: 14px;"></i>{{ __('admin.sidebar.clear_cache') }}</a></li>
                </ul>
            </li>
        </ul>
        <!-- END: Menu-->
        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0 ml-auto">
            <li class="breadcrumb-item"><a href="#">Application</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
