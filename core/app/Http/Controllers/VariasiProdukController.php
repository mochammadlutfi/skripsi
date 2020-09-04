<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukVariasi;
use App\Models\VariasiDetail;
use App\Models\Satuan;
use Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VariasiProdukController extends Controller
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

    /**
     * Menambahkan Variasi Kedalam Produk
     * are allowed.
     *
     * @return void
     */
    public function tambah(Request $request)
    {
        // dd($request->all());
        $rules = [
            'variasi_nama' => 'required',
            'variasi_hrg_modal' => 'required',
            'variasi_hrg_jual' => 'required',
        ];

        $pesan = [
            'variasi_nama.required' => 'Nama Wajib Diisi!',
            'variasi_hrg_modal.required' => 'Harga Modal Wajib Diisi!',
            'variasi_hrg_jual.required' => 'Harga Jual Wajib Diisi!',
        ];

        if($request->has('variasi_kelola_stok'))
        {
            $rules['variasi_unit'] = 'required';
            $rules['variasi_min_stok'] = 'required';

            $pesan['variasi_unit.required'] = 'Satuan Unit Stok Produk Wajib Diisi!';
            $pesan['variasi_min_stok.required'] = 'Minimum Stok Produk Wajib Diisi!';
        }

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            // dd($request->all());
            if($request->has('variasi_kelola_stok'))
            {
                $kelola_stok = 1;
            }else{
                $kelola_stok = 0;
            }
            $variasi = array();
            $row_count = $request->total_variasi;
            if($request->has('old_sku'))
            {
                $row_count = 0;
                $variasi[0] = array(
                    'id' => $request->old_variasi_id,
                    'nama' => '',
                    'sku' => $request->old_sku,
                    'hrg_modal' => $request->old_hrg_modal,
                    'hrg_jual' => $request->old_hrg_jual,
                    'kelola_stok' => $request->old_kelola_stok,
                    'volume' => $request->old_volume,
                );
                if($request->old_kelola_stok == '1')
                {
                    $satuan1 = Satuan::find($request->old_satuan_id);

                    $variasi[0]['satuan_id'] = $request->old_satuan_id;
                    $variasi[0]['satuan_nama'] = $satuan1->nama;
                    $variasi[0]['min_stok'] = $request->old_min_stok;
                }else{
                    $variasi[0]['satuan_id'] = '';
                    $variasi[0]['satuan_nama'] = '';
                    $variasi[0]['min_stok'] = '';
                }
                $variasi[1] = array(
                    'id' => null,
                    'nama' => $request->variasi_nama,
                    'sku' => $request->variasi_sku,
                    'hrg_modal' => $request->variasi_hrg_modal,
                    'hrg_jual' => $request->variasi_hrg_jual,
                    'volume' => $request->variasi_volume,
                    'kelola_stok' => $kelola_stok,
                );

                if($kelola_stok == 1)
                {
                    $satuan2 = Satuan::find($request->variasi_unit);

                    $variasi[1]['satuan_id'] = $request->variasi_unit;
                    $variasi[1]['satuan_nama'] = $satuan2->nama;
                    $variasi[1]['min_stok'] = $request->variasi_min_stok;

                }else{
                    $variasi[1]['satuan_id'] = '';
                    $variasi[1]['satuan_nama'] = '';
                    $variasi[1]['min_stok'] = '';
                }
            }else{
                $variasi[0] = array(
                    'nama' => $request->variasi_nama,
                    'sku' => $request->variasi_sku,
                    'hrg_modal' => $request->variasi_hrg_modal,
                    'hrg_jual' => $request->variasi_hrg_jual,
                    'kelola_stok' => $kelola_stok,
                    'volume' => $request->volume,
                );

                if($kelola_stok === 1)
                {
                    $satuan = Satuan::find($request->variasi_unit);
                    $variasi[0]['satuan_id'] = $request->variasi_unit;
                    $variasi[0]['satuan_nama'] = $satuan->nama;
                    $variasi[0]['min_stok'] = $request->variasi_min_stok;
                }else{
                    $variasi[0]['satuan_id'] = '';
                    $variasi[0]['satuan_nama'] = '';
                    $variasi[0]['min_stok'] = '';
                }
            }
            if($row_count > 1)
            {
                return view('produk.include.entry_variasi', compact('variasi','row_count'));
            }else{
                return view('produk.include.tbl_variasi', compact('variasi','row_count'));
            }

        }
    }

    // private function satuanNama($satuan_id)
    // {
    //     $satuan =
    //     return $satuan->nama;
    // }

    public function changeRow(Request $request){
        // dd($request->all());
        $rules = [
            'ubah_variasi_nama' => 'required',
            'ubah_variasi_hrg_modal' => 'required',
            'ubah_variasi_hrg_jual' => 'required',
        ];

        $pesan = [
            'ubah_variasi_nama.required' => 'Nama Wajib Diisi!',
            'ubah_variasi_hrg_modal.required' => 'Harga Modal Wajib Diisi!',
            'ubah_variasi_hrg_jual.required' => 'Harga Jual Wajib Diisi!',
        ];

        if($request->has('ubah_variasi_kelola_stok'))
        {
            $rules['ubah_variasi_unit'] = 'required';
            $rules['ubah_variasi_min_stok'] = 'required';

            $pesan['ubah_variasi_unit.required'] = 'Satuan Unit Stok Produk Wajib Diisi!';
            $pesan['ubah_variasi_min_stok.required'] = 'Minimum Stok Produk Wajib Diisi!';
        }

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            $row_count = $request->total_variasi;
            if($request->has('ubah_variasi_kelola_stok'))
            {
                $satuan = Satuan::find($request->ubah_variasi_unit);
                $kelola_stok = 1;
                $satuan_id = $request->ubah_variasi_unit;
                $satuan_nama = $satuan->nama;
                $min_stok = $request->ubah_variasi_min_stok;
                $show_inventaris = 'Ya';
            }else{
                $min_stok = '';
                $kelola_stok = 0;
                $satuan_id = '';
                $satuan_nama = '';
                $show_inventaris = 'Tidak';
            }
            return response()->json([
                'fail' => false,
                'row' => $request->row_ubah_variasi,
                'nama' => $request->ubah_variasi_nama,
                'sku' => $request->ubah_variasi_sku,
                'hrg_modal' => $request->ubah_variasi_hrg_modal,
                'hrg_jual' => $request->ubah_variasi_hrg_jual,
                'kelola_stok' => $kelola_stok,
                'satuan_id' => $satuan_id,
                'satuan_nama' => $satuan_nama,
                'min_stok' => $min_stok,
                'show_inventaris' => $show_inventaris,
            ]);
        }
    }

    public function jsonModal(Request $request)
    {
        $tipe = $request->tipe;
        $produk = Produk::find($request->produk_id);
        if($tipe == 'penjualan')
        {
            $produkVariasi = ProdukVariasi::where('produk_id', $request->produk_id)->get();
        }elseif($tipe == 'pembelian' || $tipe == 'returbeli')
        {
            $produkVariasi = ProdukVariasi::where('produk_id', $request->produk_id)
            ->where('kelola_stok', 1)->get();
        }
        $variasi = array();
        $i = 0;

        foreach($produkVariasi as $pv)
        {
            if($tipe == 'penjualan')
            {
                $hrg = $pv->hrg_jual;
            }elseif($tipe == 'pembelian' || $tipe == 'returbeli')
            {
                $hrg = $pv->hrg_modal;
            }
            $variasi[] = view('produk.include.modal_variasi_entry', compact('pv', 'i', 'hrg', 'tipe'))->render();
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
            // 'beban' => $produ
        ]);
    }

    public function jsonFind(Request $request)
    {
        $variasi = ProdukVariasi::find($request->variasi_id);
        $data = array(
            'fail' => false,
            'variasi_id' => $variasi->id,
            'produk_nama' => $variasi->produk->nama,
            'variasi_nama' => $variasi->produk->kategori->nama,
            'hrg_jual' => number_format($variasi->hrg_jual,0,",","."),
            'qty' => $request->qty,
            'sub_tot' => number_format($request->qty*$variasi->hrg_jual,0,",","."),
        );

        $stok = VariasiDetail::where('variasi_id',$request->variasi_id)->first();
        if($stok)
        {
            $data['max_stok'] = $stok->qty_tersedia;
        }else{
            $data['max_stok'] = 0;
        }

        return response()->json($data);
    }
}
