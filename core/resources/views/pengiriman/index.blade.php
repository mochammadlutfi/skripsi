@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
<style>
    #list-pengiriman_filter {
        display: none;
    }
</style>
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Daftar Pengiriman {{ \Carbon\Carbon::now()->format('F Y') }}
    </div>
    <div class="block">
        <div class="block-content bg-body-light">
            <!-- Search -->
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="tgl_pengiriman" placeholder="Bulan Pengiriman" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Search -->
        </div>
        <div class="block-content pb-15">
            <table class="table table-hover table-vcenter mb-0" id="list-pengiriman">
                <thead>
                    <tr>
                        <th class="font-weight-bold">Tanggal</th>
                        <th class="font-weight-bold">Transaksi</th>
                        <th class="font-weight-bold">Volume</th>
                        <th class="font-weight-bold">Rute</th>
                        <th class="font-weight-bold">Bermasalah</th>
                        <th class="font-weight-bold"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}"></script>
<script>

$(document).ready(function () {
    $("#tgl_pengiriman").datepicker({
        setDate: new Date(),
        format: "MM-yyyy",
        dayViewHeaderFormat : 'MMMM YYYY',
        viewMode: "months",
        minViewMode: "months",
        language: "id",
    });
});

$(function () {
    oTable = $('#list-pengiriman').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: laroute.route('pengiriman'),
            data: function (d) {
                d.tgl_kirim = $('#tgl_pengiriman').val()
            }
        },
        columns: [{
                data: 'tgl',
                name: 'tgl'
            },
            {
                data: 'transaksi',
                name: 'transaksi'
            },
            {
                data: 'beban',
                name: 'beban'
            },
            {
                data: 'rute',
                name: 'rute'
            },
            {
                data: 'bermasalah',
                name: 'bermasalah'
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

    $("#tgl_pengiriman").change(function(){
        oTable.draw();
    });
});
</script>
@endpush
