@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
<style>
    #produk_nav{
        display: table;
        height: 35px;
    }
    #produk_nav span {
        vertical-align:middle;
        display: table-cell;
    }
</style>
@endsection

@section('content')
<div class="content">
    <form id="form-returbeli" method="post" onsubmit="return false;">
        <input type="hidden" id="tipe_transaksi" value="returbeli">
        <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
        @csrf
        <div class="content-heading pt-0 mb-3">
            Tambah Retur Pembelian
            <button type="submit" class="btn btn-secondary float-right mr-5">
                <i class="si si-paper-plane mr-1"></i>
                Simpan Retur Pembelian
            </button>
        </div>
        <div class="block">
            <div class="block-content pb-15">
                <div class="row mb-5">
                    <div class="col-6">
                        <table style="width:100%">
                            <tr>
                                <td width="25%"><b>Supplier</b></td>
                                <td>: {{ $transaksi->supplier->nama }}</td>
                            </tr>
                            <tr>
                                <td><b>Alamat</b></td>
                                <td>: <?= get_daerah($transaksi->supplier->daerah_id); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table style="width:100%">
                            <tr>
                                <td width="25%"><b>No. Faktur</b></td>
                                <td>: {{ $transaksi->invoice_no }}</td>
                            </tr>
                            <tr>
                                <td><b>Tanggal Transaksi</b></td>
                                <td>: {{ get_tgl($transaksi->tgl_transaksi) }}</td>
                            </tr>
                            <tr>
                                <td><b>Status Pembayaran</b></td>
                                <td>: <?= get_bayar_status($transaksi->bayar_status); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-vcenter" id="retur_table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga Satuan<small class="text-white">(Rp)</small></th>
                                    <th>Kwantitas Pembelian</th>
                                    <th>Kwantitas Retur</th>
                                    <th>Jumlah Retur<small class="text-white">(Rp)</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian as $d)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="retur[{{ $d->id }}][pembelian_id]" value="{{ $d->id }}"  class="row_pembelian_id">
                                            <input type="hidden" class="hrg_pokok" name="retur[{{ $d->id }}][harga]" value="{{ round($d->hrg_beli,0) }}">
                                            {{ get_namaProduk($d->variasi->produk->nama, $d->variasi->nama) }}
                                        </td>
                                        <td>
                                            Rp {{ number_format($d->hrg_beli,0,",",".") }}
                                        </td>
                                        <td>
                                            {{ $d->quantity }} {{ $d->variasi->satuan->nama }}
                                        </td>
                                        <td>
                                            <div class="input-group input-number">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-secondary quantity-down">
                                                        <i class="fa fa-minus text-danger"></i>
                                                    </button>
                                                </div>
                                                <input type="number" class="form-control jumlah_beli" name="retur[{{ $d->id }}][jumlah]" value="0" min="1" max="{{ $d->quantity }}" data-max="{{ $d->quantity }}"  data-min="0">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-secondary quantity-up">
                                                        <i class="fa fa-plus text-success"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="row_subtotal display_currency" data-currency_symbol="true">0</span>
                                            <input type="hidden" class="form-control row_subtotal_hidden" name="retur[{{ $d->id }}][total]" value="0">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-warning">
                                    <td colspan="3" class="font-w700 text-uppercase text-right">Total Retur</td>
                                    <td colspan="2" class="font-w700 text-right">
                                        <span class="display_currency total_subtotal"></span>
                                        <input type="hidden" id="total_subtotal_input" value="0"  name="sub_total">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <input type="hidden" id="row_count" value="0">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@include('produk.include.modal')

@include('produk.include.modal_variasi')
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/mitra/returbeli-form.js') }}"></script>
<script src="{{ asset('assets/js/pages/produk-modal.js') }}"></script>
<script>
</script>
@endpush
