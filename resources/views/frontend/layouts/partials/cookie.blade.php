@if (!empty($cookie) && ($cookie['status'] ?? false))
    <div id="cookies-model" class="cookie-message bg-dark-gray border-radius-8px">

        <div class="cookie-description fs-14 text-white mb-20px lh-22">
            {{ $cookie['description'] ?? '' }}
        </div>

        <div class="cookie-btn">

            <a href="{{ $cookie['link'] ?? '#' }}"
                class="btn btn-transparent-white border-1 border-color-transparent-white-light btn-very-small btn-switch-text btn-rounded w-100 mb-15px"
                aria-label="btn" target="_blank">

                <span>
                    <span class="btn-double-text" data-text="Cookie policy">
                        Cookie policy
                    </span>
                </span>
            </a>

            <a href="#"
                class="btn btn-white btn-very-small btn-switch-text btn-box-shadow accept_cookies_btn btn-rounded w-100"
                data-accept-btn aria-label="text">

                <span>
                    <span class="btn-double-text" data-text="Allow cookies">
                        Allow cookies
                    </span>
                </span>
            </a>

        </div>
    </div>
@endif
