<div class="account-modal-overlay" id="passwordModal">

    <div class="account-modal password-modal">

        <button type="button" class="modal-close" data-modal-close>
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2 class="modal-title">
            Đổi mật khẩu
        </h2>

        <form id="passwordForm" method="POST">

            @csrf

            {{-- MẬT KHẨU HIỆN TẠI --}}
            <div class="floating-group">

                <label>Mật khẩu hiện tại</label>

                <div class="input-box password-box">

                    <i class="fa-solid fa-lock"></i>

                    <input type="password" name="current_password" required>

                    <button type="button" class="toggle-password">

                        <i class="fa-regular fa-eye"></i>

                    </button>

                </div>

                @error('current_password')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>

            {{-- MẬT KHẨU MỚI --}}
            <div class="floating-group">

                <label>Mật khẩu mới</label>

                <div class="input-box password-box">

                    <i class="fa-solid fa-lock"></i>

                    <input type="password" name="password" required>

                    <button type="button" class="toggle-password">

                        <i class="fa-regular fa-eye"></i>

                    </button>

                </div>

                @error('password')
                    <small class="input-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>

            {{-- XÁC NHẬN MẬT KHẨU --}}
            <div class="floating-group">

                <label>Xác nhận mật khẩu mới</label>

                <div class="input-box password-box">

                    <i class="fa-solid fa-lock"></i>

                    <input type="password" name="password_confirmation" required>

                    <button type="button" class="toggle-password">

                        <i class="fa-regular fa-eye"></i>

                    </button>

                </div>

            </div>

            <button type="submit" class="modal-submit-btn">

                CẬP NHẬT MẬT KHẨU

            </button>

        </form>

    </div>

</div>
