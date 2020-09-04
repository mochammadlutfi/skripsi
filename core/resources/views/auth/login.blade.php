<!doctype html>
<html lang="en" class="no-focus">
    <head>
        @include('layouts.meta')
        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.css') }}">

    </head>
    <body>
        <div id="page-container" class="main-content-boxed">

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                <div class="bg-white">
                    <div class="hero-static content content-full bg-white invisible" data-toggle="appear">
                        <!-- Header -->
                        <div class="py-30 px-5 text-center">
                            <a class="" href="{{ url('/') }}">
                                <img src="{{ asset('assets/img/logo/logo_big.png') }}" width="150px">
                            </a>
                            <h2 class="h5 font-w700 mb-0 mt-30">Silakan masuk untuk melanjutkan</h2>
                        </div>
                        <!-- END Header -->

                        <!-- Sign In Form -->
                        <div class="row justify-content-center px-5">
                            <div class="col-sm-8 col-md-6 col-xl-4">
                                <form method="POST" action="{{ route($loginRoute) }}">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="login-username">Username / Email</label>
                                            <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" id="login-username" name="username" placeholder="Masukan Username/Email">
                                            @if ($errors->has('username'))
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="login-password">Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="login-password" name="password" placeholder="Masukan Password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <a href="javaScript:void(0);"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="text-danger" role="alert">
                                                    {{ $errors->first('password') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-6 d-sm-flex align-items-center push">
                                            <div class="custom-control custom-checkbox mr-auto ml-0 mb-0">
                                                <input type="checkbox" class="custom-control-input" id="login-remember-me" name="remember">
                                                <label class="custom-control-label" for="login-remember-me">Ingat Saya</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 text-sm-right push">
                                                @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route($forgotPasswordRoute) }}">
                                                    Lupa Password?
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-12 text-sm-right push">
                                            <button type="submit" class="btn btn-alt-primary btn-block">
                                                <i class="si si-login mr-10"></i> Masuk
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Sign In Form -->
                    </div>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->
        <script src="{{ asset('assets/js/codebase.app.js') }}"></script>
        <script>
            $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if($('#show_hide_password input').attr("type") == "text"){
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass( "fa-eye-slash" );
                    $('#show_hide_password i').removeClass( "fa-eye" );
                }else if($('#show_hide_password input').attr("type") == "password"){
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass( "fa-eye-slash" );
                    $('#show_hide_password i').addClass( "fa-eye" );
                }
            });
        });
        </script>
        {{-- <script src="{{ asset('assets/backend/js/codebase.app.min.js') }}"></script> --}}
    </body>
</html>
