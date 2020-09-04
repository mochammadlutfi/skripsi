@if(Cart::session('penjualan')->isEmpty())
<tr>
    <td colspan="6" class="text-center border-0">
        <img src="{{ asset('assets/img/placeholder/cart_empty.png') }}">
    </td>
</tr>
@else
@foreach(Cart::session('penjualan')->getContent()->sort() as $d)
<tr>
    <td>
        @if(!empty($d->attributes['penjualan_id']))
            <input type="hidden" name="penjualan[{{ $d->id }}][penjualan_id]" value="{{ $d->attributes['penjualan_id'] }}">
        @endif
        <input type="hidden" name="penjualan[{{ $d->id }}][produk_id]" value="{{ $d->attributes['produk_id'] }}">
        <input type="hidden" name="penjualan[{{ $d->id }}][variasi_id]" value="{{ $d->id }}" class="row_variasi_id">
        <input type="hidden" name="penjualan[{{ $d->id }}][qty]" class="row_kuantitas" value="{{ $d->quantity }}">
        <input type="hidden" name="penjualan[{{ $d->id }}][beban]" value="{{ $d->attributes['volume'] }}">
        {{ $d->name }}
    </td>
    <td>
        <input type="number" class="form-control jumlah_beli form-control-sm" name="penjualan[{{ $d->id }}][jumlah]" value="{{ $d->quantity }}" min="1"
        @if(!empty($d->attributes['max_stok']))
        max="{{ $d->attributes['max_stok'] }}"
        @endif>
    </td>
    <td>
        <input type="number" class="form-control hrg_pokok" name="penjualan[{{ $d->id }}][harga]" value="{{ round($d->price,0) }}">
    </td>
    <td>
        Rp <span class="row_subtotal">{{ number_format($d->price*$d->quantity,0,",",".") }}</span>
        <input type="hidden" class="form-control row_subtotal_hidden" name="penjualan[{{ $d->id }}][total]" value="{{ $d->price*$d->quantity }}">
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-danger hapus_cart">
            <i class="si si-close"></i>
        </button>
    </td>
</tr>
@endforeach
@endif


