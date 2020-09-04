<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kendaraan;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use GoogleMaps;
use App\Utils\Distribusi;
class PengirimanController extends Controller
{
    /**
     * Only Authenticated users
     * are allowed.
     *
     * @return void
     */
    public function __construct(Distribusi $distribusi)
    {
        $this->middleware('auth');
        $this->distribusi = $distribusi;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if(!isset($request->tgl_kirim))
            {
                $bln =  Carbon::now()->format('m');
                $thn =  Carbon::now()->format('Y');
            }else{
                $dateMonthArray = explode('-', $request->tgl_kirim);
                $month = bln_nomor($dateMonthArray[0]);
                $year = $dateMonthArray[1];
                $bln = Carbon::createFromDate($year, $month)->format('m');
                $thn =Carbon::createFromDate($year, $month)->format('Y');
            }

            $data = Pengiriman::select(DB::raw("COUNT(DISTINCT transaksi_id) as transaksi, tgl_kirim, status, COUNT(DISTINCT rute) as rutenya,SUM(beban) as bebannya, COUNT(case when status=2 then 1 end) as bermasalah"))
            ->whereMonth('tgl_kirim', $bln)
            ->whereYear('tgl_kirim', $thn)
            ->where('status', '!=', 3)
            ->groupBy('tgl_kirim')->orderBy('tgl_kirim', 'DESC')->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tgl', function($row)

            {
                return get_tgl($row->tgl_kirim);
            })
            ->addColumn('transaksi', function($row){

                return $row->transaksi;
            })

            ->addColumn('beban', function($row){
                return $row->bebannya;
            })

            ->addColumn('rute', function($row){
                return $row->rutenya;
            })
            ->addColumn('bermasalah', function($row){
                return $row->bermasalah;
            })
            ->addColumn('action', function($row){
                    $btn = '<center><a href="'. route('pengiriman.jadwal', Carbon::parse($row->tgl_kirim)->format('d-m-Y')) .'" class="btn btn-secondary btn-sm mr-5"><i class="si si-note"></i> Jadwal Pengiriman</a></center>';
                    return $btn;
            })
            ->rawColumns(['nama', 'alamat', 'action'])
            ->make(true);
        }
        return view('pengiriman.index');
    }


    public function saving_matrix($tgl_kirim)
    {
        $data = Pengiriman::
        select('pengiriman.id as id', 'p.lat as lat', 'p.lng as lng', 'p.jarak as jarak', 'pengiriman.beban as beban', 'p.id as pelanggan_id', 'pengiriman.transaksi_id as transaksi_id')->
        join('pelanggan as p', 'p.id', '=', 'pengiriman.pelanggan_id')->
        where('tgl_kirim', Carbon::parse($tgl_kirim)->format('Y-m-d'))
        ->orderby('pengiriman.id', 'ASC')->get();
        $r = array(array('id' => 0,'nama' => 'gudang','jarak' => 0,'lat' => -6.867628,'lng' => 107.560505,));
        $client_demands = array();
        $client_node = array();
        foreach($data as $d)
        {
            $r[] = array(
                'id' => $d->id,
                'nama' => $d->nama,
                'jarak' => $d->jarak,
                'lat' => $d->lat,
                'lng' => $d->lng,
            );
            $client_node[] = $d->id;
            $client_demands[$d->id] = array(
                'bobot_pesanan' => $d->beban,
                'total_volume_of_order' => $d->beban,
                'transaksi_id' => $d->transaksi_id,
                'pengiriman_id' => $d->id,
                'pelanggan_id' => $d->pelanggan_id,
            );
        }
        $batas = count($r);
        $jarak = array();
        for($i= 0 ; $i < $batas; $i++)
        {
            for($j= 0; $j < $batas; $j++)
            {
                if($i == $j)
                {
                    $hitung = 0;
                }else
                {
                    $hitung = $this->jarak($r[$i]['lat'], $r[$i]['lng'], $r[$j]['lat'], $r[$j]['lng']);
                }
                $jarak[$r[$i]['id']][$r[$j]['id']] = $hitung;
            }
        }
        // dd($jarak);
        $kendaraan_get = Kendaraan::orderby('id', 'ASC')->get();
        $kendaraan = array();
        foreach($kendaraan_get as $k)
        {
            $kendaraan[$k->id] = array(
                'weight' => $k->max_kapasitas,
                'volume' => $k->max_kapasitas,
            );
        }
        $this->distribusi->loadData($client_node,$client_demands,$jarak,$kendaraan);
        $this->distribusi->cwSavings();
        $this->distribusi->pengurutan();
        $this->distribusi->cwroutes();
        $this->distribusi->finishingTouch();
        $c = 1;
        foreach ($this->distribusi->routes as $key => $value) {
            foreach($value['pengiriman_id'] as $d)
            {
                $kirim = Pengiriman::find($d);
                if($kirim->status == 2)
                {
                    $kirim->status = 0;
                }
                $kirim->rute = 'Rute '.$c;
                $kirim->urutan = $c;
                $kirim->kendaraan_id =  $value['truck'];
                $kirim->keterangan =  null;
                $kirim->save();
            }
            $c++;
        }
        if(count($this->distribusi->unsatisfied) >= 1)
        {
            foreach ($this->distribusi->unsatisfied as $pengiriman_id => $ket) {
                $gagal = Pengiriman::find($pengiriman_id);
                $gagal->status = 2;
                $gagal->keterangan =  $ket;
                $gagal->save();
            }
        }
    }

    /**
     * Detail Pengiriman Produk.
     *
     * @return \Illuminate\Http\Response
     */
    public function jadwal($tgl_kirim)
    {
        $this->saving_matrix($tgl_kirim);

        $jadwal = Pengiriman::where('status', '!=', 2)->where('tgl_kirim', Carbon::parse($tgl_kirim)->format('Y-m-d'))
        ->orderby('rute', 'ASC')->get();
        $tidak_dikirim = Pengiriman::where('status', 2)->where('tgl_kirim', Carbon::parse($tgl_kirim)->format('Y-m-d'))
        ->orderby('rute', 'ASC')->get();

        return view('pengiriman.jadwal', compact('jadwal', 'tgl_kirim', 'tidak_dikirim'));
    }

    public function riwayat(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->all());
            if(!isset($request->tgl_mulai) && !isset($request->tgl_akhir))
            {
                $start = Carbon::now()->startOfWeek();
                $end =  Carbon::now()->endOfWeek();
            }else{
                $start = Carbon::parse($request->tgl_mulai)->startOfDay();
                $end = Carbon::parse($request->tgl_akhir)->endOfDay();
            }

            $data = Pengiriman::where('status', 3)
            ->whereBetween('tgl_kirim', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->orderBy('tgl_kirim', 'DESC')->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('transaksi', function($row){

                return $row->transaksi->invoice_no;
            })

            ->addColumn('pelanggan', function($row){
                return $row->pelanggan->nama;
            })

            ->addColumn('produk', function($row){
                return $row->transaksi->penjualan()->count();
            })
            ->addColumn('kendaraan', function($row){
                return $row->kendaraan->tipe;
            })
            ->addColumn('status', function($row){
                return get_pengiriman_status($row->status);
            })
            ->addColumn('tgl', function($row)
            {
                return get_tgl($row->tgl_kirim);
            })
            ->rawColumns(['nama', 'alamat', 'status'])
            ->make(true);
        }
        return view('pengiriman.riwayat');
    }

    public function perbaikan(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction();
        try{
            $utama = Pengiriman::find($request->pengiriman_id);
            foreach($request->pengiriman as $d)
            {
                if(isset($d['id']))
                {

                    $utama->beban = $d['volume'];
                    $utama->tgl_kirim = Carbon::createFromFormat('d-m-Y', $d['tgl_kirim'])->format('Y-m-d H:i:s');
                    $utama->status = 0;
                    $utama->save();
                }else{
                    $pengiriman = new Pengiriman();
                    $pengiriman->transaksi_id = $utama->transaksi_id;
                    $pengiriman->pelanggan_id = $utama->pelanggan_id;
                    $pengiriman->beban = $d['volume'];
                    $pengiriman->tgl_kirim = Carbon::createFromFormat('d-m-Y', $d['tgl_kirim'])->format('Y-m-d H:i:s');
                    $pengiriman->status = 0;
                    $pengiriman->save();
                }
            }
        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => 'Data Gagal Diproses!',
                'log_error' => $e
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
        ]);

    }

    function rad($x){
        return $x * M_PI / 180;
    }

    function jarak($lat1, $lon1, $lat2, $lon2){
        // jarak kilometer dimensi (mean radius) bumi
        $R = 6371;
        $dLat = $this->rad(($lat2) - ($lat1));
        $dLong = $this->rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos($this->rad($lat1)) * cos($this->rad($lat1)) * sin($dLong/2) * sin($dLong/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $d = $R * $c;
        // hasil akhir dalam satuan kilometer
        return number_format($d, 0, '.', ',');
    }

    public function konfirmasi(Request $request)
    {
        $ids = $request->ids;
        $pengiriman = Pengiriman::whereIn('id',explode(",",$ids))->get();
        foreach($pengiriman as $p)
        {
            $pengiriman = Pengiriman::find($p->id);
            if($request->tipe == 'dikirim'){

                $pengiriman->status = 1;
            }elseif($request->tipe == 'diterima')
            {
                $pengiriman->status = 3;
            }else{
                $pengiriman->status = 0;
            }

            $pengiriman->save();
        }
        return response()->json([
            'fail' => false
        ]);
    }

    public function edit($pengiriman_id)
    {
        $pengiriman = Pengiriman::find($pengiriman_id);
        if($pengiriman)
        {
            return response()->json([
                'fail' => false,
                'id' => $pengiriman->id,
                'beban' => $pengiriman->beban,
                'tgl_kirim' => Carbon::parse($pengiriman->tgl_kirim)->format('d-m-Y'),
            ]);
        }else{
            return response()->json([
                'fail' => true
            ]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'tgl_kirim' => 'required',
        ];

        $pesan = [
            'tgl_kirim.required' => 'Tanggal Pengiriman Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{

            $data = Pengiriman::find($request->pengiriman_id);
            $data->tgl_kirim = Carbon::parse($request->tgl_kirim)->format('Y-m-d');
            if($data->save())
            {
                return response()->json([
                    'fail' => false,
                ]);
            }
        }
    }
}
