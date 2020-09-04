@extends('mitra.layouts.master')

@section('content')
<div class="content p-15">
    <div class="row mb-15">
		<div class="col-md-12 col-xs-12">
			<div class="btn-group float-right" data-toggle="buttons">
				<label class="btn btn-info active">
    				<input type="radio" name="date-filter"
    				data-start="{{ date('Y-m-d') }}"
    				data-end="{{ date('Y-m-d') }}"
    				checked> Hari Ini
  				</label>
  				<label class="btn btn-info">
    				<input type="radio" name="date-filter"
    				data-start="{{ $date_filters['this_week']['start']}}"
    				data-end="{{ $date_filters['this_week']['end']}}"
    				> Minggu Ini
  				</label>
  				<label class="btn btn-info">
    				<input type="radio" name="date-filter"
    				data-start="{{ $date_filters['this_month']['start']}}"
    				data-end="{{ $date_filters['this_month']['end']}}"
    				> Bulan Ini
  				</label>
            </div>
		</div>
	</div>
    <div class="row invisible" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-6 col-xl-4">
            <div class="block block-link-shadow text-right">
                <div class="block-content block-content-full clearfix">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-bag fa-3x text-body-bg-dark"></i>
                    </div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Penghasilan Kotor</div>
                    <div class="font-size-h3 font-w600" id="penghasilan_kotor"><i class="fas fa-sync fa-spin fa-fw"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <a class="block block-link-shadow text-right" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-envelope-open fa-3x text-body-bg-dark"></i>
                    </div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Penghasilan Bersih</div>
                    <div class="font-size-h3 font-w600 keuntungan_kotor"><i class="fas fa-sync fa-spin fa-fw"></i></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-4">
            <a class="block block-link-shadow text-right" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-users fa-3x text-body-bg-dark"></i>
                    </div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Total Transaksi Penjualan</div>
                    <div class="font-size-h3 font-w600" id="total_transaksi_jual"><i class="fas fa-sync fa-spin fa-fw"></i></div>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>
</div>

@stop
@push('scripts')
<script src="{{ asset('assets/js/pages/mitra/beranda.js') }}"></script>
@endpush
