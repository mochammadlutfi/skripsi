@extends('layouts.master')

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Detail Retur Pembelian
        @role('Kepala Gudang')
        <a href="{{ route('returbeli.edit', $retur->id) }}" class="btn btn-secondary float-right mr-5">
            <i class="si si-note mr-1"></i>
            Ubah
        </a>
        <button type="submit" class="btn btn-secondary float-right mr-5" onclick="hapus({{ $retur->id }})">
            <i class="si si-printer mr-1"></i>
            Hapus
        </button>
        @endrole
    </div>
    <div class="block">
        <div class="block-content">
            <!-- Invoice Info -->
            <div class="row mb-20">
                <!-- Company Info -->
                <div class="col-6">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{ asset('assets/img/logo/logo_big.png') }}" width="100%">
                        </div>
                        <div class="col-9 pt-3">
                            <p class="h5 mb-1">CV. Cihanjuang Mandiri</p>
                            <address>
                                Jl. Cihanjuang Babut Girang, Kel Cibabat<br>
                                Kec. Cimahi Utara, Kota Cimahi, Jawa Barat 40513, Indonesia
                            </address>
                        </div>
                    </div>
                </div>
                <!-- END Company Info -->

                <!-- Client Info -->
                <div class="col-4 text-right">
                    <h5 class="font-w700 mb-0">Detail Retur Pembelian</h5>
                </div>
                <!-- END Client Info -->
            </div>
            <!-- END Invoice Info -->
            <div class="row mb-5">
                <div class="col-6">
                    <table style="width:100%">
                        <tr>
                            <td width="25%"><b>Supplier</b></td>
                            <td>: {{ $transaksi->supplier->nama }}</td>
                        </tr>
                        <tr>
                            <td><b>Alamat</b></td>
                            <td>: <?= get_daerah($transaksi->supplier->daerah_id); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table style="width:100%">
                        <tr>
                            <td width="25%"><b>No. Faktur</b></td>
                            <td>: {{ $retur->invoice_no }}</td>
                        </tr>
                        <tr>
                            <td><b>Tanggal Transaksi</b></td>
                            <td>: {{ get_tgl($retur->tgl_transaksi) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- Table -->

            <!-- Table -->

            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h5 class="mb-5">Produk Retur:</h5>
                </div>
                <div class="col-sm-12">

                    <table class="table bg-gray-light">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>Produk</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-center">Harga Beli</th>
                                <th class="text-center">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($transaksi->pembelian as $item)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td>
                                    <div class="text-muted">{{ get_namaProduk($item->variasi->produk->nama, $item->variasi->nama) }}</div>
                                </td>
                                <td>
                                    <div class="text-muted">{{ $item->variasi->satuan->nama }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-primary">{{ $item->qty_retur }}</span>
                                </td>
                                <td class="text-center">Rp {{ number_format($item->hrg_beli,0,",",".") }}</td>
                                <td class="text-center">Rp {{ number_format($item->hrg_beli*$item->qty_retur,0,",",".") }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-warning">
                                <td colspan="4" class="font-w700 text-uppercase text-right">Total Retur</td>
                                <td colspan="2" class="font-w700 text-center">
                                    Rp {{ number_format($retur->jumlah_pengembalian,0,",",".") }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script>
function hapus(id) {
    Swal.fire({
        title: "Anda Yakin?",
        text: 'Data yang dihapus tidak akan bisa dikembalikan!',
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Tidak, Batalkan!',
        reverseButtons: true,
        allowOutsideClick: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#af1310',
    })
    .then((result) => {
        if (result.value) {
        $.ajax({
            url: laroute.route('returbeli.hapus', { id: id }),
            type: "GET",
            dataType: "JSON",
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('assets/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function(data) {
                Swal.fire({
                    title: "Berhasil",
                    text: 'Retur Pembelian Berhasil Dihapus!',
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'success'
                });
                window.setTimeout(function () {
                    location.href = laroute.route('returbeli')
                }, 1000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });
        } else {
            window.setTimeout(function(){
                location.reload();
            } ,1500);
        }
    });
}

</script>
@endpush
