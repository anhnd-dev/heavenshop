@extends('admin.layouts.base')

@php
    $data = $logoIcon ? json_decode($logoIcon->data_value) : (object) [];
@endphp

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">{{ __('admin.common.logo_and_favicon') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.common.manage') }}</li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.setting.logo.icon') }}">{{ __('admin.common.logo_and_favicon') }}</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mb-2 mt-2">
                <div class="card bl--5-primary">
                    <div class="card-body cs">
                        {{ __('admin.logo_icon.cache_clear_instructions') }}
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <div class="card">
                    <form id="logo_icon_form" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="logo_white_old"
                            value="{{ isset($data->logo_white) ? $data->logo_white : '' }}">
                        <input type="hidden" name="logo_black_old"
                            value="{{ isset($data->logo_black) ? $data->logo_black : '' }}">
                        <input type="hidden" name="logo_white_2x_old"
                            value="{{ isset($data->logo_white_2x) ? $data->logo_white_2x : '' }}">
                        <input type="hidden" name="logo_black_2x_old"
                            value="{{ isset($data->logo_black_2x) ? $data->logo_black_2x : '' }}">
                        <input type="hidden" name="favicon_old" value="{{ isset($data->favicon) ? $data->favicon : '' }}">
                        <input type="hidden" name="favicon_57x_old"
                            value="{{ isset($data->favicon_57x) ? $data->favicon_57x : '' }}">
                        <input type="hidden" name="favicon_72x_old"
                            value="{{ isset($data->favicon_72x) ? $data->favicon_72x : '' }}">
                        <input type="hidden" name="favicon_114x_old"
                            value="{{ isset($data->favicon_114x) ? $data->favicon_114x : '' }}">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="logo_white" id="logo_white" accept=".png, .jpg, .jpeg"
                                            hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->logo_white) ? getImage(imagePath()['logoIcon']['path'] . '/' . $data->logo_white) : '' }}">
                                            @if (!isset($data->logo_white))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">
                                                    {{ __('admin.logo_icon.logo_white') }}</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-success btn-block select-image-logo mb-2">{{ __('admin.logo_icon.select_logo') }}</button>
                                        <small class="text-facebook"
                                            style="font-size: 0.8rem;">{{ __('admin.logo_icon.files_supported_formats') }}:
                                            <b>{{ __('admin.logo_icon.extension.jpeg') }}, {{ __('admin.logo_icon.extension.jpg') }},
                                                {{ __('admin.logo_icon.extension.png') }}</b>.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="logo_black" id="logo_black" accept=".png, .jpg, .jpeg"
                                            hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->logo_black) ? getImage(imagePath()['logoIcon']['path'] . '/' . $data->logo_black) : '' }}">
                                            @if (!isset($data->logo_black))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">
                                                    {{ __('admin.logo_icon.logo_black') }}</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-success btn-block select-image-logo mb-2">{{ __('admin.logo_icon.select_logo') }}</button>
                                        <small class="text-facebook"
                                            style="font-size: 0.8rem;">{{ __('admin.logo_icon.files_supported_formats') }}:
                                            <b>{{ __('admin.logo_icon.extension.jpeg') }}, {{ __('admin.logo_icon.extension.jpg') }},
                                                {{ __('admin.logo_icon.extension.png') }}</b>.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="logo_white_2x" id="logo_white_2x"
                                            accept=".png, .jpg, .jpeg" hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->logo_white_2x) ? getImage(imagePath()['logoIcon']['path'] . '/' . $data->logo_white_2x) : '' }}">
                                            @if (!isset($data->logo_white_2x))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">
                                                    {{ __('admin.logo_icon.logo_white_2x') }}</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-success btn-block select-image-logo mb-2">{{ __('admin.logo_icon.select_logo') }}</button>
                                        <small class="text-facebook"
                                            style="font-size: 0.8rem;">{{ __('admin.logo_icon.files_supported_formats') }}:
                                            <b>{{ __('admin.logo_icon.extension.jpeg') }}, {{ __('admin.logo_icon.extension.jpg') }},
                                                {{ __('admin.logo_icon.extension.png') }}</b>.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="logo_black_2x" id="logo_black_2x"
                                            accept=".png, .jpg, .jpeg" hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->logo_black_2x) ? getImage(imagePath()['logoIcon']['path'] . '/' . $data->logo_black_2x) : '' }}">
                                            @if (!isset($data->logo_black_2x))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">{{ __('admin.logo_icon.logo_black_2x') }}</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-success btn-block select-image-logo mb-2">{{ __('admin.logo_icon.select_logo') }}</button>
                                        <small class="text-facebook"
                                            style="font-size: 0.8rem;">{{ __('admin.logo_icon.files_supported_formats') }}:
                                            <b>{{ __('admin.logo_icon.extension.jpeg') }}, {{ __('admin.logo_icon.extension.jpg') }},
                                                {{ __('admin.logo_icon.extension.png') }}</b>.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="favicon" id="favicon" accept=".png, .jpg, .jpeg"
                                            hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->favicon) ? getImage(imagePath()['favicon']['path'] . '/' . $data->favicon) : '' }}">
                                            @if (!isset($data->favicon))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">{{ __('admin.logo_icon.favicon') }}</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-success btn-block select-image-favicon mb-2">{{ __('admin.logo_icon.select_favicon') }}</button>
                                        <small class="text-facebook"
                                            style="font-size: 0.8rem;">{{ __('admin.logo_icon.files_supported_formats') }}:
                                            <b>{{ __('admin.logo_icon.extension.jpeg') }}, {{ __('admin.logo_icon.extension.jpg') }},
                                                {{ __('admin.logo_icon.extension.png') }}</b>.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="favicon_57x" id="favicon_57x"
                                            accept=".png, .jpg, .jpeg" hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->favicon_57x) ? getImage(imagePath()['favicon']['path'] . '/' . $data->favicon_57x) : '' }}">
                                            @if (!isset($data->favicon_57x))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">{{ __('admin.logo_icon.favicon_57x') }}</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-success btn-block select-image-favicon mb-2">{{ __('admin.logo_icon.select_favicon') }}</button>
                                        <small class="text-facebook"
                                            style="font-size: 0.8rem;">{{ __('admin.logo_icon.files_supported_formats') }}:
                                            <b>{{ __('admin.logo_icon.extension.jpeg') }}, {{ __('admin.logo_icon.extension.jpg') }},
                                                {{ __('admin.logo_icon.extension.png') }}</b>.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="favicon_72x" id="favicon_72x"
                                            accept=".png, .jpg, .jpeg" hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->favicon_72x) ? getImage(imagePath()['favicon']['path'] . '/' . $data->favicon_72x) : '' }}">
                                            @if (!isset($data->favicon_72x))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">{{ __('admin.logo_icon.favicon_72x') }}</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-success btn-block select-image-favicon mb-2">{{ __('admin.logo_icon.select_favicon') }}</button>
                                        <small class="text-facebook"
                                            style="font-size: 0.8rem;">{{ __('admin.logo_icon.files_supported_formats') }}:
                                            <b>{{ __('admin.logo_icon.extension.jpeg') }}, {{ __('admin.logo_icon.extension.jpg') }},
                                                {{ __('admin.logo_icon.extension.png') }}</b>.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="favicon_114x" id="favicon_114x"
                                            accept=".png, .jpg, .jpeg" hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->favicon_114x) ? getImage(imagePath()['favicon']['path'] . '/' . $data->favicon_114x) : '' }}">
                                            @if (!isset($data->favicon_114x))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">{{ __('admin.logo_icon.favicon_114x') }}</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-success btn-block select-image-favicon mb-2">{{ __('admin.logo_icon.select_favicon') }}</button>
                                        <small class="text-facebook"
                                            style="font-size: 0.8rem;">{{ __('admin.logo_icon.files_supported_formats') }}:
                                            <b>{{ __('admin.logo_icon.extension.jpeg') }}, {{ __('admin.logo_icon.extension.jpg') }},
                                                {{ __('admin.logo_icon.extension.png') }}</b>.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-block"
                                        id="logo_btn">{{ __('admin.event.submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Function to handle image area initialization
            function initializeImageArea(imgArea) {
                const imageNameFromDatabase = imgArea.dataset.img;
                const img = document.createElement('img');

                if (imageNameFromDatabase) {
                    img.src = imageNameFromDatabase;
                    imgArea.appendChild(img);
                    imgArea.classList.add('active');
                } else {
                    img.src = ""
                }
            }

            // Initialize image areas on document ready
            document.querySelectorAll('.img-area').forEach(function(
                imgArea) {
                initializeImageArea(imgArea);
            });

            // Bắt sự kiện click cho nút chọn hình ảnh
            $('.select-image-logo, .select-image-favicon').click(function() {
                // Tìm input file tương ứng
                var input = $(this).closest('.form-group').find('input[type="file"]');

                // Kích hoạt sự kiện click cho input file
                input.click();
            });

            // Bắt sự kiện change cho tất cả các input file
            $('input[type="file"]').change(function() {
                var input = $(this);
                var imageArea = input.siblings('.img-custom');

                if (input[0].files && input[0].files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Hiển thị hình ảnh đã chọn
                        imageArea.html('<img src="' + e.target.result + '" class="img-fluid">');
                    };
                    reader.readAsDataURL(input[0].files[0]);
                }
            });

            // Bắt sự kiện click cho nút gửi Ajax
            $(document).on("submit", "#logo_icon_form", function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                $("#logo_btn").text("Submitting...");

                // Gửi Ajax để tải lên tất cả các hình ảnh cùng một lúc
                $.ajax({
                    url: "{{ route('admin.setting.logo.icon.submit') }}",
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        $("#logo_btn").text("Submit");
                    }
                }).fail(function(xhr, status, error) {
                    var errors = xhr.responseJSON;
                    toastr.error(errors.message);
                });

            });
        });
    </script>
@endpush
