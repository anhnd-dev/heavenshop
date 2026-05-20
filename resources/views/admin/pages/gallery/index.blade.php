@if ($galleries->count())

    <div class="table-responsive">

        <table class="table table-striped table-bordered table-hover">

            <thead>

                <tr>

                    <th width="40">

                        <div class="form-check custom-checkbox">

                            <input type="checkbox" class="form-check-input" id="checkAllGallery">

                        </div>

                    </th>

                    <th>
                        {{ __('admin.product.gallery_path') }}
                    </th>

                    <th width="140">
                        {{ __('admin.product.product_image') }}
                    </th>

                    <th>
                        {{ __('admin.product.product_name') }}
                    </th>

                    <th width="120">
                        {{ __('admin.common.action') }}
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach ($galleries as $gallery)
                    <tr>

                        <td>

                            <div class="form-check custom-checkbox">

                                <input type="checkbox" class="form-check-input checkbox_gallery_ids"
                                    value="{{ $gallery->id }}">

                            </div>

                        </td>

                        <td>

                            <img src="{{ asset('uploads/gallery/' . $gallery->image) }}" class="img-thumbnail"
                                width="100">

                        </td>

                        <td>

                            {{ $gallery->product?->name }}

                        </td>

                        <td>

                            @if ($includeTrashed)
                                <button type="button" id="{{ $gallery->id }}"
                                    class="restoreGallery btn btn-success shadow btn-xs sharp mr-1 btn-sm">

                                    <i class="fas fa-trash-restore"></i>

                                </button>

                                <button type="button" id="{{ $gallery->id }}"
                                    class="forceGallery btn btn-danger shadow btn-xs sharp btn-sm">

                                    <i class="fas fa-trash-alt"></i>

                                </button>
                            @else
                                <button type="button" id="{{ $gallery->id }}"
                                    class="deleteGallery btn btn-danger shadow btn-xs sharp btn-sm">

                                    <i class="fa fa-trash"></i>

                                </button>
                            @endif

                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
@else
    <div class="text-center py-5 text-muted">

        Không có dữ liệu thư viện ảnh

    </div>

@endif
