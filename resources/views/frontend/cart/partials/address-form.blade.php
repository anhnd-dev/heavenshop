<div class="row">

    <div class="col-md-6 mb-3">

        <label>
            Họ tên
        </label>

        <input type="text" name="full_name" class="form-control">

    </div>

    <div class="col-md-6 mb-3">

        <label>
            Số điện thoại
        </label>

        <input type="text" name="phone" class="form-control">

    </div>

    <div class="col-12 mb-3">

        <label>
            Địa chỉ
        </label>

        <input type="text" name="address" class="form-control">

    </div>

    <div class="location-wrapper">

        <div class="checkout-group">
            <label>
                Tỉnh / Thành phố
            </label>

            <select name="province_id" class="province address-province">
                <option value="">-- Chọn --</option>
            </select>
        </div>

        <div class="checkout-group">
            <label>
                Quận / Huyện
            </label>

            <select name="district_id" class="district address-district">
                <option value="">-- Chọn --</option>
            </select>
        </div>

        <div class="checkout-group">
            <label>
                Phường / Xã
            </label>

            <select name="ward_id" class="ward address-ward">
                <option value="">-- Chọn --</option>
            </select>
        </div>

    </div>

</div>
