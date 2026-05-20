@extends('admin.layouts.base')

@push('styles')
    <style>
        .img-area img {
            width: inherit;
        }
    </style>
@endpush

@php
    $data = $contact ? json_decode($contact->data_value) : (object) [];
@endphp

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">{{ __('admin.breadcrumbs.contact_us') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.breadcrumbs.manage') }}</li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.frontend.contact') }}">{{ __('admin.breadcrumbs.contact_us') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <form id="contact_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="image_url_old" value="{{ isset($data->image_url) ? $data->image_url : '' }}">
                        <input type="hidden" name="map_url_old" value="{{ isset($data->map_url) ? $data->map_url : '' }}">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">{{ __('admin.contact.title') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('admin.contact.enter_title') }}"
                                            name="title" value="{{ @$data->title }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">{{ __('admin.contact.address') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('admin.contact.enter_address') }}"
                                            name="address" value="{{ @$data->address }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">{{ __('admin.contact.email') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('admin.contact.enter_email') }}"
                                            name="email" value="{{ @$data->email }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">{{ __('admin.contact.phone_number') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('admin.contact.enter_phone_number') }}"
                                            name="phone_number" value="{{ @$data->phone_number }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">{{ __('admin.contact.question') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('admin.contact.enter_question') }}"
                                            name="question" value="{{ @$data->question }}" required />
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="file" name="image_url" id="file" accept=".png, .jpg, .jpeg"
                                            hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->image_url) ? getImage(imagePath()['contact']['path'] . '/' . $data->image_url) : '' }}">
                                            @if (!isset($data->image_url))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">{{ __('admin.contact.image_url') }}</p>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-success btn-block select-image mb-2">{{ __('admin.contact.select_image') }}</button>
                                        <small class="text-facebook" style="font-size: 0.8rem;">{{ __('admin.ext.supported_file') }}
                                            <b>{{ __('admin.ext.jpeg') }}, {{ __('admin.ext.jpg') }}, {{ __('admin.ext.png') }}</b>.
                                            {{ __('admin.ext.resize_text') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="file" name="map_url" id="file" accept=".png, .jpg, .jpeg"
                                            hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->map_url) ? getImage(imagePath()['contact']['path'] . '/' . $data->map_url) : '' }}">
                                            @if (!isset($data->map_url))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <p class="mt-2 ml-2" style="font-weight: 600">{{ __('admin.contact.map_url') }}</p>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-success btn-block select-image mb-2">{{ __('admin.contact.select_image') }}</button>
                                        <small class="text-facebook" style="font-size: 0.8rem;">@lang('Supported files'):
                                            <b>{{ __('admin.ext.jpeg') }}, {{ __('admin.ext.jpg') }}, {{ __('admin.ext.png') }}</b>.
                                            {{ __('admin.ext.resize_text') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <div class="form-group">
                                        <button type="submit" id="contact_btn"
                                            class="btn btn btn-primary btn-block btn-lg">{{ __('admin.action.submit') }}</button>
                                    </div>
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
        window.translations = {
            submit: "{{ __('admin.action.submit') }}",
            submitting: "{{ __('admin.action.submitting') }}",
        };
    </script>

    <script>
        $(document).ready(function() {
            // Xử lý khởi tạo vùng ảnh
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

            // Khởi tạo các vùng hình ảnh trên tài liệu
            document.querySelectorAll('.img-area').forEach(function(
                imgArea) {
                initializeImageArea(imgArea);
            });

            // Bắt sự kiện click cho nút chọn hình ảnh
            $('.select-image').click(function() {
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

            $(document).on("submit", "#contact_form", function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                formData.append('_token', '{{ csrf_token() }}');
                $("#contact_btn").text(window.translations.submitting);

                $.ajax({
                    url: "{{ route('admin.frontend.contact.submit') }}",
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        $("#contact_btn").text(window.translations.submit);
                    }
                }).fail(function(xhr, status, error) {
                    var errors = xhr.responseJSON;
                    toastr.error(errors.message);
                });
            })
        });
    </script>
@endpush
