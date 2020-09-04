<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Merk;
use App\Models\Pembelian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cart;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Utils\PembelianUtil;
use Yajra\DataTables\DataTables;

class ReturBeliController extends Controller
{
    /**
     * Only Authenticated users for "mitra" guard
     * are allowed.
     *
     * @return void
     */
    private $transaksiRepo;
    // private $transaksiUtil;
    public function __construct(PembelianUtil $pembelianUtil)
    {
        $this->middleware('auth');
        $this->pembelianUtil = $pembelianUtil;
    }

    /**
     * Menampilkan data riwayat retur pembelian produk berdasarkan periode waktu tertentu.
     * Waktu default adalah transaksi selama 7 hari (1 minggu) terakhir
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

            $data = Transaksi::whereBetween('tgl_transaksi', [$start, $end])
            ->where('tipe', 'retur_beli')
            ->orderBy('tgl_transaksi', 'DESC')->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->setRowAttr([
                'onClick' => function($row) {
                    return "link(".$row->id.")";
                },
                'style' => 'cursor:pointer',
            ])
            ->addColumn('tgl', function($row)
            {
                return get_tgl($row->tgl_transaksi);
            })
            ->addColumn('pembelian', function($row){
                return '<a href="'.  route('pembelian.detail', $row->induk_retur->id).'">'.$row->induk_retur->invoice_no.'</a>';
            })
            ->addColumn('supplier', function($row){
                return $row->supplier->nama;
            })
            ->addColumn('nominal', function($row){
                return 'Rp '. number_format($row->final_total,0,",",".");
            })
            ->addColumn('status', function($row){
                return get_status($row->status);
            })
            ->addColumn('aksi', function($row){
                    $btn = '<center><a href="'. route("returbeli.detail",$row->id) .'" class="btn btn-alt-secondary btn-sm">Detail</a></center>';
                    return $btn;
            })
            ->rawColumns(['aksi', 'pembelian', 'status'])
            ->make(true);
        }

        return view('returbeli.riwayat');
    }

    /**
     * Show Riwayat Penjualan.
     *
     * @return \Illuminate\Http\Response
     */
    public function tambah($id){

        $transaksi = Transaksi::find($id);

        $pembelian = Pembelian::where('transaksi_id', $id)->get();

        return view('returbeli.tambah', compact('pembelian', 'transaksi'));
    }

    public function simpan(Request $request)
    {

            DB::beginTransaction();
            try{
                $lama = Transaksi::find($request->transaksi_id);
                $return_quantities = $request->input('retur');
                $return_total = 0;

                foreach ($lama->pembelian as $pembelian) {
                    $old_return_qty = $pembelian->qty_retur;


                    $return_quantity = !empty($return_quantities[$pembelian->id]['jumlah']) ? $return_quantities[$pembelian->id]['jumlah'] : 0;
                    // dd($return_quantity);
                    $pembelian->qty_retur = $return_quantity;
                    $pembelian->save();
                    $return_total += $pembelian->hrg_beli * $pembelian->qty_retur;

                    //Kurangi Stok yang tersedia
                    if ($old_return_qty != $pembelian->qty_retur) {
                        $this->pembelianUtil->kurangiStokProduk(
                            $pembelian->produk_id,
                            $pembelian->variasi_id,
                            $pembelian->qty_retur,
                            $old_return_qty
                        );
                    }
                }

                $trans = array(
                    'total' => $request->sub_total,
                    'final_total' => $request->sub_total,
                    'jumlah_pengembalian' => $return_total,
                );

                $retur_beli = Transaksi::where('tipe', 'retur_beli')->where('retur_induk', $lama->id)->first();
                if ($retur_beli) {
                    $retur_beli->update($trans);
                }else{
                    $trans['invoice_no'] = get_no_transaksi('retur_beli');
                    $trans['tipe'] = 'retur_beli';
                    $trans['status'] = 'final';
                    $trans['supplier_id'] = $lama->supplier_id;
                    $trans['tgl_transaksi'] = Carbon::now()->format('Y-m-d H:i:s');
                    $trans['dibuat_oleh'] =  auth()->user()->id;
                    $trans['retur_induk'] = $request->transaksi_id;
                    $coba = Transaksi::create($trans);
                    dd($coba);
                }
                // update status pembayaran
                // $this->transaksiUtil->updateStatusPembayaran($returbeli->id, $returbeli->final_total);
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => $e,
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        // }
    }

    public function detail($id)
    {
        $retur = Transaksi::where('tipe', 'retur_beli')->find($id);

        $transaksi = Transaksi::find($retur->retur_induk);


        return view('returbeli.detail', compact('transaksi', 'retur'));
    }

    public function edit($id)
    {
        $retur = Transaksi::where('tipe', 'retur_beli')->find($id);

        $transaksi = Transaksi::find($retur->retur_induk);


        return view('returbeli.edit', compact('transaksi', 'retur'));
    }

    /**
     * Menghapus Data Retur Pembelian dan Mengembalikan Stok Produk
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hapus($id)
    {
        try {
            if (request()->ajax()) {

                $retur_beli = Transaksi::where('id', $id)
                                ->where('tipe', 'retur_beli')
                                ->first();

                $pembelian = Pembelian::where('transaksi_id', $retur_beli->retur_induk)->get();

                DB::beginTransaction();

                if (empty($retur_beli->retur_induk)) {

                    // $delete_purchase_lines = $pembelian;
                    $delete_purchase_line_ids = [];

                    foreach ($pembelian as $beli) {
                        $delete_purchase_line_ids[] = $beli->id;
                        $this->pembelianUtil->updateStokProduk($beli->produk_id, $beli->variasi_id, $beli->qty_retur, 0, null, false);
                    }

                    // Pembelian::where('transaction_id', $retur_beli->id)
                    //             ->whereIn('id', $delete_purchase_line_ids)
                    //             ->delete();
                } else {

                    $induk_pembelian = Transaksi::where('id', $retur_beli->retur_induk)
                                ->where('tipe', 'beli')
                                ->with(['pembelian'])
                                ->first();

                    $update_pembelian = $induk_pembelian->pembelian;
                    foreach ($update_pembelian as $pembelian) {
                        $this->pembelianUtil->updateStokProduk($pembelian->produk_id, $pembelian->variasi_id, $pembelian->qty_retur, 0, null, false);
                        $pembelian->qty_retur = 0;
                        $pembelian->save();
                    }
                }

                //Delete Transaction
                $retur_beli->delete();

            }
        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => 'Data Gagal Di Proses',
                'log_error' => $e,
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
        ]);

        return $output;
    }


    public function addCart(Request $request)
    {
        // dd($request->all());
        if (request()->ajax()) {
            $returbeli = Cart::session('returbeli');
            $row_count = $request->row_count;
            for ($i = 0; $i < count($request->produk); $i++)
            {
                if($request->produk[$i]['qty'] > 0)
                {
                    if ($returbeli->has($request->produk[$i]['variasi_id'])) {
                        $returbeli->update($request->produk[$i]['variasi_id'], [
                            'quantity' => array(
                                'relative' => false,
                                'value' => $request->produk[$i]['qty']
                            ),
                        ]);
                    }else{
                        $data = array(
                            'id' => $request->produk[$i]['variasi_id'],
                            'name' => $request->produk[$i]['produk_nama'].', '.$request->produk[$i]['variasi_nama'],
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
                        $returbeli->add($data);
                    }
                }else{
                    $returbeli->remove($request->produk[$i]['variasi_id']);
                }
            }
                return response()->json([
                    'fail' => false,
                    'total_item' => $returbeli->getContent()->count(),
                    'total' => $returbeli->getTotal(),
                    'sub_total' => $returbeli->getSubTotal(),
                    'html' => view('returbeli.include.entry_row')->render(),
                ]);
        }
    }

    public function updateCart(Request $request)
    {
        if (request()->ajax()) {
            $returbeli = Cart::session('returbeli');
            $update = $returbeli->update($request->variasi_id,[
                'quantity' =>array(
                    'relative' => false,
                    'value' => $request->qty
                ),
                'price' => $request->hrg
            ]);

            return response()->json([
                'fail' => false,
                'total_item' => $returbeli->getContent()->count(),
                'total' => $returbeli->getTotal(),
                'sub_total' => $returbeli->getSubTotal(),
                'html' => view('returbeli.include.entry_row')->render(),
            ]);
        }
    }

    public function deleteCart(Request $request)
    {
        if($request->ajax())
        {
            $returbeli = Cart::session('returbeli');
            $hapus = $returbeli->remove($request->variasi_id);
            if($hapus)
            {
                return response()->json([
                    'fail' => false,
                    'total_item' => $returbeli->getContent()->count(),
                    'total' => $returbeli->getTotal(),
                    'sub_total' => $returbeli->getSubTotal(),
                    'html' => view('returbeli.include.entry_row')->render(),
                ]);
            }
        }
    }

    public function getCart()
    {
        $returbeli = Cart::session('returbeli');
        return response()->json([
            'fail' => false,
            'total_item' => $returbeli->getContent()->count(),
            'total' => $returbeli->getTotal(),
            'sub_total' => $returbeli->getSubTotal(),
            'html' => view('returbeli.include.entry_row')->render(),
        ]);
    }
}
