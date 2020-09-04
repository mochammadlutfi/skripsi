<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\TransaksiBayar;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Storage;
use Session;
use Illuminate\Support\Facades\DB;
use Cart;
use Carbon\Carbon;
use App\Utils\PembelianUtil;
class PembelianController extends Controller
{
    /**
    * Only Authenticated users are allowed.
    *
    * @return void
    */
    public function __construct(PembelianUtil $pembelianUtil)
    {
        $this->middleware('auth');
        $this->pembelianUtil = $pembelianUtil;
    }

    /**
     * Menampilkan data riwayat pembelian berdasarkan periode waktu tertentu.
     * Waktu default adalah transaksi selama 7 hari (1 minggu) terakhir
     *
     * @return \Illuminate\Http\Response
     */
     public function riwayat(Request $request)
    {
        if($request->ajax())
        {
            if(isset($request->tgl_mulai))
            {
                $start = Carbon::parse($request->get('tgl_mulai'))->startOfDay();
            }

            if(isset($request->tgl_akhir))
            {
                $end = Carbon::parse($request->get('tgl_akhir'))->endOfDay();
            }

            $supplier = $request->get('supplier');
            $data = Transaksi::where('tipe', 'beli')
            ->where(function($query) use($supplier){
                $query->where('supplier_id', 'LIKE', '%'. $supplier .'%');
            })
            ->where(function($q) {
                return $q->where('status', 'dipesan')
                  ->orWhere('status', 'diterima');
            })
            ->whereBetween('tgl_transaksi', [$start, $end])->paginate(16);

            $jml_pembelian = 'Rp '.number_format($data->sum('final_total'),0,",",".");


            if($data->toArray()['next_page_url'] == null)
            {
                $next = false;
            }else{
                $next = true;
            }

            if($data->toArray()['prev_page_url'] == null)
            {
                $prev = false;
            }else{
                $prev = true;
            }

            if($data->toArray()['from'] == null)
            {
                $nav = 'Draft Pembelian 0 - 0 Dari 0';
            }else{
                $nav = 'Draft Pembelian '. $data->toArray()['from'] .' - '.$data->toArray()['to'] .' Dari '.$data->toArray()['total'];
            }
            return response()->json([
                'jml_pembelian' => $jml_pembelian,
                'total_transaksi' => $data->total(),
                'html' => view('pembelian.include.riwayat_data', compact('data'))->render(),
                'current_page' => $data->toArray()['current_page'],
                'next_page' => $next,
                'prev_page' => $prev,
                'navigasi' => $nav,
            ]);
        }

        $start = Carbon::now()->startOfWeek();
        $end =  Carbon::now()->endOfWeek();

        $data = Transaksi::where('tipe', 'beli')
        ->where(function($q) {
            return $q->where('status', 'dipesan')
              ->orWhere('status', 'diterima');
        })
        ->whereBetween('tgl_transaksi', [$start, $end])->paginate(16);

        $jml_pembelian = 'Rp '.number_format($data->sum('final_total'),0,",",".");

        $total_transaksi = $data->total();

        $navigasi = array();
        if($data->toArray()['next_page_url'] == null)
        {
            $navigasi['next'] = false;
        }else{
            $navigasi['next'] = true;
        }
        if($data->toArray()['prev_page_url'] == null)
        {
            $navigasi['prev'] = false;
        }else{
            $navigasi['prev'] = true;
        }

        if($data->toArray()['from'] == null)
        {
            $navigasi['nav'] = 'Draft Pembelian 0 - 0 Dari 0';
        }else{
            $navigasi['nav'] = 'Draft Pembelian '. $data->toArray()['from'] .' - '.$data->toArray()['to'] .' Dari '.$data->toArray()['total'];
        }

        return view('pembelian.riwayat', compact('data', 'total_transaksi', 'jml_pembelian', 'navigasi'));
    }

    public function index()
    {
        return view('pembelian.form');
    }

    public function simpan(Request $request)
    {
        $rules = [
            'supplier' => 'required',
            'tgl_transaksi' => 'required',
            'jml_bayar' => 'required',
        ];

        $pesan = [
            'supplier.required' => 'Supplier Wajib Diisi!',
            'tgl_transaksi.required' => 'Tanggal Pembelian Wajib Diisi!',
            'jml_bayar.required' => 'Jumlah Pembayaran Wajib Diisi!',

        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            if($request->jml_bayar >= $request->final_total)
            {
                $status_pembayaran = 'Lunas';
            }else if($request->jml_bayar < $request->final_total && $request->jml_bayar !== '')
            {
                $status_pembayaran = 'Sebagian';
            }else{
                $status_pembayaran = 'Belum Dibayar';
            }
            DB::beginTransaction();
            try{

                // Simpan Data Transakasi
                $trans = array(
                    'tipe' => 'beli',
                    'status'=> 'draft',
                    'bayar_status' => $status_pembayaran,
                    'supplier_id' => $request->supplier,
                    'invoice_no' => get_no_transaksi('beli'),
                    'tgl_transaksi' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                    'total' => $request->sub_total,
                    'diskon_tipe' => $request->jenis_diskon,
                    'diskon_nilai' => $request->diskon,
                    'final_total' => $request->final_total,
                    'dibuat_oleh' => auth()->user()->id,
                    'created_at' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                );
                $transaksi = Transaksi::create($trans);

                // Simpan Data Pembayaran
                $bayar = array(
                    'transaksi_id' => $transaksi->id,
                    'method' => 'tunai',
                    'jumlah'=> $request->jml_bayar,
                    'note' => $request->note_bayar,
                    'created_at' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                );

                $pembayaran = TransaksiBayar::create($bayar);

                // Simpan Data Pembelian
                $this->pembelianUtil->createOrUpdatePembelian($transaksi, $request->pembelian);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Data Gagal Diproses!',
                    'error_log' => $e
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
                'detail_url' => route('pembelian.detail', $transaksi->id)
            ]);
        }
    }

    public function edit($transaksi_id)
    {
        Cart::session('pembelian')->clear();
        $transaksi = Transaksi::find($transaksi_id);
        $produk = Pembelian::where('transaksi_id', $transaksi_id)->get();
        foreach($produk as $p)
        {
            if($p->variasi->nama !== '')
            {
                $nama = $p->variasi->produk->nama.', '.$p->variasi->nama;
            }else{
                $nama = $p->variasi->produk->nama;
            }
            $data = array(
                'id' => $p->variasi_id,
                'name' => $nama,
                'price' => $p->hrg_beli,
                'quantity' => $p->quantity,
                'attributes' => array(
                    'produk_id' => $p->produk_id,
                    'kelola_stok' => $p->variasi->kelola_stok,
                    'pembelian_id' => $p->id,
                )
            );
            if($p->variasi->kelola_stok == 1)
            {
                $data['attributes']['satuan_id'] = $p->variasi->satuan_id;
                $data['attributes']['satuan_nama'] = $p->variasi->satuan->nama;
            }else{
                $data['attributes']['satuan_id'] = null;
                $data['attributes']['satuan_nama'] = null;
                $data['attributes']['pembelian_id'] = $p->id;
            }
            Cart::session('pembelian')->add($data);
        }

        $pembayaran = TransaksiBayar::where('transaksi_id', $transaksi_id)->get();

        return view('pembelian.edit', compact('transaksi', 'produk', 'pembayaran'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'supplier' => 'required',
            'tgl_transaksi' => 'required',
            'jml_bayar' => 'required',
        ];

        $pesan = [
            'supplier.required' => 'Supplier Wajib Diisi!',
            'tgl_transaksi.required' => 'Tanggal Pembelian Wajib Diisi!',
            'jml_bayar.required' => 'Jumlah Pembayaran Wajib Diisi!',

        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{

            if($request->jml_bayar >= $request->final_total)
            {
                $status_pembayaran = 'Lunas';
            }else if($request->jml_bayar < $request->final_total && $request->jml_bayar !== '')
            {
                $status_pembayaran = 'Sebagian';
            }else{
                $status_pembayaran = 'Belum Dibayar';
            }
            DB::beginTransaction();
            try{

                // Simpan Data Transakasi
                $transaksi = Transaksi::find($request->transaksi_id);
                $trans = array(
                    'supplier_id' => $request->supplier,
                    'tgl_transaksi' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                    'total' => $request->sub_total,
                    'diskon_tipe' => $request->jenis_diskon,
                    'diskon_nilai' => $request->diskon,
                    'final_total' => $request->final_total,
                    'dibuat_oleh' => auth()->user()->id,
                    'created_at' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                );
                $transaksi->update($trans);
                // dd($transaksi);
                // Simpan Data Pembelian
                $this->pembelianUtil->createOrUpdatePembelian($transaksi, $request->pembelian, $transaksi->status);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Data Gagal Diproses!',
                    'error_log' => $e
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
                'detail_url' => route('pembelian.detail', $transaksi->id)
            ]);
        }
    }

    public function detail($transaksi_id)
    {
        $transaksi = Transaksi::find($transaksi_id);
        $produk = Pembelian::where('transaksi_id', $transaksi_id)->get();
        $pembayaran = TransaksiBayar::where('transaksi_id', $transaksi_id)->get();

        return view('pembelian.detail', compact('transaksi', 'produk', 'pembayaran'));
    }

    public function hapus($id)
    {
        $trans = Transaksi::where('kd_faktur', $id)->first();
        $del = Transaksi::destroy($trans->transaksi_id);
        if($del){
            $data = Pembelian::destroy($id);
            if($data){
                return response()->json([
                    'fail' => false,
                ]);
            }
        }
    }

    /**
     * Menampilkan data riwayat pembelian berdasarkan periode waktu tertentu.
     * Waktu default adalah transaksi selama 7 hari (1 minggu) terakhir
     *
     * @return \Illuminate\Http\Response
     */
    public function draft(Request $request)
    {
        // if(auth()->user()->hasRole('Admin'))
        // {
        //     $status = 'draft'
        // }else if( auth()->user()->hasRole('General Manager'))
        // {
        //     $status =

        // }else if( auth()->user()->hasRole('Merchandiser'))
        // {
        //     $status =
        // }
        if($request->ajax())
        {

            if(isset($request->tgl_mulai))
            {
                $start = Carbon::parse($request->get('tgl_mulai'))->startOfDay();
            }

            if(isset($request->tgl_akhir))
            {
                $end = Carbon::parse($request->get('tgl_akhir'))->endOfDay();
            }
            $supplier = $request->get('supplier');

            if(auth()->user()->hasRole('Kepala Gudang'))
            {
                $data = Transaksi::whereBetween('tgl_transaksi', [$start, $end])
                ->where('tipe', 'beli')
                ->where(function($query) use($supplier){
                    $query->where('supplier_id', 'LIKE', '%'. $supplier .'%');
                })
                ->where('status', 'dipesan')
                ->orderBy('invoice_no', 'DESC')->paginate(16);
            }else{
                $data = Transaksi::whereBetween('tgl_transaksi', [$start, $end])
                ->where('tipe', 'beli')
                ->where(function($query) use($supplier){
                    $query->where('supplier_id', 'LIKE', '%'. $supplier .'%');
                })
                ->where(function($q) {
                    $q->where('status', 'draft')
                    ->orWhere('status', 'konfirmasi');
                })
                ->orderBy('invoice_no', 'DESC')->paginate(16);
            }
            $jml_pembelian = 'Rp '.number_format($data->sum('final_total'),0,",",".");


            if($data->toArray()['next_page_url'] == null)
            {
                $next = false;
            }else{
                $next = true;
            }

            if($data->toArray()['prev_page_url'] == null)
            {
                $prev = false;
            }else{
                $prev = true;
            }

            if($data->toArray()['from'] == null)
            {
                $nav = 'Draft Pembelian 0 - 0 Dari 0';
            }else{
                $nav = 'Draft Pembelian '. $data->toArray()['from'] .' - '.$data->toArray()['to'] .' Dari '.$data->toArray()['total'];
            }
            return response()->json([
                'jml_pembelian' => $jml_pembelian,
                'total_transaksi' => $data->total(),
                'html' => view('pembelian.include.draft_data', compact('data'))->render(),
                'current_page' => $data->toArray()['current_page'],
                'next_page' => $next,
                'prev_page' => $prev,
                'navigasi' => $nav,
            ]);
        }

        $start = Carbon::now()->startOfWeek();
        $end =  Carbon::now()->endOfWeek();
        // Default Data
        if(auth()->user()->hasRole('Kepala Gudang'))
        {
            $data = Transaksi::where('tipe', 'beli')
            ->where('status', 'dipesan')
            ->whereBetween('tgl_transaksi', [$start, $end])
            ->orderBy('invoice_no', 'DESC')->paginate(16);
        }else{
            $data = Transaksi::where('tipe', 'beli')
            ->where(function($q) {
                $q->where('status', 'draft')
                ->orWhere('status', 'konfirmasi');
            })
            ->whereBetween('tgl_transaksi', [$start, $end])
            ->orderBy('invoice_no', 'DESC')->paginate(16);
        }
        $jml_pembelian = 'Rp '.number_format($data->sum('final_total'),0,",",".");

        $total_transaksi = $data->total();

        $navigasi = array();
        if($data->toArray()['next_page_url'] == null)
        {
            $navigasi['next'] = false;
        }else{
            $navigasi['next'] = true;
        }
        if($data->toArray()['prev_page_url'] == null)
        {
            $navigasi['prev'] = false;
        }else{
            $navigasi['prev'] = true;
        }

        if($data->toArray()['from'] == null)
        {
            $navigasi['nav'] = 'Draft Pembelian 0 - 0 Dari 0';
        }else{
            $navigasi['nav'] = 'Draft Pembelian '. $data->toArray()['from'] .' - '.$data->toArray()['to'] .' Dari '.$data->toArray()['total'];
        }

        return view('pembelian.draft', compact('data', 'total_transaksi', 'jml_pembelian', 'navigasi'));
    }

    public function konfirmasi($id)
    {
        DB::beginTransaction();
        try{
            $transaksi = Transaksi::find($id);

            if(auth()->user()->hasrole('General Manager'))
            {
                $transaksi->status = 'konfirmasi';
                $transaksi->save();
            }else if(auth()->user()->hasrole('Merchandiser'))
            {
                $transaksi->status = 'dipesan';
                $transaksi->save();

            }else if(auth()->user()->hasrole('Kepala Gudang'))
            {
                $sebelumnya = $transaksi->status;
                $transaksi->status = 'diterima';
                $transaksi->save();

                $pembelian = Pembelian::where('transaksi_id', $id)->get()->toArray();
                foreach($pembelian as $p)
                {
                    $this->pembelianUtil->updateStokProduk($p['produk_id'], $p['variasi_id'], $p['quantity'], 0);
                }
            }else{

            }
        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => 'Data Gagal Diproses!',
                'error_log' => $e
            ]);
        }
        DB::commit();
        return response()->json([
            'fail' => false,
        ]);
    }

    public function addCart(Request $request)
    {
        if (request()->ajax()) {
            $pembelian = Cart::session('pembelian');
            $row_count = $request->row_count;
            for ($i = 0; $i < count($request->produk); $i++)
            {
                if($request->produk[$i]['qty'] > 0)
                {
                    if ($pembelian->has($request->produk[$i]['variasi_id'])) {
                        Cart::update($request->produk[$i]['variasi_id'], [
                            'quantity' => array(
                                'relative' => false,
                                'value' => $request->produk[$i]['qty']
                            ),
                        ]);
                    }else{
                        $data = array(
                            'id' => $request->produk[$i]['variasi_id'],
                            'name' =>get_namaProduk($request->produk[$i]['produk_nama'],$request->produk[$i]['variasi_nama']),
                            'price' => $request->produk[$i]['hrg'],
                            'quantity' => $request->produk[$i]['qty'],
                            'attributes' => array(
                                'produk_id' =>$request->produk[$i]['produk_id'],
                                'kelola_stok' =>$request->produk[$i]['kelola_stok'],
                            )
                        );
                        if($request->produk[$i]['kelola_stok'] == 1)
                        {
                            $data['attributes']['satuan_id'] = $request->produk[$i]['satuan_id'];
                            $data['attributes']['satuan_nama'] = $request->produk[$i]['satuan_nama'];
                        }
                        $pembelian->add($data);
                    }
                }else{
                    $pembelian->remove($request->produk[$i]['variasi_id']);
                }
            }

            return response()->json([
                'fail' => false,
                'total_item' => Cart::session('pembelian')->getContent()->count(),
                'total' => $pembelian->getTotal(),
                'sub_total' => $pembelian->getSubTotal(),
                'html' => view('pembelian.include.entry_row')->render(),
            ]);
        }
    }

    public function updateCart(Request $request)
    {
        if (request()->ajax()) {
            $update = Cart::session('pembelian')
            ->update($request->variasi_id,[
                'quantity' =>array(
                    'relative' => false,
                    'value' => $request->qty
                ),
                'price' => $request->hrg
            ]);

            return response()->json([
                'fail' => false,
                'total_item' => Cart::session('pembelian')->getContent()->count(),
                'total' => Cart::session('pembelian')->getTotal(),
                'sub_total' => Cart::session('pembelian')->getSubTotal(),
                'html' => view('pembelian.include.entry_row')->render(),
            ]);
        }
    }

    public function deleteCart(Request $request)
    {
        if($request->ajax())
        {
            $hapus = Cart::session('pembelian')->remove($request->variasi_id);
            if($hapus)
            {
                return response()->json([
                    'fail' => false,
                    'total_item' => Cart::session('pembelian')->getContent()->count(),
                    'total' => Cart::session('pembelian')->getTotal(),
                    'sub_total' => Cart::session('pembelian')->getSubTotal(),
                    'html' => view('pembelian.include.entry_row')->render(),
                ]);
            }
        }
    }

    public function getCart()
    {
        $pembelian = Cart::session('pembelian');
        return response()->json([
            'fail' => false,
            'total_item' => $pembelian->getContent()->count(),
            'total' => $pembelian->getTotal(),
            'sub_total' => $pembelian->getSubTotal(),
            'html' => view('pembelian.include.entry_row')->render(),
        ]);
    }
}
