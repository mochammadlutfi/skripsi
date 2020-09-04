<!doctype html>
<html lang="en" class="no-focus">
    <head>
        @include('layouts.meta')
        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">

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
                            <h2 class="h5 font-w700 mb-0 mt-30">Lupa Password</h2>
                        </div>
                        <!-- END Header -->
                        <div class="row justify-content-center px-5">
                            <div class="col-sm-8 col-md-6 col-xl-4">
                                <form method="post" id="form-ResetPassword" onsubmit="return false;">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="field-email">Username / Email</label>
                                            <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="field-email" name="email" value="{{ old('email') }}" placeholder="Masukan Username/Alamat Email">
                                            <div class="invalid-feedback" id="error-email">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-12 text-sm-right">
                                            <button type="submit" class="btn btn-alt-primary btn-block">
                                                <i class="si si-paper-plane mr-10"></i> Kirim
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group row justify-content-center mb-0 mt-1">
                                        <div class="col-12 text-center">
                                            <span>Kembali Ke Halaman <a href="{{ url('/') }}"><b>Login</b></a></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->
        <script src="{{ asset('assets/js/laroute.js') }}"></script>
        <script src="{{ asset('assets/js/codebase.app.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <script>
        $(document).ready(function () {
            $("#form-ResetPassword").submit(function (e) {
                e.preventDefault();
                var formData = new FormData($('#form-ResetPassword')[0]);
                $.ajax({
                    url: laroute.route('lupa.getEmail'),
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        Swal.fire({
                            title: 'Tunggu Sebentar...',
                            text: ' ',
                            imageUrl: laroute.url('assets/img/loading.gif', ['']),
                            showConfirmButton: false,
                            allowOutsideClick: false,
                        });
                    },
                    success: function (response) {
                        $('.is-invalid').removeClass('is-invalid');
                        if (response.fail == false) {
                            Swal.fire({
                                title: "Berhasil",
                                text: "Informasi Reset Password Berhasil Dikirim Ke Alamat Email!",
                                timer: 5000,
                                showConfirmButton: false,
                                icon: 'success'
                            });
                            window.setTimeout(function () {
                                location.href = laroute.route('kendaraan');
                            }, 1500);
                        } else {
                            Swal.close();
                            for (control in response.errors) {
                                $('#field-' + control).addClass('is-invalid');
                                $('#error-' + control).html(response.errors[control]);
                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.close();
                        alert('Error adding / update data');
                    }
                });
            });
        });
        </script>
        {{-- <script src="{{ asset('assets/backend/js/codebase.app.min.js') }}"></script> --}}
    </body>
</html>
