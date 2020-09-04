<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\ProdukVariasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Cart;
use Session;
use Storage;

class PeramalanController extends Controller
{
    /**
     * Only Authenticated users
     * are allowed.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $kategori_id = $request->get('kategori_id');
            $merk_id = $request->get('merk_id');
            $cari = $request->get('keyword');

            $produk = Produk::where('kategori_id', 'like', '%' . $kategori_id . '%')
            ->where('merk_id', 'like', '%' . $merk_id . '%')
            ->where('nama', 'like', '%' . $cari . '%')
            ->paginate(6);
            if($produk->toArray()['next_page_url'] == null)
            {
                $next = false;
            }else{
                $next = true;
            }

            if($produk->toArray()['prev_page_url'] == null)
            {
                $prev = false;
            }else{
                $prev = true;
            }
            if($produk->toArray()['from'] == null)
            {
                $nav = 'Data Produk 0 - 0 Dari 0';
            }else{
                $nav = 'Data Produk '. $produk->toArray()['from'] .' - '.$produk->toArray()['to'] .' Dari '.$produk->toArray()['total'];
            }
            return response()->json([
                'fail' => false,
                'navigasi' => $nav,
                'tipe' => $request->get('tipe'),
                'total' => $produk->Total(),
                'current_page' => $produk->toArray()['current_page'],
                'next_page' => $next,
                'prev_page' => $prev,
                'html' => view('peramalan.include.produk_data', compact('produk'))->render(),
            ]);
        }
        $produk = Produk::paginate(6);
        if($produk->toArray()['next_page_url'] == null)
        {
            $next = false;
        }else{
            $next = true;
        }

        if($produk->toArray()['prev_page_url'] == null)
        {
            $prev = false;
        }else{
            $prev = true;
        }

        if($produk->toArray()['from'] == null)
        {
            $nav = 'Data Produk 0 - 0 Dari 0';
        }else{
            $nav = 'Data Produk '. $produk->toArray()['from'] .' - '.$produk->toArray()['to'] .' Dari '.$produk->toArray()['total'];
        }

        return view('peramalan.index', compact('produk'));
    }

    public function show_variasi(Request $request)
    {
        $tipe = $request->tipe;
        $produk = Produk::find($request->produk_id);

        $produkVariasi = ProdukVariasi::where('produk_id', $request->produk_id)
        ->where('kelola_stok', 1)->get();
        $variasi = array();
        $i = 0;

        foreach($produkVariasi as $pv)
        {
            $hrg = $pv->hrg_modal;
            $variasi[] = view('peramalan.include.modal_variasi_entry', compact('pv', 'i', 'hrg', 'tipe'))->render();
            $i++;
        }
        if(!empty($produk->foto))
        {
            $produk_foto = asset($produk->foto);
        }else{
            $produk_foto = asset('assets/img/placeholder/product.png');
        }

        return response()->json([
            'fail' => false,
            'produk_foto' => $produk_foto,
            'produk_nama' => $produk->nama,
            'produk_kategori' => $produk->kategori->nama,
            'variasi' => $variasi,
        ]);
    }


    /**
     * Menghitung Peramalan Produk.
     *
     * @return \Illuminate\Http\Response
     */
    public function hitung(Request $request)
    {
        $rules = [
            'tgl_target' => 'required',
            'variasi_id' => 'required',
        ];

        $pesan = [
            'tgl_target.required' => 'Target Peramalan Wajib Diisi!',
            'variasi_id.required' => 'Produk Variasi Wajib Diisi!',

        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'tipe' => 'input'
            ]);
        }else{
            $variasi = ProdukVariasi::find($request->variasi_id);
            $dateMonthArray = explode('-', $request->tgl_target);
            $month = $dateMonthArray[0];
            $year = $dateMonthArray[1];
            $metode = 6;
            $target = Carbon::createFromDate($year, $month)->startOfMonth();
            $dari = number_format(Carbon::createFromDate($year, $month)->startOfMonth()->subMonths($metode)->format('n'));
            $sampai = number_format(Carbon::createFromDate($year, $month)->endOfMonth()->subMonths(1)->format('n'));

            $bln = 1;
            $sum_penjualan = 0;
            for($i= $dari; $i <= $sampai; $i++)
            {
                $bulan = Carbon::createFromDate($year, $month)->startOfMonth()->subMonths($bln);
                $penjualan = Penjualan::where('variasi_id', $request->variasi_id)->whereMonth('created_at', $bulan->format('m'))
                ->whereYear('created_at', Carbon::createFromDate($year, $month)->startOfMonth()->subMonths($bln)->format('Y'))
                ->sum('quantity');
                if($penjualan)
                {
                    $sum_penjualan += $penjualan;
                }else{
                    return response()->json([
                        'fail' => true,
                        'errors' => 'Data Penjualan Sebelumnya Kurang Dari '.$metode.' Bulan',
                        'tipe' => 'data'
                    ]);
                    die;
                }
                $bln++;
            }
            $average = round(($sum_penjualan / $metode), 2);

            return response()->json([
                'fail' => false,
                'judul' => 'Hasil Peramalan Produk <b>'. $variasi->produk->nama .'</b>',
                'target' =>  Carbon::createFromDate($year, $month)->format('F Y'),
                'peramalan' => round($average).' '. $variasi->satuan->nama,
                'hasil' => round($average),
                'variasi_id' => $variasi->id,
            ]);
        }
    }


    public function pembelian(Request $request)
    {
        if (request()->ajax()) {
            $variasi_id = $request->variasi_id;
            $peramalan = $request->peramalan;

            $variasi = ProdukVariasi::find($request->variasi_id);
            $data = array(
                'id' => $variasi->id,
                'name' => get_namaProduk($variasi->produk->nama,$variasi->nama),
                'price' => $variasi->hrg_modal,
                'quantity' => $peramalan,
                'attributes' => array(
                    'produk_id' => $variasi->produk->id,
                    'kelola_stok' => $variasi->kelola_stok,
                )
            );

            if($variasi->kelola_stok == 1)
            {
                $data['attributes']['satuan_id'] = $variasi->satuan->id;
                $data['attributes']['satuan_nama'] =$variasi->satuan->nama;
            }

            Cart::session('pembelian')->add($data);

            return response()->json([
                'fail' => false,
            ]);
        }
    }
}
