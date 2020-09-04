<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Produk;
use App\Models\ProdukVariasi;
use App\Models\Transaksi;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Repository\Eloquent\ProdukRepository;
// use App\Utils\ProdukUtil;
// use App\Utils\TransaksiUtil;

use Session;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
class StokAwalController extends Controller
{
    /**
     * Only Authenticated users for "admin" guard
     * are allowed.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($produk_id)
    {
        $produk = Produk::find($produk_id);
        $variasi = ProdukVariasi::where('produk_id', $produk_id)->where('kelola_stok', 1)->get();

        $transaksi = Transaksi::where('stok_awal_produk_id', $produk_id)
        ->where('tipe', 'stok_awal')
        ->with(['pembelian'])
        ->get();

        $pembelian = [];
        foreach ($transaksi as $trans) {
            $pembelian_line = [];

            foreach ($trans->pembelian as $pembelian_data) {
                if (!empty($pembelian_line[$pembelian_data->variasi_id])) {
                    $k = count($pembelian_line[$pembelian_data->variasi_id]);
                } else {
                    $k = 0;
                    $pembelian_line[$pembelian_data->variasi_id] = [];
                }

                //Show only remaining quantity for editing opening stock.
                $pembelian_line[$pembelian_data->variasi_id][$k]['qty'] = $pembelian_data->quantity_remaining;
                $pembelian_line[$pembelian_data->variasi_id][$k]['pembelian_harga'] = $pembelian_data->purchase_price;
                $pembelian_line[$pembelian_data->variasi_id][$k]['purchase_line_id'] = $pembelian_data->id;
            }
            $pembelian[$trans->warung_id] = $pembelian_line;
        }

        return view('inventaris.stok_awal', compact(
            'produk',
            'variasi',
            'pembelian',
        ));
    }

    public function simpan(Request $request)
    {
        // dd($request->all());
        $produk = Produk::find($request->produk_id);
        $stok_awal = $request->stok;
        $mitra_id = auth()->guard('mitra')->user()->id;
        $bisnis_id = Session::get('bisnis.bisnis_id');
        $outlet_id = Session::get('bisnis.outlet_id');
        // dd($toko_id);
        $tgl_transaksi = Carbon::now();

        //Buat pembelian_data array
        //$k adalah variasi_id
        DB::beginTransaction();
        try{
            foreach ($stok_awal as $k => $v) {

                $hrg_beli = $this->produkUtil->num_uf(trim($v['hrg_beli']));
                $qty_remaining = $this->produkUtil->num_uf(trim($v['qty']));
                $purchase_line = null;
                $total_pembelian = 0;

                if (isset($v['pembelian_id'])) {
                    dd('kasdnlas');
                    $purchase_line = Pembelian::findOrFail($v['pembelian_id']);

                    $qty_remaining = $qty_remaining;

                    if ($qty_remaining != 0) {
                        //Calculate transaction total
                        $total_pembelian += ($hrg_beli * $qty_remaining);

                        $updated_purchase_line_ids[] = $purchase_line->id;

                        $old_qty = $purchase_line->quantity;

                        $this->produkUtil->updateProdukQty($bisnis_id, $outlet_id, $produk->id, $k, $qty_remaining, $old_qty);
                    }
                }else {
                    if ($qty_remaining != 0) {

                        // Buat data pembelian terbaru
                        $purchase_line = new Pembelian();
                        $purchase_line->produk_id = $produk->id;
                        $purchase_line->variasi_id = $k;

                        $this->produkUtil->updateProdukQty($bisnis_id, $outlet_id, $produk->id, $k, $qty_remaining);

                        // Menghitung Total Transaksi
                        $total_pembelian += ($hrg_beli * $qty_remaining);
                    }
                }
                if (!is_null($purchase_line)) {
                    $purchase_line->quantity = $qty_remaining;
                    $purchase_line->hrg_beli = $hrg_beli;
                    $purchase_line->sub_total = $qty_remaining*$hrg_beli;
                    $purchase_lines[] = $purchase_line;
                }

                //Buat Transakasi & Data Pembelian
                if (!empty($purchase_lines)) {
                    $is_transaksi_baru = false;

                    $transaksi = Transaksi::where('tipe', 'stok_awal')
                            ->where('bisnis_id', $bisnis_id)
                            ->where('outlet_id', $outlet_id)
                            ->where('stok_awal_produk_id', $produk->id)
                            ->first();
                    if (!empty($transaksi)) {
                        $transaksi->final_total = $total_pembelian;
                        $transaksi->update();
                    } else {
                        $is_transaksi_baru = true;
                        $transaksi = Transaksi::create(
                            [
                                'tipe' => 'stok_awal',
                                'stok_awal_produk_id' => $produk->id,
                                'status' => 'diterima',
                                'bisnis_id' => $bisnis_id,
                                'outlet_id' => $outlet_id,
                                'tgl_transaksi' => $tgl_transaksi,
                                'total' => $total_pembelian,
                                'final_total' => $total_pembelian,
                                'payment_status' => 'lunas',
                                'mitra_id' => $mitra_id
                            ]
                        );
                    }
                    $transaksi->pembelian()->saveMany($purchase_lines);
                }
            }
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
    }

}
