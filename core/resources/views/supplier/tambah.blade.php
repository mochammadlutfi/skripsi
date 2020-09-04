@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection


@section('content')
<div class="content">
    <form id="form-supplier" onsubmit="return false;">
        <div class="content-heading pt-0 mb-3">
            Tambah Supplier
            <button type="submit" class="btn btn-secondary float-right mr-5">
                <i class="si si-paper-plane mr-1"></i>
                Simpan Supplier
            </button>
        </div>
        <div class="block">
            <div class="block-content pb-15">
                <div class="row px-0">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="field-nama">Nama Supplier</label>
                            <input type="text" class="form-control" name="nama" id="field-nama" placeholder="Masukan Nama Variasi Produk">
                            <div id="error-nama" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-telp">No. Telepon</label>
                            <input type="text" class="form-control" id="field-telp" name="telp" placeholder="Masukan Kode Produk (SKU)">
                            <div id="error-telp" class="invalid-feedback"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="field-wilayah">Wilayah</label>
                                    <select name="wilayah" id="field-wilayah" style="width:100%"></select>
                                    <div id="error-wilayah" class="text-danger font-size-sm"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="field-kd_pos">Kode Pos</label>
                                    <input type="text" class="form-control" id="field-kd_pos" name="kd_pos" placeholder="Kode Pos">
                                    <div id="error-kd_pos" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-alamat">Alamat</label>
                            <textarea class="form-control" id="field-alamat" name="alamat" placeholder="Masukan Alamat Lengkap" rows="4"></textarea>
                            <div id="error-alamat" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="field-perwakilan_nama">Nama Perwakilan</label>
                            <input type="text" class="form-control" name="perwakilan_nama" id="field-perwakilan_nama" placeholder="Masukan Nama Perwakilan">
                            <div id="error-perwakilan_nama" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-perwakilan_hp">No. Handphone</label>
                            <input type="text" class="form-control" name="perwakilan_hp" id="field-perwakilan_hp" placeholder="Masukan No. Handphone">
                            <div id="error-perwakilan_hp" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ asset('assets/js/pages/mitra/supplier-form.js') }}"></script>
@endpush
