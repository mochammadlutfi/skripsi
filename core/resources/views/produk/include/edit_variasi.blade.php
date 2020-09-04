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
                    <th>Nama Varian</th>
                    <th>Kode Produk</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th width="25%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $row_count = 0; @endphp
                @foreach($variasi as $data)
                <tr>
                    <td>
                        {{-- {{ $data['variasi_nama'] }} --}}
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][nama]" value="{{ $data->nama }}" readonly>
                    </td>
                    <td>
                        {{-- {{ $data['variasi_sku'] }} --}}
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][sku]" value="{{ $data->sku }}" readonly>
                    </td>
                    <td>
                        {{-- <span class="display_currency">{{ $data['variasi_hrg_modal'] }}</span> --}}
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][hrg_modal]" value="{{ $data->hrg_modal }}" readonly>
                    </td>
                    <td>
                        {{-- <span class="display_currency">{{ $data['variasi_hrg_jual'] }}</span> --}}
                        <input type="text" class="form-control" name="variasi[{{ $row_count }}][hrg_jual]" value="{{ $data->hrg_jual }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control" value="@if($data['variasi_stok'] < 0) Tidak punya stok @else {{ $data['variasi_stok'] }} @endif" readonly>
                        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][kelola_stok]" value="{{ $data['kelola_stok'] }}" readonly>
                        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][stok]" value="{{ $data['stok'] }}" readonly>
                        <input type="hidden" class="form-control" name="variasi[{{ $row_count }}][min_stok]" value="{{ $data['min_stok'] }}" readonly>
                        <input type="hidden" name="variasi[{{ $row_count }}][satuan_id]" value="{{ $data['satuan_id'] }}">
                    </td>
                    <td>
                        <button class="btn btn-alt-primary btn-sm" onclick="ubah({{ $row_count }})">
                            <i class="si si-note mr-1"></i>
                            Ubah
                        </button>
                        <button class="btn btn-alt-danger btn-sm btn-hapus">
                            <i class="si si-trash mr-1"></i>
                            Hapus
                        </button>
                    </td>
                </tr>

                @php $row_count + 1; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
