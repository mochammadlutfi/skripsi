@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-3 mb-3">
        Peramalan Pengadaan
    </div>
    <div class="block">
        <div class="block-content bg-body-light">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="field-kategori">Kategori</label>
                        <select class="form-control" id="field-kategori" name="kategori" placeholder="Pilih Kategori"  style="width: 100%"></select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="field-merk">Merk</label>
                        <select class="form-control" id="field-merk" name="merk" placeholder="Pilih Merk" style="width: 100%"></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="si si-magnifier"></i>
                                </span>
                            </div>
                            <input type="text" id="field-keyword" class="form-control" placeholder="Cari Produk">
                            <span class="input-group-append">
                                <div class="input-group-text bg-transparent"><i class="fa fa-times"></i></div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-8" id="produk_nav">
                            <span>Menampilkan Produk 1-9 Dari 9</span>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-alt-secondary float-right" id="nextProduk">
                                <i class="fa fa-chevron-right fa-fw"></i>
                            </button>
                            <button type="button" class="btn btn-alt-secondary float-left" id="prevProduk">
                                <i class="fa fa-chevron-left fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content pb-15" id="list-produk">
            <input type="hidden" id="produk_current_page" value="">
            <input type="hidden" id="tipe_transaksi" value="pembelian">
            <div class="row" id="list-produk-body">
                @include('peramalan.include.produk_data')
            </div>
        </div>
    </div>
</div>
@include('peramalan.include.modal_variasi')
@include('peramalan.include.modal_peramalan')
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/peramalan.js') }}"></script>
@endpush
