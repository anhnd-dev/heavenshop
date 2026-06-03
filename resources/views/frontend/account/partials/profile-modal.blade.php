<div class="account-modal-overlay" id="profileModal">

    <div class="account-modal profile-modal">

        <button type="button" class="modal-close" data-modal-close>
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2 class="modal-title">
            Chỉnh sửa thông tin tài khoản
        </h2>

        <form id="profileForm" method="POST" enctype="multipart/form-data">

            @csrf

            {{-- Name --}}
            <div class="floating-group">

                <label>Họ và tên</label>

                <div class="input-box">

                    <i class="fa-regular fa-user"></i>

                    <input type="text" name="name" value="{{ $customer->name }}" required>

                </div>

                <small class="input-error" data-error="name"></small>

            </div>

            {{-- Phone --}}
            <div class="floating-group">
                <label>Số điện thoại</label>

                <div class="input-box">
                    <i class="fa-solid fa-phone"></i>

                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                </div>
            </div>

            {{-- Email --}}
            <div class="floating-group">
                <label>Email</label>

                <div class="input-box">
                    <i class="fa-regular fa-envelope"></i>

                    <input type="email" name="email" value="{{ old('email', $customer->email) }}">
                </div>
            </div>

            {{-- Gender --}}
            <div class="gender-group">
                <i class="fa-solid fa-venus-mars"></i>

                <label class="radio-item">
                    <input type="radio" name="gender" value="male"
                        {{ old('gender', $customer->gender) == 'male' ? 'checked' : '' }}>
                    <span>Nam</span>
                </label>

                <label class="radio-item">
                    <input type="radio" name="gender" value="female"
                        {{ old('gender', $customer->gender) == 'female' ? 'checked' : '' }}>
                    <span>Nữ</span>
                </label>

            </div>

            <button type="submit" class="modal-submit-btn">
                CẬP NHẬT THÔNG TIN
            </button>

        </form>

    </div>

</div>
