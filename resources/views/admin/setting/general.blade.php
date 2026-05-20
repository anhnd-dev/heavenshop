@extends('admin.layouts.base')

@php
    $data = $setting ? json_decode($setting->data_value) : (object) [];
@endphp

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">{{ __('admin.common.general_setting') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.common.manage') }}</li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.setting.general') }}">{{ __('admin.common.general_setting') }}</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <form id="general_setting_form">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('admin.general_setting.shipping_free_threshold') }}</label>
                                        <input type="text" name="shipping_free_threshold" class="form-control"
                                            value="{{ @$data->shipping_free_threshold }}" placeholder="Enter shipping free threshold">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="general_setting_btn" class="btn btn btn-primary btn-block">{{ __('admin.event.submit') }}</button>
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
            $("#general_setting_form").submit(function(e) {
                e.preventDefault();

                const fd = new FormData(this);
                $("#general_setting_btn").text("Submitting...");

                $.ajax({
                    url: "{{ route('admin.setting.general.submit') }}",
                    method: "POST",
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        $("#general_setting_btn").text("Submit");
                    }
                });
            });
        });
    </script>
@endpush
