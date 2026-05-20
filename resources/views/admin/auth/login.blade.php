@extends('admin.layouts.auth')

@section('content')
    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->

                    <form class="md-float-material form-material" action="{{ route('admin.postLogin') }}" method="POST">
                        @csrf
                        <div class="text-center">
                            @php
                                if ($logoIcon && $logoIcon->logo_white) {
                                    $logoWhitePath = getImage(
                                        imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_white,
                                    );
                                } else {
                                    $logoWhitePath = getImage(imagePath()['image']['default']);
                                }
                            @endphp
                            <img src="{{ $logoWhitePath }}" alt="logo.png" width="100px">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Sign In</h3>
                                    </div>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="text" name="email" class="form-control" required=""
                                        placeholder="Your Email Address">
                                    <span class="form-bar"></span>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="password" name="password" class="form-control" required=""
                                        placeholder="Password">
                                    <span class="form-bar"></span>
                                </div>
                                <div class="row m-t-25 text-left">
                                    <div class="col-12">
                                        <div class="checkbox-fade fade-in-primary d-">
                                            <label>
                                                <input type="checkbox" name="remember" value="remember">
                                                <span class="cr"><i
                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                <span class="text-inverse">Remember me</span>
                                            </label>
                                        </div>
                                        <div class="forgot-phone text-right f-right">
                                            <a href="auth-reset-password.htm" class="text-right f-w-600"> Forgot
                                                Password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit"
                                            class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Sign
                                            in</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-inverse text-left m-b-0">Thank you.</p>
                                        <p class="text-inverse text-left"><a href="{{ route('home') }}"><b
                                                    class="f-w-600">Back
                                                    to website</b></a></p>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        @php
                                            if ($logoIcon && $logoIcon->logo_black) {
                                                $logoBlackPath = getImage(
                                                    imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black,
                                                );
                                            } else {
                                                $logoBlackPath = getImage(imagePath()['image']['default']);
                                            }
                                        @endphp
                                        <img src="{{ $logoBlackPath }}" alt="small-logo.png" width="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
@endsection
