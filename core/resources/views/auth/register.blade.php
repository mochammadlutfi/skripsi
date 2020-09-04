<!doctype html>
<html lang="en" class="no-focus">

<head>
    @include('mitra.layouts.meta')
    <!-- Fonts and Codebase framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.css') }}">
    <link rel="stylesheet" id="css-theme" href="{{ asset('assets/css/themes/earth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
</head>

<body>
    <div id="page-container" class="main-content-boxed">

        <!-- Main Container -->
        <main id="main-container">
            <!-- Page Content -->
            <div class="bg-image" style="background-image: url('assets/media/photos/photo34@2x.jpg');">
                <div class="row mx-0 bg-earth-op">
                    <div class="hero-static col-md-6 col-xl-5 d-none d-md-flex align-items-md-end">
                        <div class="p-30 invisible" data-toggle="appear">
                            <p class="font-size-h3 font-w600 text-white mb-5">
                                We're very happy you are joining our community!
                            </p>
                            <p class="font-size-h5 text-white">
                                <i class="fa fa-angles-right"></i> Create your account today and receive 50% off.
                            </p>
                            <p class="font-italic text-white-op">
                                Copyright &copy; <span class="js-year-copy"></span>
                            </p>
                        </div>
                    </div>
                    <div class="hero-static col-md-6 col-xl-7 d-flex align-items-center bg-white">
                        <div class="content content-full p-10">
                            <!-- Header -->
                            <div class="px-30 py-10">
                                <h1 class="h3 font-w700 mt-1 mb-10">Daftar Sekarang!</h1>
                                <h2 class="h5 font-w400 text-muted mb-0">Ayo tingkatkan kualitas bisnis anda sekarang!
                                </h2>
                            </div>
                            <!-- END Header -->
                            <div id="pendaftaran" class="block">
                                <!-- Step Tabs -->
                                <ul class="nav nav-tabs nav-tabs-alt nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#info_warung" data-toggle="tab">1.
                                            Bisnis</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#info_pemilik" data-toggle="tab">2.
                                            Pemilik</a>
                                    </li>
                                </ul>
                                <!-- END Step Tabs -->

                                <!-- Form -->
                                <form id="form-pendaftaran" method="post" onsubmit="return false;">
                                    <!-- Steps Content -->
                                    <div class="block-content block-content-full tab-content"
                                        style="min-height: 274px;">
                                        <!-- Step 1 -->
                                        <div class="tab-pane active" id="info_warung" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-1">
                                                        <label for="field-bisnis_nama">Nama Bisnis</label>
                                                        <input class="form-control" type="text" id="field-bisnis_nama"
                                                            name="bisnis_nama" placeholder="Masukan Nama Bisnis">
                                                        <div id="error-bisnis_nama" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-1">
                                                        <label for="field-bisnis_kategori">Kategori Bisnis</label>
                                                        <select class="form-control" name="bisnis_kategori"
                                                            id="field-bisnis_kategori" style="width:100%"></select>
                                                        <div id="error-bisnis_kategori" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-1">
                                                        <label for="field-no_kontak">No Kontak Usaha</label>
                                                        <input class="form-control" type="text" id="field-no_kontak"
                                                            name="no_kontak" placeholder="Masukan No. Kontak">
                                                        <div id="error-no_kontak" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-1">
                                                        <label for="field-no_kontak_alt">No Kontak Alternatif
                                                            Usaha</label>
                                                        <input class="form-control" type="text" id="field-no_kontak_alt"
                                                            name="no_kontak_alt" placeholder="Masukan No Kontak Alternatif">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group mb-1">
                                                        <label for="field-bisnis_daerah">Daerah Bisnis</label>
                                                        <select class="form-control" name="bisnis_daerah"
                                                            id="field-bisnis_daerah" style="width:100%"></select>
                                                        <div id="error-bisnis_daerah" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-1">
                                                        <label for="field-pos">Kode Pos</label>
                                                        <input class="form-control" type="text" id="field-pos"
                                                            name="pos"  placeholder="Masukan Kode POS">
                                                        <div id="error-pos" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-1">
                                                        <label for="field-bisnis_alamat">Alamat Lengkap</label>
                                                        <textarea class="form-control" name="bisnis_alamat"
                                                            id="field-bisnis_alamat"
                                                            placeholder="Masukan Alamat Usaha"></textarea>
                                                        <div id="error-bisnis_alamat" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Step 1 -->

                                        <!-- Step 2 -->
                                        <div class="tab-pane" id="info_pemilik" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="field-pemilik_nama">Nama Lengkap</label>
                                                        <input class="form-control" type="text" id="field-pemilik_nama"
                                                            name="pemilik_nama" placeholder="Masukan Nama Lengkap">
                                                        <div id="error-pemilik_nama" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="field-pemilik_username">Username</label>
                                                        <input class="form-control" type="text"
                                                            id="field-pemilik_username" name="pemilik_username" placeholder="Masukan Username">
                                                        <div id="error-pemilik_username" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="field-pemilik_hp">No. Handphone</label>
                                                        <input class="form-control" type="text" id="field-pemilik_hp"
                                                            name="pemilik_hp" placeholder="No. Handphone">
                                                        <div id="error-pemilik_hp" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="field-pemilik_email">Alamat Email</label>
                                                        <input class="form-control" type="email"
                                                            id="field-pemilik_email" name="pemilik_email"
                                                            placeholder="Masukan Alamat Email">
                                                        <div id="error-pemilik_email" class="invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="field-password">Password</label>
                                                        <input class="form-control" type="password" id="field-password"
                                                            name="password" placeholder="Masukan Password">
                                                        <div id="error-password" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="field-knf_password">Konfirmasi Password</label>
                                                        <input class="form-control" type="password"
                                                            id="field-knf_password" name="knf_password"
                                                            placeholder="Masukan Konfirmasi Password">
                                                        <div id="error-knf_password" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Step 2 -->
                                    </div>
                                    <!-- END Steps Content -->

                                    <!-- Steps Navigation -->
                                    <div class="block-content block-content-sm block-content-full bg-body-light">
                                        <div class="row">
                                            <div class="col-6">
                                                <button type="button" class="btn btn-alt-secondary disabled"
                                                    data-wizard="prev">
                                                    <i class="fa fa-angle-left mr-5"></i> Sebelumnya
                                                </button>
                                            </div>
                                            <div class="col-6 text-right">
                                                <button type="button" class="btn btn-alt-secondary" data-wizard="next">
                                                    Selanjutnya <i class="fa fa-angle-right ml-5"></i>
                                                </button>
                                                <button type="submit" class="btn btn-alt-primary d-none"
                                                    data-wizard="finish">
                                                    <i class="fa fa-check mr-5"></i> Daftar Sekarang
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END Steps Navigation -->
                                </form>
                                <!-- END Form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->

    <!-- Terms Modal -->
    <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-slidedown" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Terms &amp; Conditions</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <p>Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper etiam bibendum ad feugiat
                            magna accumsan dolor, nibh molestie cras hac ac ad massa, fusce ante convallis ante urna
                            molestie vulputate bibendum tempus ante justo arcu erat accumsan adipiscing risus, libero
                            condimentum venenatis sit nisl nisi ultricies sed, fames aliquet consectetur consequat
                            nostra molestie neque nullam scelerisque neque commodo turpis quisque etiam egestas
                            vulputate massa, curabitur tellus massa venenatis congue dolor enim integer luctus, nisi
                            suscipit gravida fames quis vulputate nisi viverra luctus id leo dictum lorem, inceptos nibh
                            orci.</p>
                        <p>Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper etiam bibendum ad feugiat
                            magna accumsan dolor, nibh molestie cras hac ac ad massa, fusce ante convallis ante urna
                            molestie vulputate bibendum tempus ante justo arcu erat accumsan adipiscing risus, libero
                            condimentum venenatis sit nisl nisi ultricies sed, fames aliquet consectetur consequat
                            nostra molestie neque nullam scelerisque neque commodo turpis quisque etiam egestas
                            vulputate massa, curabitur tellus massa venenatis congue dolor enim integer luctus, nisi
                            suscipit gravida fames quis vulputate nisi viverra luctus id leo dictum lorem, inceptos nibh
                            orci.</p>
                        <p>Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper etiam bibendum ad feugiat
                            magna accumsan dolor, nibh molestie cras hac ac ad massa, fusce ante convallis ante urna
                            molestie vulputate bibendum tempus ante justo arcu erat accumsan adipiscing risus, libero
                            condimentum venenatis sit nisl nisi ultricies sed, fames aliquet consectetur consequat
                            nostra molestie neque nullam scelerisque neque commodo turpis quisque etiam egestas
                            vulputate massa, curabitur tellus massa venenatis congue dolor enim integer luctus, nisi
                            suscipit gravida fames quis vulputate nisi viverra luctus id leo dictum lorem, inceptos nibh
                            orci.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-alt-success" data-dismiss="modal">
                        <i class="fa fa-check"></i> Perfect
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Terms Modal -->
    <script src="{{ asset('assets/js/laroute.js') }}"></script>
    <script src="{{ asset('assets/js/codebase.app.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/select2/js/i18n/id.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    {{-- <script src="assets/js/pages/op_auth_signup.min.js"></script> --}}
    <script src="{{ asset('assets/js/pages/mitra/pendaftaran.js') }}"></script>
</body>

</html>
