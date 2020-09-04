
@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection


@section('content')
<div class="content">
    <form id="form-ubah_pw" onsubmit="return false;">
    <div class="content-heading pt-0 mb-3">
        Profil Pengguna
        <button type="submit" class="btn btn-secondary float-right mr-5">
            <i class="si si-paper-plane mr-1"></i>
            Simpan Perubahan
        </button>
    </div>
    <div class="block block-rounded">
        <div class="block-content pb-15">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" >Kata Sandi Lama</label>
                        <div class="col-lg-8">
                            <input type="password" id="field-pw_lama" class="form-control" name="pw_lama"  placeholder="Kata Sandi Lama">
                            <span id="error-pw_lama" class="invalid-feedback"></span>
                        </div>
                    </div>
                        <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Kata Sandi Baru</label>
                        <div class="col-lg-8">
                            <input type="password" id="field-pw_baru" class="form-control" name="pw_baru" placeholder="Kata Sandi Baru">
                            <span id="error-pw_baru" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Konfirmasi Kata Sandi Baru</label>
                        <div class="col-lg-8">
                            <input type="password" id="field-konf_pw_baru" class="form-control" name="konf_pw_baru" placeholder="Konfirmasi Kata Sandi Baru">
                            <span id="error-konf_pw_baru" class="invalid-feedback"></span>
                        </div>
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
<script src="{{ asset('assets/js/pages/ubah_pw.js') }}"></script>
@endpush
