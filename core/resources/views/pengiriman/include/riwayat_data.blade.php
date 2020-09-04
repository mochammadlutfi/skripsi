@if($data->total() > 0)
    @foreach($data as $d)
    <tbody class="data_transaksi">
        <tr data-transaksi_id="{{ $d->id }} ">
            <td>
                {{ $d->tgl_kirim }}
            </td>
            <td>
                {{ $d->transaksi_id }}
            </td>
            <td>
               {{ ucwords($d->pelanggan->nama) }}
            </td>
            <td>
                {{ $d->beban }}
            </td>
            <td>
                {{ $d->kendaraan->tipe }}
            </td>
            <td>
                <?= get_pengiriman_status($d->status); ?>
            </td>
            <td>

            </td>
        </tr>
    </tbody>
    @endforeach
    @if ($data->hasMorePages())
    <tfoot>
        <tr>
            <td colspan="4">
            {{-- Menampilkan {!! $data->total() !!} --}}
            </td>
            <td colspan="4" class="text-right">
            {!! $data->links() !!}
            </td>
        </tr>
    </tfoot>
    @endif
@else
<tbody>
    <tr>
        <td colspan="8" class="text-center">
            <img src="{{ asset('assets/img/placeholder/data_not_found.png') }}">
        </td>
    </tr>
</tbody>
@endif
