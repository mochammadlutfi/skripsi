<?php

namespace App\Http\Controllers;


use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use App\Charts\PenjualanChart;
use App\Charts\PengirimanChart;
use App\Charts\PembelianChart;
use Carbon\Carbon;

class BerandaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->hasRole('Admin'))
        {
            return $this->admin();
        }else if( auth()->user()->hasRole('General Manager'))
        {
            return $this->admin();

        }else if( auth()->user()->hasRole('Merchandiser'))
        {
            return $this->admin();
        }else if( auth()->user()->hasRole('Kepala Gudang'))
        {
            return $this->gudang();

        }else if( auth()->user()->hasRole('Kepala Delivery'))
        {
            return $this->delivery();

        }
    }


    public function admin()
    {
        $total_produk = Produk::all()->count();

        $now = Carbon::now();
        $start = Carbon::now()->startofWeek()->subDays(1);
        $end = Carbon::now()->endofWeek();
        $penjualan_minggu = Transaksi::where('tipe', 'jual')->whereBetween('tgl_transaksi', [$now->startofWeek()->format('Y-m-d'), $now->endofWeek()->format('Y-m-d')])->get();
        $penjualan_bulan = Transaksi::where('tipe', 'jual')->whereBetween('tgl_transaksi', [$now->startofMonth()->format('Y-m-d'), $now->endofMonth()->format('Y-m-d')])->get();
        $data = array(
            'total_produk' => $total_produk,
            'total_penjualan' => $penjualan_minggu->count()
        );

        $tgl = array();
        $jual = array();
        for($i = 1; $i <= 7 ; $i++)
        {
            $hari = $start->addDays();
            $tgl[] = $hari->isoFormat('dddd'). ' - '. $hari->isoFormat('D MMMM YYYY');
            $transaksi = Transaksi::where('tipe', 'jual')->whereDate('tgl_transaksi', $hari->format('Y-m-d'))->get();
            $jual[] = $transaksi->count();
        }

        $chartPenjualan = new PenjualanChart;
        // dd($chartPenjualan);
        $chartPenjualan->labels($tgl)->displayAxes('yAxes');
        $chartPenjualan->dataset('Penjualan', 'line', $jual)
        ->backgroundColor('rgba(66,165,245,.25)')
        ->color('rgba(66,165,245,1)')
        ->fill(TRUE);

        return view('beranda.admin', compact('data', 'chartPenjualan'));
    }

    public function gudang()
    {
        $pembelian_pending = Transaksi::where('tipe', 'beli')->where('status','dipesan')->get()->count();

        $now = Carbon::now();
        $start = Carbon::now()->startofWeek()->subDays(1);
        $end = Carbon::now()->endofWeek();
        $pembelian_minggu = Transaksi::where('tipe', 'beli')->where('status','dipesan')->whereBetween('tgl_transaksi', [$now->startofWeek()->format('Y-m-d'), $now->endofWeek()->format('Y-m-d')])->get();
        $pembelian_hari_ini = Transaksi::where('tipe', 'beli')->where('status','dipesan')->whereDate('tgl_transaksi', $now->format('Y-m-d'))->get();
        $data = array(
            'pembelian_pending' => $pembelian_pending,
            'pembelian_hari_ini' => $pembelian_hari_ini->count()
        );

        $tgl = array();
        $jual = array();
        for($i = 1; $i <= 7 ; $i++)
        {
            $hari = $start->addDays();
            $tgl[] = $hari->isoFormat('dddd'). ' - '. $hari->isoFormat('D MMMM YYYY');
            $transaksi = Transaksi::where('tipe', 'beli')->where('status','diterima')->whereDate('tgl_transaksi', $hari->format('Y-m-d'))->get();
            $jual[] = $transaksi->count();
        }

        $pembelianChart = new PembelianChart;
        $pembelianChart->labels($tgl)->displayAxes('yAxes');
        $pembelianChart->dataset('Pembelian', 'line', $jual)
        ->backgroundColor('rgba(66,165,245,.25)')
        ->color('rgba(66,165,245,1)')
        ->fill(TRUE);

        return view('beranda.gudang', compact('data', 'pembelianChart'));
    }

    public function delivery()
    {

        $now = Carbon::now();
        $start = Carbon::now()->startofWeek()->subDays(1);
        $end = Carbon::now()->endofWeek();
        // $penjualan_minggu = Transaksi::where('tipe', 'jual')->whereBetween('tgl_transaksi', [$now->startofWeek()->format('Y-m-d'), $now->endofWeek()->format('Y-m-d')])->get();
        $minggu_ini = Pengiriman::whereBetween('tgl_kirim', [$now->startofWeek()->format('Y-m-d'), $now->endofWeek()->format('Y-m-d')])->get();
        $hari_ini = Pengiriman::whereDate('tgl_kirim', $now->format('Y-m-d'))->get();

        $data = array(
            'minggu_ini' => $minggu_ini->count(),
            'hari_ini' => $hari_ini->count()
        );

        $tgl = array();
        $jual = array();
        for($i = 1; $i <= 7 ; $i++)
        {
            $hari = $start->addDays();
            $tgl[] = $hari->isoFormat('dddd'). ' - '. $hari->isoFormat('D MMMM YYYY');
            $pengiriman = Pengiriman::whereDate('tgl_kirim', $hari->format('Y-m-d'))->get();
            $jual[] = $pengiriman->count();
        }

        $pengirimanChart = new PengirimanChart;
        $pengirimanChart->labels($tgl)->displayAxes('yAxes');
        $pengirimanChart->dataset('Pengiriman', 'line', $jual)
        ->backgroundColor('rgba(66,165,245,.25)')
        ->color('rgba(66,165,245,1)')
        ->fill(TRUE);

        return view('beranda.delivery', compact('data', 'pengirimanChart'));
    }
}
