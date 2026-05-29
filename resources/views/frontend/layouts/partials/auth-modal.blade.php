{{-- LOGIN MODAL --}}
<div class="checkout-auth-modal" id="checkoutAuthModal" style="display:none;">

    <div class="checkout-auth-overlay"></div>

    <div class="checkout-auth-box">

        <button type="button" class="checkout-auth-close">
            ×
        </button>

        <div class="checkout-auth-header">

            <img src="{{ asset('frontend/images/logo.png') }}" alt="Logo">

            <h2 id="auth-modal-title">
                Đăng nhập để tiếp tục
            </h2>

            <p id="auth-modal-description">
                Vui lòng đăng nhập tài khoản khách hàng
            </p>

        </div>

        {{-- LOGIN --}}
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

        {{-- REGISTER --}}
        <form id="checkout-register-form" style="display:none;">

            @csrf

            <div class="checkout-auth-group">

                <input type="text" name="name" class="checkout-auth-input" placeholder="Họ và tên">

            </div>

            <div class="checkout-auth-row">

                <div class="checkout-auth-group">

                    <input type="text" name="phone" class="checkout-auth-input" placeholder="Số điện thoại">

                </div>

                <div class="checkout-auth-group">

                    <input type="email" name="email" class="checkout-auth-input" placeholder="Email">

                </div>

            </div>

            <div class="checkout-auth-group">

                <input type="password" name="password" class="checkout-auth-input" placeholder="Mật khẩu">

            </div>

            <div class="checkout-auth-group">

                <input type="password" name="password_confirmation" class="checkout-auth-input"
                    placeholder="Nhập lại mật khẩu">

            </div>

            <button type="submit" class="checkout-auth-submit">

                Đăng ký tài khoản

            </button>

            <div class="checkout-auth-footer">

                <a href="#" id="show-login-form">

                    Đã có tài khoản? Đăng nhập

                </a>

            </div>

        </form>

    </div>

</div>
