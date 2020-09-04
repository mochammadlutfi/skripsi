@if($data->total() > 0)
    @foreach($data as $d)
    <tbody class="data_transaksi">
        <tr data-transaksi_id="{{ $d->id }} ">
            <td>
                {{ get_tgl($d->tgl_transaksi) }}
            </td>
            <td>
                {{ $d->invoice_no }}
            </td>
            <td>
               {{ ucwords($d->pelanggan->nama) }}
            </td>
            <td>
               {{ ucwords($d->sales->nama) }}
            </td>
            <td>
                <?= get_status($d->status); ?>
            </td>
            <td>
                <?= get_bayar_status($d->bayar_status); ?>
            </td>
            <td>
                Rp <span class="display_currency">{{ $d->final_total }}</span>
            </td>
            {{-- <td>
                {{ ucWords($d->user->nama) }}
            </td> --}}
        </tr>
    </tbody>
    @endforeach
@else
<tbody>
    <tr>
        <td colspan="8" class="text-center">
            <img src="{{ asset('assets/img/placeholder/data_not_found.png') }}">
        </td>
    </tr>
</tbody>
@endif
