@if(Cart::session('pembelian')->isEmpty())
<tr>
    <td colspan="6" class="text-center border-0">
        <img src="{{ asset('assets/img/placeholder/cart_empty.png') }}">
    </td>
</tr>
@else
@foreach(Cart::session('pembelian')->getContent()->sort() as $d)
<tr>
    <td>
        @if(!empty($d->attributes['pembelian_id']))
            <input type="hidden" name="pembelian[{{ $d->id }}][pembelian_id]" value="{{ $d->attributes['pembelian_id'] }}">
        @endif
        <input type="hidden" name="pembelian[{{ $d->id }}][produk_id]" value="{{ $d->attributes['produk_id'] }}">
        <input type="hidden" name="pembelian[{{ $d->id }}][variasi_id]" value="{{ $d->id }}" class="row_variasi_id">
        <input type="hidden" name="pembelian[{{ $d->id }}][qty]" class="row_kuantitas" value="{{ $d->quantity }}">
        {{ $d->name }}
    </td>
    <td>
        {{ $d->attributes['satuan_nama'] }}
    </td>
    <td>
        <div class="input-group input-number">
            <div class="input-group-prepend">
                <button type="button" class="btn btn-secondary quantity-down">
                    <i class="fa fa-minus text-danger"></i>
                </button>
            </div>
            <input type="number" class="form-control jumlah_beli" name="pembelian[{{ $d->id }}][jumlah]" value="{{ $d->quantity }}" min="1">
            <div class="input-group-append">
                <button type="button" class="btn btn-secondary quantity-up">
                    <i class="fa fa-plus text-success"></i>
                </button>
            </div>
        </div>
    </td>
    <td>
        {{-- Rp {{ number_format($d->price,0,",",".") }} --}}
        <input type="number" class="form-control hrg_pokok" name="pembelian[{{ $d->id }}][harga]" value="{{ round($d->price,0) }}">
    </td>
    <td>
        Rp <span class="row_subtotal">{{ number_format($d->price*$d->quantity,0,",",".") }}</span>
        <input type="hidden" class="form-control row_subtotal_hidden" name="pembelian[{{ $d->id }}][total]" value="{{ $d->price*$d->quantity }}">
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-danger hapus_cart">
            <i class="si si-close"></i>
        </button>
    </td>
</tr>
@endforeach
@endif


