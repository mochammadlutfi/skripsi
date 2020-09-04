@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection


@section('content')
<div class="content">
    <form id="form-pengguna" onsubmit="return false;">
        <input type="hidden" name="user_id" value="{{ $data->id }}">
    <div class="content-heading pt-0 mb-3">
        Ubah Data Pengguna
        <button type="submit" class="btn btn-secondary float-right mr-5">
            <i class="si si-paper-plane mr-1"></i>
            Simpan Pengguna
        </button>
    </div>
    <div class="block block-rounded">
        <div class="block-content pb-15">
            <div class="row px-0">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="field-nama">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" id="field-nama" placeholder="Masukan Nama Lengkap" value="{{ $data->nama }}">
                        <div id="error-nama" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="field-hp">No. Handphone</label>
                        <input type="text" class="form-control" name="hp" id="field-hp" placeholder="Masukan No. Handphone" value="{{ $data->hp }}">
                        <div id="error-hp" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="field-email">Alamat Email</label>
                        <input type="text" class="form-control" name="email" id="field-email" placeholder="Masukan Alamat Email" value="{{ $data->email }}">
                        <div id="error-email" class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="field-ktp">No KTP</label>
                        <input type="text" class="form-control" name="ktp" id="field-ktp" placeholder="Masukan No. KTP" value="{{ $data->ktp }}">
                        <div id="error-ktp" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="field-username">Username</label>
                        <input type="text" class="form-control" name="username" id="field-username" placeholder="Masukan Username" value="{{ $data->username }}">
                        <div id="error-username" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="field-jabatan">Jabatan</label>
                        <select class="form-control" name="jabatan" id="field-jabatan">
                            <option value="">Pilih</option>
                            <option value="General Manager" @if($data->jabatan == 'General Manager') selected="selected" @endif>General Manager</option>
                            <option value="Merchandiser" @if($data->jabatan == 'Merchandiser') selected="selected" @endif>Merchandiser</option>
                            <option value="Kepala Gudang" @if($data->jabatan == 'Kepala Gudang') selected="selected" @endif>Kepala Gudang</option>
                            <option value="Admin" @if($data->jabatan == 'Admin') selected="selected" @endif>Admin</option>
                        </select>
                        <div id="error-jabatan" class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group">
                        <label for="field-wilayah">Wilayah</label>
                        <select name="wilayah" id="field-wilayah" style="width:100%" data-id="{{ $data->id }}" data-text="{{ $wilayah }}"></select>
                        <div id="error-wilayah" class="text-danger font-size-sm"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="field-kd_pos">Kode Pos</label>
                        <input type="text" class="form-control" id="field-kd_pos" name="kd_pos" placeholder="Kode Pos" value="{{ $data->kd_pos }}">
                        <div id="error-kd_pos" class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="field-alamat">Alamat</label>
                <textarea class="form-control" id="field-alamat" name="alamat" placeholder="Masukan Alamat Lengkap" rows="4">{{ $data->alamat }}</textarea>
                <div id="error-alamat" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    </form>
</div>
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ asset('assets/js/pages/pengguna-edit.js') }}"></script>
@endpush
