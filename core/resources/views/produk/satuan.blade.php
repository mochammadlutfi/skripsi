@extends('layouts.master')

@section('styles')
<style>
    #list-satuan_filter {
        display: none;
    }

</style>
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Kelola Satuan Produk
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title" id="form-title">Tambah Satuan Produk</h3>
                </div>
                <div class="block-content">
                    <form id="form-satuan">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label class="col-form-label" for="field-nama">Nama Satuan</label>
                                        <input type="text" class="form-control" id="field-nama" name="nama" placeholder="Masukan Nama Satuan">
                                        <div class="invalid-feedback" id="error-nama">Invalid feedback</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mb-2">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-alt-primary btn-block"><i class="si si-check mr-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="block">
                <div class="block-content bg-body-light p-3">
                    <!-- Search -->
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search_box" placeholder="Masukan Kata Kunci..">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- END Search -->
                </div>
                <div class="block-content pb-15">
                    <table class="table table-hover table-vcenter mb-0" id="list-satuan">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th width="30%">Total Produk</th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- END Default Elements -->
        </div>
    </div>
</div>

<div class="modal" id="modal_form"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title modal-title">Perbaharui Satuan</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="form-update_satuan" onsubmit="return false;">
                        <input type="hidden" name="satuan_id" value="">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nama Satuan</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-upt_nama" class="form-control" name="upt_nama" placeholder="Masukan Nama Satuan">
                                <div class="text-danger" id="error-upt_nama"></div>
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
@stop
@push('scripts')
<script src="{{ asset('assets/js/pages/satuan.js') }}"></script>
@endpush
