@forelse($data as $d)
<tbody class="@if($d->variasi > 1) js-table-sections-header @endif data">
    <tr>
        <td class="text-center">
            @if($d->variasi > 1)<i class="fa fa-angle-right"></i>@endif
        </td>
        <td>
            @if($d->foto == '')
            <img src="{{ asset('assets/img/placeholder/product.png') }}" width="40px">
            @else
            <img src="{{ asset($d->foto) }}" width="40px">
            @endif
        </td>
        <td class="font-w600">{{ $d->nama }}</td>
        <td class="font-w600">{{ $d->kategori->nama }}</td>
        <td>
            @if($d->variasi > 1)
            <span class="btn btn-alt-primary btn-sm">{{ $d->produk_variasi->count() }} Harga</span>
            @else
            Rp <span class="display_currency">{{ $d->produk_variasi[0]['hrg_jual'] }}</span>
            @endif
        </td>
        <td>{{ $d->pvd->sum('qty_tersedia') }}</td>
        <td class="font-w600">{{ $d->merk->nama }}</td>
        <td>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">Aksi</button>
                <div class="dropdown-menu" style="">
                    <a class="dropdown-item" href="{{ route('produk.edit', $d->id) }}">
                        <i class="si si-note mr-1"></i>Ubah Produk
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="hapus({{ $d->id }})">
                        <i class="si si-trash mr-1"></i>Hapus Produk
                    </a>
                </div>
            </div>
        </td>
    </tr>
</tbody>
@if($d->variasi > 1)
<tbody class="data">
    <tr>
        <th></th>
        <th></th>
        <th class="font-weight-bold" colspan="2">Nama Variasi</th>
        <th class="font-weight-bold">Harga Jual</th>
        <th class="font-weight-bold" colspan="2">Jumlah Stok</th>
        <th></th>
    </tr>
    @foreach($d->produk_variasi as $pv)
    <tr>
        <td></td>
        <td></td>
        <td class="font-w600" colspan="2">{{ $pv->nama }}</td>
        <td class="font-size-sm">Rp <span class="display_currency">{{ $pv->hrg_jual }}</span></td>
        {{-- <td>{{ getStokPerVariasi($pv->id) }}</td> --}}
        <td></td>
        <td></td>
    </tr>
    @endforeach
</tbody>
@endif
@empty
<tbody>
    <tr>
        <td colspan="8" class="text-center">
            <img src="{{ asset('assets/img/placeholder/data_not_found.png') }}">
        </td>
    </tr>
</tbody>
@endforelse
