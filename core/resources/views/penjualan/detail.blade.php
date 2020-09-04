@extends('layouts.master')

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Detail Penjualan
        @role('Admin')
        <a href="{{ route('penjualan.edit', $transaksi->id) }}" class="btn btn-secondary float-right mr-5">
            <i class="si si-note mr-1"></i>
            Ubah
        </a>
        <button type="submit" class="btn btn-secondary float-right mr-5">
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
                    <h5 class="font-w700 mb-0">Faktur Penjualan</h5>
                </div>
                <!-- END Client Info -->
            </div>
            <!-- END Invoice Info -->
            <div class="row mb-5">
                <div class="col-6">
                    <table style="width:100%">
                        <tr>
                            <td width="25%"><b>Pelanggan</b></td>
                            <td>: {{ $transaksi->pelanggan->nama }}</td>
                        </tr>
                        <tr>
                            <td><b>Alamat</b></td>
                            <td>: <?= get_daerah($transaksi->pelanggan->daerah_id); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table style="width:100%">
                        <tr>
                            <td width="25%"><b>No. Faktur</b></td>
                            <td>: {{ $transaksi->invoice_no }}</td>
                        </tr>
                        <tr>
                            <td><b>Tanggal Transaksi</b></td>
                            <td>: {{ get_tgl($transaksi->tgl_transaksi) }}</td>
                        </tr>
                        <tr>
                            <td><b>Status Pembayaran</b></td>
                            <td>: <?= get_bayar_status($transaksi->bayar_status); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- Table -->

            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h5 class="mb-5">Rincian Produk:</h5>
                </div>
                <div class="col-sm-12">
                    <table class="table bg-gray-light">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>Produk</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-center">Harga Satuan</th>
                                <th class="text-center">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($produk as $item)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td>
                                    <div class="text-muted">{{ get_namaProduk($item->variasi->produk->nama, $item->variasi->nama) }}</div>
                                </td>
                                <td>
                                    <div class="text-muted">{{ $item->variasi->satuan->nama }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-primary">{{ $item->quantity }}</span>
                                </td>
                                <td class="text-center">Rp {{ number_format($item->hrg_jual,0,",",".") }}</td>
                                <td class="text-center">Rp {{ number_format($item->sub_total,0,",",".") }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END Produk -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <h5 class="mb-5">Pembayaran:</h5>
                </div>
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table bg-gray-light">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>No Ref</th>
                                    <th>Jumlah</th>
                                    <th>Catatan Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach($pembayaran as $p)
                                <tr>
                                    <td>
                                        {{ $no++ }}
                                    </td>
                                    <td>
                                        {{ get_tgl($p->created_at) }}
                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <span class="display_currency" data-currency_symbol="true">{{ $p->jumlah }}</span></td>
                                    <td> --
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table bg-gray-light">
                            <tbody>
                                <tr>
                                    <th>Total: </th>
                                    <td></td>
                                    <td>
                                        <span class="display_currency pull-right" data-currency_symbol="true"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Diskon:</th>
                                    <td class="text-right">
                                        <b>(-)</b>
                                        <span class="display_currency pull-right" data-currency_symbol="true">{{ $transaksi->diskon_nilai }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Tagihan: </th>
                                    <td></td>
                                    <td>
                                        <span class="display_currency pull-right" data-currency_symbol="true">{{ $transaksi->final_total }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Dibayar:</th>
                                    <td></td>
                                    <td>
                                        <span class="display_currency pull-right" data-currency_symbol="true">{{ $transaksi->bayaran->sum('jumlah') }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END Table -->

            <!-- Footer -->
            {{-- <p class="text-muted text-center">Thank you very much for doing business with us. We look forward to working with you again!</p> --}}
            <!-- END Footer -->
        </div>
    </div>
</div>
@stop
@push('scripts')
<script>
</script>
@endpush
