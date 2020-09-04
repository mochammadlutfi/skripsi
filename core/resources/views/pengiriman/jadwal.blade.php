@extends('layouts.master')

@section('styles')

<link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Jadwal Pengiriman {{ $tgl_kirim}}
        <button type="button" id="konfirmasi" class="btn btn-secondary float-right mr-5"><i class="si si-check mr-1"></i>Konfirmasi Pengiriman</a>
        <button type="button" id="tunda" class="btn btn-secondary float-right mr-5"><i class="si si-close mr-1"></i>Batalkan Pengiriman</a>
    </div>
    <div class="block">
        <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#pengiriman-normal">Jadwal Pengiriman</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#pengiriman-bermasalah"><span class="badge badge-primary badge-pill mr-1">{{ $tidak_dikirim->count() }}</span> Pengiriman Bermasalah</a>
            </li>
        </ul>
        <div class="block-content tab-content">
            <div class="tab-pane active" id="pengiriman-normal" role="tabpanel">
                <table class="table bg-gray-light v-center">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="50px"><input type="checkbox" id="master"></th>
                            <th class="font-weight-bold">No. Faktur</th>
                            <th class="font-weight-bold">Pelanggan</th>
                            <th class="font-weight-bold">Total Beban</th>
                            <th class="font-weight-bold">Rute</th>
                            <th class="font-weight-bold">Kendaraan</th>
                            <th class="font-weight-bold">Status</th>
                            <th class="font-weight-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rowid = 0;
                            $rowspan = 0;
                        @endphp
                        @forelse($jadwal as $data => $d)
                        @php
                            $rowid += 1
                        @endphp
                            <tr class="border-top">
                                <td><input type="checkbox" class="sub_chk" data-id="{{$d->id}}"></td>
                                <td>
                                    {{ $d->transaksi->invoice_no }}
                                </td>
                                <td>
                                    {{ $d->pelanggan->nama }}
                                </td>
                                <td>
                                    {{ $d->beban }}
                                </td>
                                <td>
                                    {{ $d->rute }}
                                </td>
                                <td>
                                    {{ $d->kendaraan->tipe }}
                                    {{ $d->kendaraan->no_polisi }}
                                </td>
                                <td>
                                    <?= get_pengiriman_status($d->status); ?>
                                </td>
                                <td>
                                    <button type="button" onClick="perbaiki({{ $d->id }})" class="btn btn-secondary btn-sm"><i class="si si-note mr-1"></i> Ubah</button>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <img src="{{ asset('assets/img/placeholder/data_not_found.png') }}">
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="pengiriman-bermasalah" role="tabpanel">
                <table class="table bg-gray-light">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="font-weight-bold">No. Faktur</th>
                            <th class="font-weight-bold">Pelanggan</th>
                            <th class="font-weight-bold">Total Beban</th>
                            <th class="font-weight-bold">Keterangan</th>
                            <th class="font-weight-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tidak_dikirim as $t)
                            <tr class="border-top">
                                <td>
                                    {{ $t->transaksi->invoice_no }}
                                </td>
                                <td>
                                    {{ $t->pelanggan->nama }}
                                </td>
                                <td>
                                    {{ $t->beban }}
                                </td>
                                <td>
                                    {{ $t->keterangan }}
                                </td>
                                <td>
                                    <button type="button" onClick="perbaiki({{ $t->id }})" class="btn btn-secondary btn-sm"><i class="si si-note mr-1"></i> Ubah</button>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <img src="{{ asset('assets/img/placeholder/data_not_found.png') }}">
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal_form"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title modal-title">Ubah Pengiriman</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="form-pengiriman" onsubmit="return false;">
                        <input type="hidden" name="pengiriman_id" value="">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="field-tgl_kirim">Tanggal Pengiriman</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control tgl_kirim" id="field-tgl_kirim" name="tgl_kirim" placeholder="Tanggal Pengiriman" value="{{ $tgl_kirim }}">
                                <div class="text-danger" id="error-tgl_kirim"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-alt-primary btn-block"><i class="si si-check mr-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-perbaikan"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title modal-title">Ubah Pengiriman</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="form-perbaikan" onsubmit="return false;">
                        <input type="hidden" id="count" value="1">
                        <input type="hidden" name="pengiriman_id" value="">
                        <input type="hidden" name="beban" value="">
                        <table class="table border-bottom" id="tabel-pengiriman">
                            <thead>
                                <tr>
                                    <td>
                                        Pengiriman Utama
                                        <button type="button" class="btn btn-alt-danger float-right" id="btn-bagi"><i class="si si-plus mr-1"></i>Bagi 2</button>
                                        <button type="button" class="btn btn-alt-primary float-right mr-1" id="btn-hapus"><i class="si si-close mr-1"></i>Gabungkan</button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="pengiriman[0][id]" value="">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="field-beban">Volume Pengiriman</label>
                                            <div class="col-lg-8">
                                                <input type="number" class="form-control input_beban" name="pengiriman[0][volume]" step=".01">
                                                <div class="text-danger" id="error-beban"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="field-tgl_kirim">Tanggal Pengiriman</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control tgl_kirim" id="field-tgl_kirim" name="pengiriman[0][tgl_kirim]" placeholder="Tanggal Pengiriman">
                                                <div class="text-danger" id="error-tgl_kirim"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-alt-primary btn-block"><i class="si si-check mr-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/pengiriman-jadwal.js') }}"></script>
@endpush
