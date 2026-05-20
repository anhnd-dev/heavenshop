@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/bootstrap4-toggle/css/bootstrap4-toggle.min.css') }}" />
@endpush

@php
    $data = $cookie ? json_decode($cookie->data_value) : (object) [];
@endphp

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">{{ __('admin.common.gdpr_cookie') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.common.manage') }}</li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.setting.cookie') }}">{{ __('admin.common.gdpr_cookie') }}</a>
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
                    <form id="cookie_form">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('admin.cookie.policy_link') }}</label>
                                        <input type="text" name="link" class="form-control"
                                            value="{{ @$data->link }}" placeholder="Policy Link">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('admin.common.status') }}</label>
                                        <input type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disabled"
                                            data-onstyle="success" data-offstyle="danger"
                                            @if (@$data->status) checked @endif name="status">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('admin.common.description') }}</label>
                                <textarea class="form-control" rows="10" name="description" placeholder="Description">@php echo @$data->description @endphp</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="cookie_btn" class="btn btn btn-primary btn-block">{{ __('admin.event.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
@endsection

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/bootstrap4-toggle/js/bootstrap4-toggle.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#cookie_form").submit(function(e) {
                e.preventDefault();

                const fd = new FormData(this);
                $("#cookie_btn").text("Submitting...");

                $.ajax({
                    url: "{{ route('admin.setting.cookie.submit') }}",
                    method: "POST",
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        $("#cookie_btn").text("Submit");
                    }
                });
            });
        });
    </script>
@endpush
