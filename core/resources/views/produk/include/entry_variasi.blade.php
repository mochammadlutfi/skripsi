
@foreach($variasi as $d)

@if(!empty($d['id']))
<tr data-variasi_id="{{ $d['id'] }}">
    <td>
        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][variasi_id]" value="{{ $d['id'] }}" readonly>
        <input type="text" class="form-control" name="variasi[{{ $row_count }}][nama]" value="{{ $d['nama'] }}" readonly>
    </td>
@else
<tr>
    <td>
        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][variasi_id]" value="" readonly>
        <input type="text" class="form-control" name="variasi[{{ $row_count }}][nama]" value="{{ $d['nama'] }}" readonly>
    </td>
@endif
    <td>
        <input type="text" class="form-control" name="variasi[{{ $row_count }}][sku]" value="{{ $d['sku'] }}" readonly>
    </td>
    <td>
        <input type="text" class="form-control" name="variasi[{{ $row_count }}][hrg_modal]" value="{{ $d['hrg_modal'] }}" readonly>
    </td>
    <td>
        <input type="text" class="form-control" name="variasi[{{ $row_count }}][hrg_jual]" value="{{ $d['hrg_jual'] }}" readonly>
    </td>
    <td>
        <input type="text" class="form-control" name="variasi[{{ $row_count }}][show_inventaris]" value="@if( $d['kelola_stok'] == '0') Tidak @else Ya  @endif" readonly>
        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][kelola_stok]" value="{{ $d['kelola_stok'] }}" readonly>
        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][min_stok]" value="{{ $d['min_stok'] }}" readonly>
        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][volume]" value="{{ $d['volume'] }}" readonly>
        <input type="hidden" name="variasi[{{ $row_count }}][satuan_id]" value="{{ $d['satuan_id'] }}">
        <input type="hidden" name="variasi[{{ $row_count }}][satuan_nama]" value="{{ $d['satuan_nama'] }}">
    </td>
    <td>
        <button class="btn btn-alt-primary btn-sm" type="button" onclick="ubah({{ $row_count }})">
            <i class="si si-note mr-1"></i>
            Ubah
        </button>
        <button class="btn btn-alt-danger btn-sm btn-hapus" type="button">
            <i class="si si-trash mr-1"></i>
            Hapus
        </button>
    </td>
</tr>
@php $row_count += 1; @endphp
@endforeach
