<div id="variant-template" class="d-none">

    <div class="variant-item border rounded p-3 mb-3">

        {{-- hidden fields --}}
        <input type="hidden" name="__NAME__[__INDEX__][id]" class="variant-id">
        <input type="hidden" name="__NAME__[__INDEX__][existing_image]" class="variant-existing-image">

        {{-- header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <h6 class="mb-0 variant-title">
                __TITLE__
            </h6>

            <button type="button" class="btn btn-danger btn-sm remove-variant">
                <i class="fas fa-trash"></i>
            </button>

        </div>

        {{-- ROW 1 --}}
        <div class="row">

            {{-- COLOR --}}
            <div class="col-md-2">
                <div class="form-group">
                    <label>Màu sắc</label>

                    <select name="__NAME__[__INDEX__][color_id]" class="form-control variant-color">
                        <option value="">-- Chọn màu --</option>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}">
                                {{ $color->name }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>

            {{-- SIZE --}}
            <div class="col-md-2">
                <div class="form-group">
                    <label>Kích thước</label>

                    <select name="__NAME__[__INDEX__][size_id]" class="form-control variant-size">
                        <option value="">-- Chọn size --</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}">
                                {{ $size->name }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>

            {{-- PRICE --}}
            <div class="col-md-2">
                <div class="form-group">
                    <label>Giá</label>

                    <input type="number" min="0" name="__NAME__[__INDEX__][price]"
                        class="form-control variant-price">
                </div>
            </div>

            {{-- SALE PRICE --}}
            <div class="col-md-2">
                <div class="form-group">
                    <label>Giá sale</label>

                    <input type="number" min="0" name="__NAME__[__INDEX__][sale_price]"
                        class="form-control variant-sale-price">
                </div>
            </div>

            {{-- STOCK --}}
            <div class="col-md-2">
                <div class="form-group">
                    <label>Kho</label>

                    <input type="number" min="0" name="__NAME__[__INDEX__][stock]"
                        class="form-control variant-stock">
                </div>
            </div>

            {{-- SKU --}}
            <div class="col-md-2">
                <div class="form-group">
                    <label>SKU</label>

                    <input type="text" name="__NAME__[__INDEX__][sku]" class="form-control variant-sku" readonly>
                </div>
            </div>

        </div>

        {{-- ROW 2 --}}
        <div class="row mt-2">

            {{-- IMAGE --}}
            <div class="col-md-4">
                <div class="form-group">
                    <label>Ảnh biến thể</label>

                    <input type="file" name="__NAME__[__INDEX__][image]" class="form-control variant-image">

                </div>
            </div>

            {{-- STATUS --}}
            <div class="col-md-3">
                <div class="form-group">
                    <label>Trạng thái</label>

                    <select name="__NAME__[__INDEX__][is_active]" class="form-control variant-status">

                        <option value="1" selected>Hiển thị</option>
                        <option value="0">Ẩn</option>

                    </select>

                </div>
            </div>

            {{-- PREVIEW --}}
            <div class="col-md-5">
                <div class="form-group">

                    <label>Preview</label>

                    <div class="text-center">
                        <img class="variant-preview img-thumbnail d-none" style="max-width: 120px;">
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>
