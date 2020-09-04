@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
@endsection


@section('content')
<div class="content">
    <form id="form-produk" onsubmit="return false;">
        <div class="content-heading pt-3 mb-3">
            Ubah Data Produk
            <button type="submit" class="btn btn-secondary float-right mr-5">
                <i class="si si-paper-plane mr-1"></i>
                Simpan Produk
            </button>
        </div>
        <div class="block block-rounded">
            <div class="block-content pb-15">
                <input type="hidden" name="total_variasi" id="total_variasi" value="{{ $produk->produk_variasi()->count() }}">
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                <div class="row info_produk">
                    <div class="col-lg-8">
                        <h2 class="content-heading pt-0 mb-2">Detail Produk</h2>
                        <div class="form-group">
                            <label for="field-nama">Nama Produk</label>
                            <input type="text" class="form-control" id="field-nama" name="nama" placeholder="Masukan Nama Produk" value="{{ $produk->nama }}">
                            <div id="error-nama" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-kategori">Kategori</label>
                            <select class="form-control" id="field-kategori" name="kategori" data-id="{{ $produk->kategori_id }}" data-name="{{ $produk->kategori->nama }}">
                            </select>
                            <div id="error-kategori" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-merk">Merk</label>
                            <select class="form-control" id="field-merk" name="merk" data-id="{{ $produk->merk_id }}" data-name="{{ $produk->merk->nama }}"></select>
                            <div id="error-merk" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <h2 class="content-heading pt-0 mb-2">Foto Produk</h2>
                        <div class="form-group">
                            <div class="row justify-content-center mb-10">
                                <div class="col-6 py-1">
                                    @if(!empty($produk->foto))
                                    <img id="img_preview" src="{{ asset($produk->foto) }}" width="100%"/>
                                    @else
                                    <img id="img_preview" src="{{ asset('assets/img/placeholder/product.png') }}" width="100%"/>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn btn-alt-primary btn-block">
                                        <input type="file" class="file-upload" id="file-upload" accept="image/*">
                                        Pilih Foto
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('produk.include.tbl_variasi')

                <div class="row border-bottom mx-0 mb-2 tanpa_variasi" style="display:none">
                    <div class="col-lg-8 pl-0">
                        <h2 class="content-heading pt-0 mb-0 border-0">Harga Produk</h2>
                    </div>
                    <div class="col-lg-4 pr-0">
                    <button class="btn btn-alt-primary float-right btnVariasi" type="button"><i class="si si-plus mr-3"></i>Tambah Variasi</button>
                    </div>
                </div>
                <div class="row px-0 tanpa_variasi" style="display:none">
                    <div class="col-lg-4">
                        <div class="form-group mb-0">
                            <label for="field-sku">Kode Produk (SKU)</label>
                            <input type="text" class="form-control" id="field-sku" name="sku" placeholder="Masukan Kode Produk (SKU)">
                            <div id="error-sku" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-0">
                            <label for="field-hrg_modal">Harga Modal</label>
                            <input type="text" class="form-control" id="field-hrg_modal" name="hrg_modal" placeholder="Masukan Harga Modal">
                            <div id="error-hrg_modal" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-0">
                            <label for="field-hrg_jual">Harga Jual</label>
                            <input type="text" class="form-control" id="field-hrg_jual" name="hrg_jual" placeholder="Masukan Harga Jual">
                            <div id="error-hrg_jual" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="row border-bottom mx-0 mb-2 tanpa_variasi" style="display:none">
                    <div class="col-lg-12 px-0">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox checkbox-lg mb-5">
                                <input class="custom-control-input" type="checkbox" name="kelola_stok" id="kelola_stok" value="1" checked="">
                                <label class="custom-control-label" for="kelola_stok">Kelola Inventaris</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row px-0 tanpa_variasi inventaris" style="display:none">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="field-unit">Satuan Unit</label>
                            <select class="form-control" id="field-unit" name="unit" placeholder="Pilih Satuan Unit" style="width:100%;">
                            </select>
                            <div id="error-unit" class="text-danger font-size-sm"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="field-min_stok">Minimum Stok</label>
                            <input type="number" class="form-control" id="field-min_stok" name="min_stok" placeholder="Masukan Minimum Stok">
                            <div id="error-min_stok" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="field-volume">Volume</label>
                            <input type="number" class="form-control" step=".01" id="field-volume" name="volume" placeholder="Masukan Volume Produk">
                            <div id="error-volume" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="modal" id="modal_addUnit"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-header block-header-default">
                        <h3 class="block-title modal-title">Tambah Satuan Unit</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="form-satuan" onsubmit="return false;">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nama Unit Label</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-nama_satuan" class="form-control" name="nama_satuan" placeholder="Masukan Nama Satuan">
                                <div class="text-danger font-size-sm" id="error-nama_satuan"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-alt-primary btn-block"><i class="si si-check mr-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('produk.include.modal_add_variasi')

@include('produk.include.modal_ubah_variasi')


@include('include.modal_crop')
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
<script src="{{ asset('assets/js/pages/produk-edit.js') }}"></script>
@endpush
