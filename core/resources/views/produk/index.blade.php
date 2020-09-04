@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-3 mb-3">
        <a href="{{ route('produk.tambah') }}" class="btn btn-secondary float-right mr-5"><i class="si si-plus mr-1"></i>Tambah Produk</a>
        Kelola Produk
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
                        <label for="field-keyword">Pencarian</label>
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
                        <div class="col-md-7 pt-30" id="produk_nav">
                            <span>{{ $navigasi['nav'] }}</span>
                        </div>
                        <div class="col-md-5 pt-25">
                            <button type="button" class="btn btn-alt-secondary float-right" id="nextProduk" @if(!$navigasi['next']) disabled="disabled" @endif>
                                <i class="fa fa-chevron-right fa-fw"></i>
                            </button>
                            <button type="button" class="btn btn-alt-secondary float-left" id="prevProduk" @if(!$navigasi['prev']) disabled="disabled" @endif>
                                <i class="fa fa-chevron-left fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content pb-15">
            <input type="hidden" id="current_page" value="1">
            <table class="js-table-sections table table-borderless table-striped font-size-sm" id="list-produk">
                <thead>
                    <tr>
                        <th></th>
                        <th class="font-weight-bold">Foto</th>
                        <th class="font-weight-bold">Nama Produk</th>
                        <th class="font-weight-bold">Kategori</th>
                        <th class="font-weight-bold">Harga Jual</th>
                        <th class="font-weight-bold">Total Stok</th>
                        <th class="font-weight-bold" width="15%">Merk</th>
                        <th class="font-weight-bold">Aksi</th>
                    </tr>
                </thead>
                @include('produk.include.data')
            </table>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script>jQuery(function(){ Codebase.helpers('table-tools'); });</script>
<script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ asset('assets/js/pages/produk.js') }}"></script>
<script>
</script>
@endpush
