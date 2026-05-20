@extends('admin.layouts.base')

@push('styles-lib')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@php
    $data = $seo ? json_decode($seo->data_value) : (object) [];
@endphp

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">SEO Configuration</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Manage</li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.seo') }}">SEO Configuration</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <form id="seo_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="image_old" value="{{ isset($data->image) ? $data->image : '' }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" name="image" id="file" accept=".png, .jpg, .jpeg"
                                            hidden>
                                        <div class="img-area img-thumbnail img-custom"
                                            data-img="{{ isset($data->image) ? getImage(imagePath()['seo']['path'] . '/' . $data->image) : '' }}">
                                            @if (!isset($data->image))
                                                <i class="mdi mdi-cloud-upload"></i>
                                                <h3>Upload Image</h3>
                                                <p>Image size must be less than <span>2MB</span></p>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-success btn-block select-image mb-2">Select
                                            Image</button>
                                        <small class="text-facebook" style="font-size: 0.8rem;">@lang('Supported files'):
                                            <b>@lang('jpeg'), @lang('jpg'), @lang('png')</b>.
                                            @lang('Image will be resized into')
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold">@lang('Meta Keywords')</label>
                                        <small class="ml-2 mt-2 text-facebook">@lang('Separate multiple keywords by')
                                            <code>,</code>(@lang('comma')) @lang('or')
                                            <code>@lang('enter')</code> @lang('key')</small>
                                        <select name="keywords[]" class="form-control select2-auto-tokenize"
                                            multiple="multiple" required>
                                            @if (@$data->keywords)
                                                @foreach ($data->keywords as $option)
                                                    <option value="{{ $option }}" selected>{{ __($option) }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label  font-weight-bold">@lang('Meta Description')</label>
                                        <textarea name="description" rows="3" class="form-control" placeholder="@lang('SEO meta description')" required>{{ @$data->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label  font-weight-bold">@lang('Social Title')</label>
                                        <input type="text" class="form-control" placeholder="@lang('Social Share Title')"
                                            name="social_title" value="{{ @$data->social_title }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label  font-weight-bold">@lang('Social Description')</label>
                                        <textarea name="social_description" rows="3" class="form-control" placeholder="@lang('Social Share  meta description')" required>{{ @$data->social_description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="seo_btn"
                                            class="btn btn btn-primary btn-block btn-lg">@lang('Submit')</button>
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

@push('scripts-lib')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2-auto-tokenize').select2({
                dropdownParent: $('.card-body'),
                tags: true,
                tokenSeparators: [',']
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const selectImage = document.querySelector('.select-image');
            const inputFile = document.querySelector('#file');
            const imgArea = document.querySelector('.img-area');

            // Kiểm tra và gán tên file ảnh từ cơ sở dữ liệu khi tải lại trang
            const imageNameFromDatabase = imgArea.dataset.img;
            const img = document.createElement('img');

            if (imageNameFromDatabase) {
                img.src = imageNameFromDatabase;
                imgArea.appendChild(img);
                imgArea.classList.add('active');
            } else {
                img.src = "";
            }

            selectImage.addEventListener('click', function() {
                inputFile.click();
            });

            inputFile.addEventListener('change', function() {
                const image = this.files[0];
                if (image) {
                    if (image.size < 2000000) {
                        const reader = new FileReader();
                        reader.onload = () => {
                            const imgUrl = reader.result;
                            const img = document.createElement('img');
                            img.src = imgUrl;
                            imgArea.innerHTML = ''; // Xóa hình ảnh hiện có trước khi hiển thị ảnh mới
                            imgArea.appendChild(img);
                            imgArea.classList.add('active');
                            imgArea.dataset.img = image.name; // Lưu tên file ảnh vào phần tử .img-area
                        };
                        reader.readAsDataURL(image);
                    } else {
                        alert("Image size more than 2MB");
                        this.value =
                        ''; // Xóa giá trị của trường nhập để cho phép người dùng chọn một tệp ảnh khác
                    }
                }
            });
        });

        $(document).on("submit", "#seo_form", function(e) {
            e.preventDefault();

            const fd = new FormData(this);
            $("#seo_btn").text("Submitting...");

            $.ajax({
                url: "{{ route('admin.seo.submit') }}",
                method: "POST",
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json"
            }).done(function(res) {
                if (res.status == 200) {
                    toastr.success(res.message);
                    $("#seo_btn").text("Submit");
                }
            }).fail(function(xhr, status, error) {
                var errors = xhr.responseJSON;
                toastr.error(errors.message);
            });
        })
    </script>
@endpush
