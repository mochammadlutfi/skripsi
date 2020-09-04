@extends('layouts.master')

@section('styles')
<style>
    #list-merk_filter {
        display: none;
    }

</style>
@endsection

@section('content')
<div class="content">
    <form id="form-stok_awal" onsubmit="return false;">
        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
        <div class="content-heading pt-3 mb-3">
            Tambahkan Stok Awal Produk
            <button type="submit" class="btn btn-secondary float-right mr-5">
                <i class="si si-paper-plane mr-1"></i>
                Simpan
            </button>
        </div>
        <div class="block">
            <div class="block-content bg-body-light">

            </div>
            <div class="block-content pb-15">
                @if($produk->variasi == 1)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th width="15%">Jumlah</th>
                            <th>Harga Beli Satuan</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($variasi as $v)
                        <tr>
                            <td>
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                {{ $produk->nama }}
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control input_number purchase_quantity input_quantity" name="stok[{{ $v->id }}][qty]" value="0" min="0">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $v->satuan->nama }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_price" name="stok[{{ $v->id }}][hrg_beli]" value="{{ round($v->hrg_modal, 0)}}">
                            </td>
                            <td class="text-right">
                                Rp <span class="display_currency row_subtotal_before_tax">
                                    0
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-warning">
                            <td colspan="3" class="font-w700 text-uppercase text-right">Total Pembelian</td>
                            <td class="font-w700 text-right">
                                <span id="total_subtotal" class="display_currency"></span>
                                <input type="hidden" id="total_subtotal" value=0  name="total_subtotal">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Variasi</th>
                            <th>Kode Produk</th>
                            <th width="15%">Jumlah</th>
                            <th>Harga Beli Satuan</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($variasi as $v)
                        <tr>
                            <td>
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                {{ $v->nama }}
                            </td>
                            <td>{{ $v->sku }}</td>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control input_number purchase_quantity input_quantity" name="stok[{{ $v->id }}][qty]" value="0" min="0">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $v->satuan->nama }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="form-control unit_price" name="stok[{{ $v->id }}][hrg_beli]" value="{{ round($v->hrg_modal, 0)}}">
                            </td>
                            <td class="text-right">
                                Rp <span class="display_currency row_subtotal_before_tax">
                                    0
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-warning">
                            <td colspan="4" class="font-w700 text-uppercase text-right">Total Pembelian</td>
                            <td class="font-w700 text-right">
                                <span id="total_subtotal" class="display_currency"></span>
                                <input type="hidden" id="total_subtotal" value=0  name="total_subtotal">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                @endif
            </div>
        </div>
    </form>
</div>
@stop
@push('scripts')
<script src="{{ asset('assets/js/pages/stok_awal.js') }}"></script>
<script>
</script>
@endpush
