<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>SIM Distribusi | Cihanjuang Mandiri</title>

        <meta name="description" content="SIM Distribusi Cihanjuang Mandiri">
        <meta name="author" content="SIM Distribusi Cihanjuang Mandiri">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="SIM Distribusi Cihanjuang Mandiri">
        <meta property="og:site_name" content="SIM Distribusi Cihanjuang Mandiri">
        <meta property="og:description" content="SIM Distribusi Cihanjuang Mandiri">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">


        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        {{-- <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
            <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
            <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}"> --}}
        <!-- END Icons -->

        <meta name="csrf-token" content="{{ csrf_token() }}">


        <!-- Stylesheets -->

        <!-- Page JS Plugins CSS -->

        {{-- <link rel="stylesheet" href="{{ asset('assets/backend/js/plugins/slick/slick.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css">
        {{-- <link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}"> --}}

        @yield('styles')
        {{--  --}}
        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        {{-- <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.css') }}"> --}}
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
        <!-- Scripts -->
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
        <!-- END Stylesheets -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Currency setting -->
        <input type="hidden" id="p_code" value="IDR">
        <input type="hidden" id="p_symbol" value="Rp">
        <input type="hidden" id="p_thousand" value=".">
        <input type="hidden" id="p_decimal" value=",">
        <input type="hidden" id="__code" value="IDR">
        <input type="hidden" id="__symbol" value="Rp">
        <input type="hidden" id="__thousand" value=".">
        <input type="hidden" id="__decimal" value=",">
        <input type="hidden" id="__symbol_placement" value="before">
        <input type="hidden" id="__precision" value="0">
        <input type="hidden" id="__quantity_precision" value="0">
    </head>
    <body>

        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header main-content-boxed">

            @include('layouts.sidebar')
            <!-- END Sidebar -->

            <!-- Header -->
            @include('layouts.header')
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                @yield('content')
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->

            @include('layouts.footer');

        </div>
        @include('layouts.incl_scripts');
        @stack('scripts')
    </body>
</html>
