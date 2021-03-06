@extends('layouts.master')

@section('content')
<!-- Page Content -->
<div class="content">
    <div class="row invisible" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-6 col-xl-6">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-bag fa-2x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-primary" data-toggle="countTo" data-speed="1000" data-to="{{ $data['pembelian_pending'] }}">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Total Pembelian Produk</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-6">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-envelope-open fa-2x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-elegance" data-toggle="countTo" data-speed="1000" data-to="{{ $data['pembelian_hari_ini'] }}">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Pembelian Hari Ini</div>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>
    <div class="row invisible" data-toggle="appear">
        <!-- Row #2 -->
        <div class="col-md-12">
            <div class="block block-rounded block-bordered">
                <div class="block-header block-header-default border-b">
                    <h3 class="block-title">
                        Pembelian <small>Minggu Ini</small>
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="pull-all pt-50">
                        {!! $pembelianChart->container() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- END Row #2 -->
    </div>
</div>
<!-- END Page Content -->

@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/chartjs/Chart.bundle.min.js') }}"></script>
{!! $pembelianChart->script() !!}
@endpush
