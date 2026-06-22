<div class="checkout-auth-modal" id="checkoutAuthModal" style="display:none;">

    <div class="checkout-auth-overlay"></div>

    <div class="checkout-auth-box">

        <button type="button" class="checkout-auth-close">
            ×
        </button>

        <div class="checkout-auth-header">

            @if ($logoIcon['favicon'])
                <img src="{{ getImage(imagePath()['favicon']['path'] . '/' . ($logoIcon['favicon'] ?? '')) }}">
            @endif

            <h2 id="auth-modal-title">
                Đăng nhập để tiếp tục đặt hàng
            </h2>

            <p id="auth-modal-description">
                Vui lòng đăng nhập tài khoản khách hàng trước khi thanh toán
            </p>

        </div>

        {{-- =========================
            LOGIN FORM
        ========================== --}}
        <form id="checkout-login-form">

            @csrf

            <div class="checkout-auth-group">

                <input type="text" name="login" class="checkout-auth-input" placeholder="Email hoặc số điện thoại">

            </div>

            <div class="checkout-auth-group">

                <input type="password" name="password" class="checkout-auth-input" placeholder="Mật khẩu">

            </div>

            <button type="submit" class="checkout-auth-submit">
                Đăng nhập
            </button>

            <div class="checkout-auth-footer">

                <a href="#" id="show-register-form">
                    Đăng ký tài khoản
                </a>

                <a href="#">
                    Quên mật khẩu
                </a>

            </div>

        </form>

        {{-- =========================
            REGISTER FORM
        ========================== --}}
        <form id="checkout-register-form" style="display:none;">

            @csrf

            {{-- FULL NAME --}}
            <div class="checkout-auth-group">

                <input type="text" name="name" class="checkout-auth-input" placeholder="Họ và tên">

            </div>

            {{-- ROW --}}
            <div class="checkout-auth-row">

                {{-- PHONE --}}
                <div class="checkout-auth-group">

                    <input type="text" name="phone" class="checkout-auth-input" placeholder="Số điện thoại">

                </div>

                {{-- EMAIL --}}
                <div class="checkout-auth-group">

                    <input type="email" name="email" class="checkout-auth-input" placeholder="Email">

                </div>

            </div>

            {{-- PASSWORD --}}
            <div class="checkout-auth-group">

                <input type="password" name="password" class="checkout-auth-input" placeholder="Mật khẩu">

            </div>

            {{-- CONFIRM --}}
            <div class="checkout-auth-group">

                <input type="password" name="password_confirmation" class="checkout-auth-input"
                    placeholder="Nhập lại mật khẩu">

            </div>

            {{-- BUTTON --}}
            <button type="submit" class="checkout-auth-submit">

                Đăng ký tài khoản

            </button>

            {{-- FOOTER --}}
            <div class="checkout-auth-footer">

                <a href="#" id="show-login-form">

                    Đã có tài khoản? Đăng nhập

                </a>

            </div>

        </form>

    </div>

</div>
