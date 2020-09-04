@extends('layouts.master')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" />
<style>
    #list-returbeli_filter {
        display: none;
    }
</style>
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Kelola Retur Pembelian
    </div>
    <div class="block">
        <div class="block-content bg-body-light">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="tgl_range" placeholder="Cari Berdasarkan Tanggal">
                            </div>
                        </div>
                        <input type="hidden" id="tgl_mulai" value="">
                        <input type="hidden" id="tgl_akhir" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                                <select class="form-control" id="supplier"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-store-alt"></i>
                                    </span>
                                </div>
                                <select class="form-control" id="supplier"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content pb-15">
            <table class="table table-hover table-vcenter" id="list-returbeli">
                <thead>
                    <tr>
                        <th class="font-weight-bold">Tanggal</th>
                        <th class="font-weight-bold">Pembelian</th>
                        <th class="font-weight-bold">Supplier</th>
                        <th class="font-weight-bold">Nominal Retur</th>
                        <th class="font-weight-bold">Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop
@push('scripts')
{{-- <script src="{{ asset('assets/js/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script> --}}
<script src="{{ asset('assets/js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/mitra/returbeli.js') }}"></script>
@endpush
