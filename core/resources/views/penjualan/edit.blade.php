@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection

@section('content')
<div class="content">
    <form id="form-penjualan" method="post" onsubmit="return false;">
        <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
        @csrf
        <div class="content-heading pt-0 mb-3">
            Edit Penjualan Produk
            <button type="submit" class="btn btn-secondary float-right mr-5">
                <i class="si si-paper-plane mr-1"></i>
                Simpan Penjualan
            </button>
        </div>
        <div class="block">
            <div class="block-content pb-15">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-sales">Sales</label>
                            <select class="form-control" id="field-sales" name="sales" style="width: 100%;" data-id="{{ $transaksi->sales_id }}" data-text="{{ $transaksi->sales->nama }}"></select>
                            <div id="error-sales" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-pelanggan">Pelanggan</label>
                            <select class="form-control" id="field-pelanggan" name="pelanggan" style="width: 100%;"  data-id="{{ $transaksi->pelanggan_id }}" data-text="{{ $transaksi->pelanggan->nama }}"></select>
                            <div id="error-pelanggan" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-tgl_transaksi">Tanggal Transaksi</label>
                            <input type="text" class="form-control" id="field-tgl_transaksi" name="tgl_transaksi" placeholder="Tanggal Transaksi" value="{{ Carbon\Carbon::parse($transaksi->created_at)->format('d-m-Y') }}">
                            <div class="invalid-feedback" id="error-tgl_transaksi">Invalid feedback</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-tgl_pengiriman">Tanggal Pengiriman</label>
                            <input type="text" class="form-control" id="field-tgl_pengiriman" name="tgl_pengiriman" placeholder="Tanggal Pengiriman" value="{{ Carbon\Carbon::parse($transaksi->pengiriman->tgl_kirim)->format('d-m-Y') }}">
                            <div class="invalid-feedback" id="error-tgl_pengiriman">Invalid feedback</div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <img src="{{ asset('assets/img/placeholder/barcode.png') }}">
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="cari_sku" placeholder="Masukan Kode Produk" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <button type="button" id="btn-cari_produk" class="btn btn-alt-primary btn-block">
                        <i class="si si-plus mr-5"></i>
                        Tambahkan Produk
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-vcenter" id="penjualan_table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Produk</th>
                                    <th>Kwantitas</th>
                                    <th>Harga Satuan<small class="text-white">(Rp)</small></th>
                                    <th>Jumlah <small class="text-white">(Rp)</small></th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="table-warning">
                                    <td colspan="3" class="font-w700 text-uppercase text-right">Sub Total</td>
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
                <hr>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label for="field-jenis_diskon">Jenis Diskon</label>
                                <select class="form-control" id="field-jenis_diskon" name="jenis_diskon" placholder="Jenis Diskon">
                                    <option value="">Pilih</option>
                                    <option value="percentage">Persentase</option>
                                    <option value="fixed">Tetap</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label for="field-diskon" id="label_diskon">Nilai Diskon</label>
                                <input type="number" class="form-control" id="field-diskon" name="diskon" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label class="mr-5">Diskon</label>:
                                (-)<div id="discount_calculated_amount">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label for="field-jenis_pembayaran">Jenis Pembayaran</label>
                                <select class="form-control" id="field-jenis_pembayaran" name="jenis_pembayaran" placholder="Jenis Pembayaran">
                                    <option value="tunai">Tunai</option>
                                    <option value="kredit">Kredit</option>
                                </select>
                                <div class="invalid-feedback" id="error-jenis_pembayaran"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label id="jml_bayar">Jumlah Bayar</label>
                                <input type="number" class="form-control payment-amount" id="field-jml_bayar" name="jml_bayar" value="0">
                                <div class="text-danger" id="error-jml_bayar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 ml-auto">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label class="mr-5">Grand Total</label>:
                                <input id="grand_total_hidden" name="final_total" type="hidden" value="">
                                <div id="grand_total">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group" id="tgl_tempo" style="display:none;">
                            <div class="col-lg-12">
                                <label for="field-tgl_tempo">Tanggal Tempo</label>
                                <input type="text" class="form-control" id="field-tgl_tempo" name="tgl_tempo" placeholder="Tanggal Tempo">
                                <div class="invalid-feedback" id="error-tgl_tempo"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 ml-auto">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label class="mr-5" id="title_kembalian">Kembalian</label>
                                <input type="hidden" name="tempo" id="sisa_pembayaran">
                                <div id="kembalian">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label>Catatan Pembayaran</label>
                                <textarea class="form-control" name="note_bayar"></textarea>
                            </div>
                        </div>
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
<script src="{{ asset('assets/js/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/pages/penjualan-edit.js') }}"></script>
@endpush
