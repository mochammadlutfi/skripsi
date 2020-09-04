<div class="row border-bottom mx-0 mb-2 sh_variasi">
    <div class="col-lg-8 pl-0">
        <h2 class="content-heading pt-0 mb-0 border-0">Variasi Produk</h2>
    </div>
    <div class="col-lg-4 pr-0">
    <button class="btn btn-alt-primary float-right btnVariasi" type="button"><i class="si si-plus mr-3"></i>Tambah Variasi</button>
    </div>
</div>
<div class="row sh_variasi">
    <div class="col-lg-12">
        <table class="table" id="tbl_variasi">
            <thead>
                <tr>
                    <th>Nama Variasi</th>
                    <th>Kode Produk</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual</th>
                    <th>Kelola Stok</th>
                    <th width="25%">Aksi</th>
                </tr>
            </thead>
            <tbody>
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
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][nama]" value="{{ $d['nama'] }}" readonly>
                    </td>
                @endif
                    <td>
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][sku]" value="{{ $d['sku'] }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][hrg_modal]" value="{{ round($d['hrg_modal'],0) }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][hrg_jual]" value="{{ round($d['hrg_jual'],0) }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][show_inventaris]" value="@if( $d['kelola_stok'] == '0') Tidak @else Ya  @endif" readonly>
                        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][kelola_stok]" value="{{ $d['kelola_stok'] }}" readonly>
                        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][min_stok]" value="{{ $d['min_stok'] }}" readonly>
                        <input type="hidden" name="variasi[{{ $row_count }}][satuan_id]" value="{{ $d['satuan_id'] }}">
                        <input type="hidden" name="variasi[{{ $row_count }}][satuan_nama]" value="{{ $d['satuan_nama'] }}">
                        <input type="hidden" name="variasi[{{ $row_count }}][volume]" value="{{ $d['volume'] }}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-alt-primary btn-sm btn-ubah" onclick="ubah({{ $row_count }})">
                            <i class="si si-note mr-1"></i>
                            Ubah
                        </button>
                        <button type="button" class="btn btn-alt-danger btn-sm btn-hapus">
                            <i class="si si-trash mr-1"></i>
                            Hapus
                        </button>
                    </td>
                </tr>
                @php $row_count += 1; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
