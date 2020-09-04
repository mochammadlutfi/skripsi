@extends('layouts.master')

@section('styles')
<style>
    #list-pengguna_filter {
        display: none;
    }

</style>
@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-3 mb-3">
        <a href="{{ route('pengguna.tambah') }}" class="btn btn-secondary float-right mr-5"><i class="si si-plus mr-1"></i>Tambah Pengguna</a>
        Kelola Pengguna
    </div>
    <div class="block">
        <div class="block-content bg-body-light">
            <!-- Search -->
            <div class="form-group">
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
            <table class="table table-hover table-vcenter mb-0" id="list-pengguna">
                <thead>
                    <tr>
                        <th class="font-weight-bold">Nama</th>
                        <th class="font-weight-bold">Username</th>
                        <th class="font-weight-bold">Jabatan</th>
                        <th class="font-weight-bold">Kontak</th>
                        <th class="font-weight-bold">Alamat</th>
                        <th class="font-weight-bold" width="15%"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script>
$(function () {
    oTable = $('#list-pengguna').DataTable({
        processing: true,
        serverSide: true,
        ajax: laroute.route('mitra.pengguna'),
        columns: [{
                data: 'nama',
                name: 'nama'
            },
            {
                data: 'username',
                name: 'username'
            },
            {
                data: 'jabatan',
                name: 'jabatan'
            },
            {
                data: 'kontak',
                name: 'kontak'
            },
            {
                data: 'alamat',
                name: 'alamat'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });

    $('#search_box').keyup(function () {
        oTable.search($(this).val()).draw();
    });
});
</script>
@endpush
