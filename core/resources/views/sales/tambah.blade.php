@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection


@section('content')
<div class="content">
    <form id="form-sales" onsubmit="return false;">
        <div class="content-heading pt-0 mb-3">
            Tambah sales
            <button type="submit" class="btn btn-secondary float-right mr-5">
                <i class="si si-paper-plane mr-1"></i>
                Simpan Sales
            </button>
        </div>
        <div class="block">
            <div class="block-content pb-15">
                <div class="row px-0">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="field-nama">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" id="field-nama" placeholder="Masukan Nama Lengkap">
                            <div id="error-nama" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-telp">No. Telepon</label>
                            <input type="text" class="form-control" id="field-telp" name="telp" placeholder="Masukan No Telepon">
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
                            <label for="field-ktp">No KTP</label>
                            <input type="text" class="form-control" name="ktp" id="field-ktp" placeholder="Masukan No. KTP">
                            <div id="error-ktp" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-hp">No. Handphone</label>
                            <input type="text" class="form-control" name="hp" id="field-hp" placeholder="Masukan No. Handphone">
                            <div id="error-hp" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-email">Alamat Email</label>
                            <input type="text" class="form-control" name="email" id="field-email" placeholder="Masukan Alamat Email">
                            <div id="error-email" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-keterangan">Keterangan</label>
                            <textarea class="form-control" id="field-keterangan" name="keterangan" placeholder="Masukan Keterangan Tambahan" rows="4"></textarea>
                            <div id="error-keterangan" class="invalid-feedback"></div>
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
<script src="{{ asset('assets/js/pages/sales-form.js') }}"></script>
@endpush
