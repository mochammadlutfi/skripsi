<tr>
    <td>
        <div class="font-w600 font-size-md">{{ $pv->nama }}</div>
        <input type="hidden" name="produk[{{ $i }}][produk_id]" value="{{ $pv->produk->id }}">
        <input type="hidden" name="produk[{{ $i }}][produk_nama]" value="{{ $pv->produk->nama }}">
        <input type="hidden" name="produk[{{ $i }}][variasi_id]" value="{{ $pv->id }}" class="variasi_id_modal">
        <input type="hidden" name="produk[{{ $i }}][variasi_nama]" value="{{ $pv->nama }}">
        <input type="hidden" name="produk[{{ $i }}][hrg]" value="{{ $hrg }}">
        <input type="hidden" name="produk[{{ $i }}][kelola_stok]" value="{{ $pv->kelola_stok }}">
        <input type="hidden" name="produk[{{ $i }}][volume]" value="{{ $pv->volume }}">
        @if($pv->kelola_stok == 1)
        <input type="hidden" name="produk[{{ $i }}][satuan_id]" value="{{ $pv->satuan_id }}">
        <input type="hidden" name="produk[{{ $i }}][satuan_nama]" value="{{ $pv->satuan->nama }}">
        @endif
        Rp <span class="display_currency">{{ number_format($hrg,0,",",".") }}</span>
    </td>
    <td width="35%">
        <div class="input-group input-number">
            <div class="input-group-prepend">
                <button type="button" class="btn btn-secondary quantity-down">
                    <i class="fa fa-minus text-danger"></i>
                </button>
            </div>
            @if($tipe == 'penjualan')
                <input type="number" min="0" class="form-control input-number kuantias_modal" @if($pv->kelola_stok == 1) data-max="{{ $pv->detail->qty_tersedia }}"  max="{{ $pv->detail->qty_tersedia }}" @endif min="0" data-min="0" name="produk[{{ $i }}][qty]" value="0">
            @else
                <input type="number" min="0" class="form-control input-number kuantias_modal" min="0" data-min="0" name="produk[{{ $i }}][qty]" value="0">
            @endif
            <div class="input-group-append">
                <button type="button" class="btn btn-secondary quantity-up">
                    <i class="fa fa-plus text-success"></i>
                </button>
            </div>
        </div>
    </td>
</tr>
