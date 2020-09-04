@if($data->total() > 0)
    @foreach($data as $d)
    <tbody class="data_transaksi">
        <tr data-transaksi_id="{{$d->id }}">
            <td>
                {{ get_tgl($d->tgl_transaksi) }}
            </td>
            <td>
                {{ ucwords($d->supplier->nama) }}
            </td>
            <td>
                <?= get_bayar_status($d->bayar_status); ?>
            </td>
            <td>
                Rp <span class="display_currency">{{ $d->final_total }}</span>
            </td>
            <td>

            </td>
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
