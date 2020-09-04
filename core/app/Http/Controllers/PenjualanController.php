<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pengiriman;
use App\Models\Transaksi;
use App\Models\TransaksiBayar;
use App\Models\Produk;
use App\Models\ProdukVariasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Storage;
use Session;
use Illuminate\Support\Facades\DB;
use Cart;
use Carbon\Carbon;
use App\Utils\PenjualanUtil;
class PenjualanController extends Controller
{
    /**
     * Only Authenticated users for "admin" guard
     * are allowed.
     *
     * @return void
     */
    public function __construct(PenjualanUtil $penjualanUtil)
    {
        $this->middleware('auth');
        $this->penjualanUtil = $penjualanUtil;
    }

    /**
     * Menampilkan data riwayat penjualan berdasarkan periode waktu tertentu.
     * Waktu default adalah transaksi selama 7 hari (1 minggu) terakhir
     *
     * @return \Illuminate\Http\Response
     */
    public function riwayat(Request $request)
    {
        $start = Carbon::now()->startOfWeek();
        $end =  Carbon::now()->endOfWeek();
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
            $query = $request->get('query');

            $data = Transaksi::where('tipe', 'jual')->whereBetween('tgl_transaksi', [$start, $end])->orderBy('tgl_transaksi', 'DESC')->paginate(16);
            $penjualan_kotor = 'Rp '.number_format($data->sum('final_total'),0,",",".");

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
                $nav = 'Data Penjualan 0 - 0 Dari 0';
            }else{
                $nav = 'Data Penjualan '. $data->toArray()['from'] .' - '.$data->toArray()['to'] .' Dari '.$data->toArray()['total'];
            }

            return response()->json([
                'penjualan_kotor' => $penjualan_kotor,
                'total_transaksi' => $data->total(),
                'current_page' => $data->toArray()['current_page'],
                'next_page' => $next,
                'prev_page' => $prev,
                'navigasi' => $nav,
                'html' => view('penjualan.include.riwayat_data', compact('data'))->render(),
            ]);
        }
        $data = Transaksi::where('tipe', 'jual')->whereBetween('tgl_transaksi', [$start, $end])->orderBy('tgl_transaksi', 'DESC')->paginate(16);
        $penjualan_kotor = 'Rp '.number_format(Transaksi::where('tipe', 'jual')->whereBetween('tgl_transaksi', [$start, $end])->sum('final_total'),0,",",".");
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
            $navigasi['nav'] = 'Data Penjualan 0 - 0 Dari 0';
        }else{
            $navigasi['nav'] = 'Data Penjualan '. $data->toArray()['from'] .' - '.$data->toArray()['to'] .' Dari '.$data->toArray()['total'];
        }

        return view('penjualan.riwayat', compact('data', 'total_transaksi', 'penjualan_kotor', 'navigasi'));
    }


    public function index()
    {
        Cart::session(Session::get('bisnis.bisnis_id').'-penjualan')->clear();
        $tgl_transaksi = Carbon::now()->format('d-m-Y');
        $tgl_kirim = Carbon::now()->addDays(1)->format('d-m-Y');
        return view('penjualan.form', compact('tgl_transaksi', 'tgl_kirim'));
    }

    public function simpan(Request $request)
    {
        // dd($this->penjualanUtil->hitungVolume($request->penjualan));
        $rules = [
            'sales' => 'required',
            'pelanggan' => 'required',
            'tgl_transaksi' => 'required',
            'jml_bayar' => 'required',
        ];

        $pesan = [
            'sales.required' => 'Sales Wajib Diisi!',
            'pelanggan.required' => 'Pelanggan Wajib Diisi!',
            'tgl_transaksi.required' => 'Tanggal Penjualan Wajib Diisi!',
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
            }else if($request->jml_bayar < $request->final_total)
            {
                $status_pembayaran = 'Sebagian';
            }else{
                $status_pembayaran = 'Belum Dibayar';
            }
            DB::beginTransaction();
            try{
                // Simpan Data Transakasi
                $trans = array(
                    'tipe' => 'jual',
                    'status'=> 'final',
                    'bayar_status' => $status_pembayaran,
                    'sales_id' => $request->sales,
                    'pengiriman_status' => 0,
                    'invoice_no' => get_no_transaksi('jual'),
                    'pelanggan_id' => $request->pelanggan,
                    'tgl_transaksi' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                    'total' => $request->sub_total,
                    'diskon_tipe' => $request->jenis_diskon,
                    'diskon_nilai' => $request->diskon,
                    'final_total' => $request->final_total,
                    'dibuat_oleh' => auth()->user()->id,
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
                $this->penjualanUtil->createOrUpdatePenjualan($transaksi, $request->penjualan);

                if($request->status == 'final')
                {
                    foreach($request->penjualan as $produk)
                    {
                        $this->penjualanUtil->kurangiStokProduk(
                            $produk['produk_id'],
                            $produk['variasi_id'],
                            $produk['qty'],
                        );
                    }
                }
                // Simpan Data Pengiriman Produk
                $kirim = array(
                    'pelanggan_id' => $request->pelanggan,
                    'tgl_kirim' => Carbon::createFromFormat('d-m-Y', $request->tgl_pengiriman)->format('Y-m-d H:i:s'),
                    'transaksi_id' => $transaksi->id,
                    'status' => 0,
                    'beban' => $this->penjualanUtil->hitungVolume($request->penjualan),
                );

                $pengiriman = Pengiriman::create($kirim);

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
                'detail_url' => route('penjualan.detail', $transaksi->id)
            ]);
        }
    }

    public function edit($transaksi_id)
    {
        Cart::session('penjualan')->clear();
        $transaksi = Transaksi::find($transaksi_id);
        $produk = Penjualan::where('transaksi_id', $transaksi_id)->get();
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
                'price' => $p->hrg_jual,
                'quantity' => $p->quantity,
                'attributes' => array(
                    'produk_id' => $p->produk_id,
                    'kelola_stok' => $p->variasi->kelola_stok,
                    'volume' => $p->variasi->volume*$p->quantity,
                )
            );
            if($p->variasi->kelola_stok == 1)
            {
                $data['attributes']['satuan_id'] = $p->variasi->satuan_id;
                $data['attributes']['satuan_nama'] = $p->variasi->satuan->nama;
                $data['attributes']['pembelian_id'] = $p->id;
            }
            Cart::session('penjualan')->add($data);
        }

        $pembayaran = TransaksiBayar::where('transaksi_id', $transaksi_id)->get();
        return view('penjualan.edit', compact('transaksi', 'produk', 'pembayaran'));
    }

    public function update(Request $request)
    {
        $rules = [
            'sales' => 'required',
            'pelanggan' => 'required',
            'tgl_transaksi' => 'required',
            'jml_bayar' => 'required',
        ];

        $pesan = [
            'sales.required' => 'Sales Wajib Diisi!',
            'pelanggan.required' => 'Pelanggan Wajib Diisi!',
            'tgl_transaksi.required' => 'Tanggal Penjualan Wajib Diisi!',
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
            }else if($request->jml_bayar < $request->final_total)
            {
                $status_pembayaran = 'Sebagian';
            }else{
                $status_pembayaran = 'Belum Dibayar';
            }

            DB::beginTransaction();
            try{
                // Simpan Data Transakasi
                $trans = array(
                    'tipe' => 'jual',
                    'status'=> 'final',
                    'invoice_no' => get_no_transaksi('jual'),
                    'bayar_status' => $status_pembayaran,
                    'sales_id' => $request->sales,
                    'pengiriman_status' => 0,
                    'pelanggan_id' => $request->pelanggan,
                    'tgl_transaksi' => Carbon::createFromFormat('d-m-Y', $request->tgl_transaksi)->format('Y-m-d H:i:s'),
                    'total' => $request->sub_total,
                    'diskon_tipe' => $request->jenis_diskon,
                    'diskon_nilai' => $request->diskon,
                    'final_total' => $request->final_total,
                    'dibuat_oleh' => auth()->user()->id,
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
                $this->penjualanUtil->createOrUpdatePenjualan($transaksi, $request->penjualan);

                foreach($request->penjualan as $produk)
                {
                    $this->penjualanUtil->kurangiStokProduk(
                        $produk['produk_id'],
                        $produk['variasi_id'],
                        $produk['qty'],
                    );
                }


                // Simpan Data Pengiriman Produk
                $kirim = array(
                    'transaksi_id' => $transaksi->id,
                    'tgl_kirim' => Carbon::createFromFormat('d-m-Y', $request->tgl_pengiriman)->format('Y-m-d H:i:s'),
                    'pelanggan_id' => $request->pelanggan,
                    'status' => 0,
                    'beban' => 0,
                );

                $pengiriman = Pengiriman::create($kirim);

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
                'detail_url' => route('penjualan.detail', $transaksi->id)
            ]);
        }
    }

    public function detail($transaksi_id)
    {
        $transaksi = Transaksi::find($transaksi_id);
        $produk = Penjualan::where('transaksi_id', $transaksi_id)->get();
        $pembayaran = TransaksiBayar::where('transaksi_id', $transaksi_id)->get();

        return view('penjualan.detail', compact('transaksi', 'produk', 'pembayaran'));
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

    public function addCart(Request $request)
    {
        if (request()->ajax()) {
            // dd($request->all());
            $penjualan = Cart::session('penjualan');
            $row_count = $request->row_count;
            for ($i = 0; $i < count($request->produk); $i++)
            {
                if($request->produk[$i]['qty'] > 0)
                {
                    if ($penjualan->has($request->produk[$i]['variasi_id'])) {
                        Cart::update($request->produk[$i]['variasi_id'], [
                            'quantity' => array(
                                'relative' => false,
                                'value' => $request->produk[$i]['qty']
                            ),
                            'attributes' => array(
                                'produk_id' =>$request->produk[$i]['produk_id'],
                                'kelola_stok' =>$request->produk[$i]['kelola_stok'],
                                'volume' => $request->produk[$i]['qty']*$request->produk[$i]['volume'],
                            )
                        ]);
                    }else{
                        $data = array(
                            'id' => $request->produk[$i]['variasi_id'],
                            'name' => get_namaProduk($request->produk[$i]['produk_nama'],$request->produk[$i]['variasi_nama']),
                            'price' => $request->produk[$i]['hrg'],
                            'quantity' => $request->produk[$i]['qty'],
                            'attributes' => array(
                                'produk_id' =>$request->produk[$i]['produk_id'],
                                'kelola_stok' =>$request->produk[$i]['kelola_stok'],
                                'volume' => $request->produk[$i]['qty']*$request->produk[$i]['volume'],
                            )
                        );
                        if($request->produk[$i]['kelola_stok'] == 1)
                        {
                            $data['attributes']['satuan_id'] = $request->produk[$i]['satuan_id'];
                            $data['attributes']['satuan_nama'] = $request->produk[$i]['satuan_nama'];
                            $data['attributes']['max_stok'] = get_stok_max($request->produk[$i]['variasi_id']);
                        }
                        $penjualan->add($data);
                    }
                }else{
                    $penjualan->remove($request->produk[$i]['variasi_id']);
                }
            }

            return response()->json([
                'fail' => false,
                'total_item' => $penjualan->getContent()->count(),
                'total' => $penjualan->getTotal(),
                'sub_total' => $penjualan->getSubTotal(),
                'html' => view('penjualan.include.entry_row')->render(),
            ]);
        }
    }

    public function updateCart(Request $request)
    {
        if (request()->ajax()) {
            $variasi = ProdukVariasi::find($request->variasi_id);

            $attributes = array();
            if($variasi->kelola_stok == 1){
                $attributes['produk_id'] = $variasi->produk_id;
                $attributes['satuan_id'] = $variasi->satuan_id;
                $attributes['satuan_nama'] = $variasi->satuan->nama;
                $attributes['kelola_stok'] = 1;
                $attributes['volume'] = $request->qty*$variasi->volume;
            }else{
                $attributes['produk_id'] = $variasi->produk_id;
                $attributes['satuan_id'] = '';
                $attributes['satuan_nama'] = '';
                $attributes['kelola_stok'] = 0;
                $attributes['volume'] = $request->qty * $variasi->volume;
            }
            $update = Cart::session('penjualan')
            ->update($request->variasi_id,[
                'quantity' =>array(
                    'relative' => false,
                    'value' => $request->qty
                ),
                'price' => $request->hrg,
                'attributes' => $attributes
            ]);

            return response()->json([
                'fail' => false,
                'total_item' =>  Cart::session('penjualan')->getContent()->count(),
                'total' => Cart::session('penjualan')->getTotal(),
                'sub_total' => Cart::session('penjualan')->getSubTotal(),
                'html' => view('penjualan.include.entry_row')->render(),
            ]);
        }
    }

    public function deleteCart(Request $request)
    {
        if($request->ajax())
        {
            $hapus = Cart::session('penjualan')->remove($request->variasi_id);
            if($hapus)
            {
                return response()->json([
                    'fail' => false,
                    'total_item' =>  Cart::session('penjualan')->getContent()->count(),
                    'total' => Cart::session('penjualan')->getTotal(),
                    'sub_total' => Cart::session('penjualan')->getSubTotal(),
                    'html' => view('penjualan.include.entry_row')->render(),
                ]);
            }
        }
    }

    public function getCart()
    {
        $penjualan = Cart::session('penjualan');
        return response()->json([
            'fail' => false,
            'total_item' => $penjualan->getContent()->count(),
            'total' => $penjualan->getTotal(),
            'sub_total' => $penjualan->getSubTotal(),
            'html' => view('penjualan.include.entry_row')->render(),
        ]);
    }

    public function getProduk(Request $request)
    {
        if($request->ajax())
        {
            $kategori_id = $request->get('kategori_id');
            $merk_id = $request->get('merk_id');
            $cari = $request->get('keyword');

            $produk = Produk::where('kategori_id', 'like', '%' . $kategori_id . '%')
            ->where('merk_id', 'like', '%' . $merk_id . '%')
            ->where('nama', 'like', '%' . $cari . '%')
            ->paginate(3);
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
                'html' => view('penjualan.include.produk_list', compact('produk'))->render(),
            ]);

        }
    }
}
