@extends('admin.layouts.base')
@push('styles')
    <style>
        td {
            width: 50%;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">System Information</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Manage</li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.system.info') }}">System Information</a>
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>PHP Version</td>
                                        <td>{{ $currentPHP }}</td>
                                    </tr>
                                    <tr>
                                        <td>Laravel Version</td>
                                        <td>{{ $laravelVersion }}</td>
                                    </tr>
                                    <tr>
                                        <td>Server Software</td>
                                        <td>{{ @$serverDetails['SERVER_SOFTWARE'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Server IP Address</td>
                                        <td>{{ @$serverDetails['REMOTE_ADDR'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Server Protocol</td>
                                        <td>{{ @$serverDetails['SERVER_PROTOCOL'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>HTTP Host</td>
                                        <td>{{ @$serverDetails['HTTP_HOST'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Database Connection</td>
                                        <td>{{ @$serverDetails['DB_CONNECTION'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Database Port</td>
                                        <td>{{ @$serverDetails['DB_PORT'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Database Name</td>
                                        <td>{{ @$serverDetails['DB_DATABASE'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>App Environment</td>
                                        <td>{{ @$serverDetails['APP_ENV'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>App Debug</td>
                                        <td>{{ @$serverDetails['APP_DEBUG'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Timezone</td>
                                        <td>{{ @$timeZone }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
@endsection
