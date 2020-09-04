<?php $row_count = 0; ?>
@foreach($beli_detail as $d)
<?php $row_count += 1; ?>
<tr>
    <td>
        <input type="hidden" class="form-control" name="pembelian[{{ $row_count }}][kd_barang]" value="{{ $d->produknya->kd_barang }}">
        {{ $d->produknya->kd_barang }} - {{ $d->produknya->nama }}
    </td>
    <td>{{ $d->produknya->satuan->nama }}</td>
    <td>
        Rp {{ number_format($d->produknya->hrg_pokok,0,",",".") }}
        <input type="hidden" class="form-control hrg_pokok" name="pembelian[{{ $row_count }}][harga]" value="{{ $d->produknya->hrg_pokok }}">
    </td>
    <td>
        <input type="number" class="form-control jumlah_beli form-control-sm" name="pembelian[{{ $row_count }}][jumlah]" value="1">
    </td>
    <td>
        <input type="number" class="form-control form-control-sm inline_diskon" name="pembelian[{{ $row_count }}][diskon]" value="0">
    </td>
    <td>
        Rp <span class="row_subtotal">{{ number_format($d->produknya->hrg_pokok,0,",",".") }}</span>
        <input type="hidden" class="form-control row_subtotal_hidden" name="pembelian[{{ $row_count }}][total]">
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-danger hapus_brg" id="#hapus-{{ $row_count }}">
            <i class="si si-trash"></i>
        </button>
    </td>
</tr>
@endforeach
<input type="hidden" id="row_count" value="{{ $row_count }}">
