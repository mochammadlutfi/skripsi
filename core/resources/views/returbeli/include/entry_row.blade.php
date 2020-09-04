@if(Cart::session('returbeli')->isEmpty())
<tr>
    <td colspan="6" class="text-center border-0">
        <img src="{{ asset('assets/img/placeholder/cart_empty.png') }}">
    </td>
</tr>
@else
@foreach(Cart::session('returbeli')->getContent()->sort() as $d)
<tr>
    <td><input type="hidden" name="retur[{{ $d->id }}][produk_id]" value="{{ $d->attributes['produk_id'] }}">
        <input type="hidden" name="retur[{{ $d->id }}][variasi_id]" value="{{ $d->id }}" class="row_variasi_id">
        <input type="hidden" name="retur[{{ $d->id }}][qty]" class="row_kuantitas" value="{{ $d->quantity }}">
        {{ $d->name }}
    </td>
    <td>
        <input type="number" class="form-control jumlah_beli form-control-sm" name="retur[{{ $d->id }}][jumlah]" value="{{ $d->quantity }}" min="1">
    </td>
    <td>
        <input type="number" class="form-control hrg_pokok" name="retur[{{ $d->id }}][harga]" value="{{ round($d->price,0) }}">
    </td>
    <td>
        Rp <span class="row_subtotal">{{ number_format($d->price*$d->quantity,0,",",".") }}</span>
        <input type="hidden" class="form-control row_subtotal_hidden" name="retur[{{ $d->id }}][total]" value="{{ $d->price*$d->quantity }}">
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-danger hapus_cart">
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
@endforeach
@endif
