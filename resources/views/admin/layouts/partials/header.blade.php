<div id="header-fix" class="header fixed-top">
    <div class="site-width">
        <nav class="navbar navbar-expand-lg  p-0">
            <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">
                <a href="{{ route('admin.dashboard') }}" class="horizontal-logo text-left">
                    @php
                        if ($logoIcon && $logoIcon->logo_black) {
                            $logoBlackPath = getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black);
                            $size = '100px';
                        } else {
                            $logoBlackPath = getImage(imagePath()['image']['default']);
                            $size = '50px';
                        }
                    @endphp
                    <img src="{{ $logoBlackPath }}" alt="" width="{{ $size }}">
                </a>
            </div>
            <div class="navbar-header h4 mb-0 text-center h-100 collapse-menu-bar">
                <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
            </div>

            <form class="float-left d-none d-lg-block search-form">
                <div class="form-group mb-0 position-relative">
                    <input type="text" class="form-control border-0 rounded bg-search pl-5"
                        placeholder="{{ __('admin.header.search_anything') }}">
                    <div class="btn-search position-absolute top-0">
                        <a href="#"><i class="h6 icon-magnifier"></i></a>
                    </div>
                    <a href="#" class="position-absolute close-button mobilesearch d-lg-none"
                        data-toggle="dropdown" aria-expanded="false"><i class="icon-close h5"></i>
                    </a>

                </div>
            </form>
            <div class="navbar-right ml-auto h-100">
                <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
                    <li class="d-inline-block align-self-center d-block d-lg-none">
                        <a href="#" class="nav-link mobilesearch" data-toggle="dropdown" aria-expanded="false"><i
                                class="icon-magnifier h4"></i>
                        </a>
                    </li>

                    <li class="dropdown align-self-center">
                        <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="true"><i
                                class="mdi mdi-google-translate"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right border py-0">
                            <li>
                                <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0"
                                    href="{{ route('admin.language.switch', ['lang' => 'vi']) }}">
                                    <div class="media">
                                        <div class="flag-wrapper">
                                            <div class="img-thumbnail flag flag-icon-background flag-icon-vn"
                                                title="vn" id="vn"></div>
                                        </div>
                                        <div class="media-body">
                                            <p class="mb-0">{{ __('admin.header.language.viet_nam') }}</p>
                                            <span>{{ __('admin.header.language.vietnamese') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0"
                                    href="{{ route('admin.language.switch', ['lang' => 'en']) }}">
                                    <div class="media">
                                        <div class="flag-wrapper">
                                            <div class="img-thumbnail flag flag-icon-background flag-icon-us"
                                                title="us" id="us"></div>
                                        </div>
                                        <div class="media-body">
                                            <p class="mb-0">{{ __('admin.header.language.america') }}</p>
                                            <span>{{ __('admin.header.language.english') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown align-self-center d-inline-block">
                        <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false"><i
                                class="icon-bell h4"></i>
                            <span class="badge badge-default"> <span class="ring">
                                </span><span class="ring-point">
                                </span></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right border py-0">
                            <li>
                                <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0"
                                    href="#">
                                    <div class="media">
                                        <img src="{{ asset('backend/assets/images/author.jpg') }}" alt=""
                                            class="d-flex mr-3 img-fluid rounded-circle w-50">
                                        <div class="media-body">
                                            <p class="mb-0 text-success">john send a message</p>
                                            12 min ago
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0"
                                    href="#">
                                    <div class="media">
                                        <img src="{{ asset('backend/assets/images/author2.jpg') }}" alt=""
                                            class="d-flex mr-3 img-fluid rounded-circle">
                                        <div class="media-body">
                                            <p class="mb-0 text-danger">Peter send a message</p>
                                            15 min ago
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0"
                                    href="#">
                                    <div class="media">
                                        <img src="{{ asset('backend/assets/images/author3.jpg') }}" alt=""
                                            class="d-flex mr-3 img-fluid rounded-circle">
                                        <div class="media-body">
                                            <p class="mb-0 text-warning">Bill send a message</p>
                                            5 min ago
                                        </div>
                                    </div>
                                </a>
                            </li>

                            <li><a class="dropdown-item text-center py-2" href="#"> Read All Message <i
                                        class="icon-arrow-right pl-2 small"></i></a></li>
                        </ul>
                    </li>

                    <li class="dropdown user-profile align-self-center d-inline-block">
                        <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false">
                            <div class="media">
                                <img src="{{ asset('backend/assets/images/author.jpg') }}" alt=""
                                    class="d-flex rounded-circle" style="width: 35px;">
                            </div>
                        </a>

                        <div class="dropdown-menu border dropdown-menu-right p-0">
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span
                                    class="icon-pencil mr-2 h6 mb-0"></span>{{ __('admin.header.edit_profile') }}</a>
                            {{-- <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                            <div class="dropdown-divider"></div>
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-support mr-2 h6  mb-0"></span> Help Center</a>
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-globe mr-2 h6 mb-0"></span> Forum</a>
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-settings mr-2 h6 mb-0"></span> Account Settings</a> --}}
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.logout') }}"
                                class="dropdown-item px-2 text-danger align-self-center d-flex">
                                <span class="icon-logout mr-2 h6  mb-0"></span>{{ __('admin.header.sign_out') }}</a>
                        </div>

                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
