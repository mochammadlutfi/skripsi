<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukVariasi;
use App\Models\VariasiDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Storage;
use App\Repository\Eloquent\ProdukRepository;

class ProdukController extends Controller
{
    /**
     * Only Authenticated users are allowed.
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

            $data = Produk::where('kategori_id', 'like', '%' . $kategori_id . '%')
            ->where('merk_id', 'like', '%' . $merk_id . '%')
            ->where('nama', 'like', '%' . $cari . '%')
            ->paginate(12);
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
                $nav = 'Data Produk 0 - 0 Dari 0';
            }else{
                $nav = 'Data Produk '. $data->toArray()['from'] .' - '.$data->toArray()['to'] .' Dari '.$data->toArray()['total'];
            }

            return response()->json([
                'fail' => false,
                'navigasi' => $nav,
                'tipe' => $request->get('tipe'),
                'total' => $data->Total(),
                'current_page' => $data->toArray()['current_page'],
                'next_page' => $next,
                'prev_page' => $prev,
                'html' => view('produk.include.data', compact('data'))->render(),
            ]);
        }

        $data = Produk::orderBy('nama', 'ASC')->paginate(12);

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
            $navigasi['nav'] = 'Data Produk 0 - 0 Dari 0';
        }else{
            $navigasi['nav'] = 'Data Produk '. $data->toArray()['from'] .' - '.$data->toArray()['to'] .' Dari '.$data->toArray()['total'];
        }


        return view('produk.index', compact('data', 'navigasi'));
    }

    public function tambah(){
        return view('produk.tambah');
    }

    public function simpan(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'kategori' => 'required',
            'merk' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Produk Wajib Diisi!',
            'kategori.required' => 'Kategori Produk Wajib Diisi!',
            'merk.required' => 'Merk Produk Wajib Diisi!',
        ];

        if($request->variasi == 0)
        {
            $rules['hrg_modal'] = 'required';
            $rules['hrg_jual'] = 'required';

            $pesan['hrg_modal.required'] = 'Harga Modal Produk Wajib Diisi!';
            $pesan['hrg_jual.required'] = 'Harga Jual Produk Wajib Diisi!';

            if($request->has('kelola_stok'))
            {
                $rules['unit'] = 'required';
                $rules['min_stok'] = 'required';

                $pesan['unit.required'] = 'Satuan Unit Stok Produk Wajib Diisi!';
                $pesan['min_stok.required'] = 'Minimum Stok Produk Wajib Diisi!';
            }
        }

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();

            try{
                // Insert Produk

                $data = array(
                    'nama' => $request->nama,
                    'kategori_id' => $request->kategori,
                    'merk_id' => $request->merk,
                    'variasi' => $request->total_variasi,
                );

                if($request->hasfile('foto'))
                {
                    $foto_file = $request->file('foto');
                    $foto_path = 'produk';
                    $foto = Storage::disk('umum')->put($foto_path, $foto_file);
                    $data['foto'] = 'uploads/'.$foto;
                }

                $produk = Produk::create($data);
                // dd($produk);
                if($request->total_variasi == 1)
                {
                    $variasiData = array(
                        'produk_id' => $produk->id,
                        'nama' => '',
                        'sku' => $request->sku,
                        'hrg_modal' => $request->hrg_modal,
                        'hrg_jual' => $request->hrg_jual,
                        'volume' => $request->volume,
                    );
                    if($request->has('kelola_stok'))
                    {
                        $variasiData['kelola_stok'] = 1;
                        $variasiData['satuan_id'] = $request->unit;
                        $variasiData['min_stok'] = $request->min_stok;
                    }

                    $variasi = ProdukVariasi::create($variasiData);

                    $variasiDetail = array(
                        'produk_id' => $produk->id,
                        'variasi_id' => $variasi->id,
                        'qty_tersedia' => 0
                    );

                    VariasiDetail::insert($variasiDetail);

                }else{
                    // dd($request->variasi);
                    foreach ($request->variasi as $d) {
                        $variasiData = array(
                            'produk_id' => $produk->id,
                            'nama' => $d['nama'],
                            'sku' => $d['sku'],
                            'hrg_modal' => $d['hrg_modal'],
                            'hrg_jual' => $d['hrg_jual'],
                            'kelola_stok' => $d['kelola_stok'],
                            'volume' => $d['volume'],
                        );
                        if($d['kelola_stok'])
                        {
                            $variasiData['min_stok'] = $d['min_stok'];
                            $variasiData['satuan_id'] = $d['satuan_id'];
                        }
                        $variasi = ProdukVariasi::create($variasiData);

                        $variasiDetail = array(
                            'produk_id' => $produk->id,
                            'variasi_id' => $variasi->id,
                            'qty_tersedia' => 0
                        );

                        VariasiDetail::insert($variasiDetail);
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

    public function edit($produk_id)
    {
        $produk = Produk::find($produk_id);
        if( $produk->produk_variasi()->count() > 1)
        {
            $variasi = ProdukVariasi::select('produk_variasi.id as id', 'produk_variasi.nama as nama', 'produk_variasi.sku as sku',
            'produk_variasi.hrg_modal as hrg_modal', 'produk_variasi.hrg_jual as hrg_jual',
            'produk_variasi.kelola_stok as kelola_stok','produk_variasi.min_stok as min_stok','produk_variasi.produk_id  as produk_id',
            's.id as satuan_id', 's.nama as satuan_nama')
            ->join('satuan as s', 's.id', '=', 'produk_variasi.satuan_id')->where('produk_id',$produk_id)->get();
            $row_count = 0;
            // dd($variasi);
            return view('produk.edit2', compact('produk', 'variasi', 'row_count'));
        }else{
            $variasi = $produk->produk_variasi()->first();
            return view('produk.edit1', compact('produk', 'variasi'));
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'kategori' => 'required',
            'merk' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Produk Wajib Diisi!',
            'kategori.required' => 'Kategori Produk Wajib Diisi!',
            'merk.required' => 'Merk Produk Wajib Diisi!',
        ];

        if($request->variasi == 1)
        {
            $rules['hrg_modal'] = 'required';
            $rules['hrg_jual'] = 'required';

            $pesan['hrg_modal.required'] = 'Harga Modal Produk Wajib Diisi!';
            $pesan['hrg_jual.required'] = 'Harga Jual Produk Wajib Diisi!';

            if($request->has('kelola_stok'))
            {
                $rules['unit'] = 'required';
                $rules['stok_awal'] = 'required';
                $rules['min_stok'] = 'required';

                $pesan['unit.required'] = 'Satuan Unit Stok Produk Wajib Diisi!';
                $pesan['stok_awal.required'] = 'Stok Awal Produk Wajib Diisi!';
                $pesan['min_stok.required'] = 'Minimum Stok Produk Wajib Diisi!';
            }
        }
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{
                // Update Produk

                if($request->has('variasihapus'))
                {
                    $variasi = $request->total_variasi - count($request->variasihapus);
                }else{
                    $variasi = $request->total_variasi;
                }
                $produk = Produk::find($request->produk_id);

                if(!empty($produk->foto))
                {
                    if($request->hasfile('foto'))
                    {
                        // Hapus Foto
                        Storage::disk('umum')->delete($produk->foto);
                        $foto_file = $request->file('foto');
                        $foto = Storage::disk('umum')->put('produk', $foto_file);
                        $produk->foto = 'uploads/'.$foto;
                    }
                }else{
                    if($request->hasfile('foto'))
                    {
                        $foto_file = $request->file('foto');
                        $foto_path = 'produk';
                        $foto = Storage::disk('umum')->put($foto_path, $foto_file);
                        $produk->foto = 'uploads/'.$foto;
                    }
                }

                $produk->nama = $request->nama;
                $produk->kategori_id = $request->kategori;
                $produk->merk_id = $request->merk;
                $produk->variasi = $variasi;
                $produk->save();

                $variasi = array();
                if($request->total_variasi <= 1)
                {
                    $variasi = ProdukVariasi::find($request->variasi_id);
                    $variasi->nama = '';
                    $variasi->sku = $request->sku;
                    $variasi->hrg_modal = $request->hrg_modal;
                    $variasi->hrg_jual = $request->hrg_jual;
                    $variasi->volume = $request->volume;
                    if($request->has('kelola_stok'))
                    {
                        $variasi->kelola_stok = 1;
                        $variasi->satuan_id = $request->unit;
                        $variasi->min_stok = $request->min_stok;
                    }else{
                        $variasi->kelola_stok = 0;
                    }
                    $variasi->save();
                }
                else{
                    $i = 0;
                    foreach ($request->variasi as $d){
                        if(isset($d['variasi_id']))
                        {
                            $variasi = ProdukVariasi::find($d['variasi_id']);
                            $variasi->produk_id = $produk->id;
                            $variasi->nama = $d['nama'];
                            $variasi->sku = $d['sku'];
                            $variasi->hrg_modal = $d['hrg_modal'];
                            $variasi->hrg_jual = $d['hrg_jual'];
                            $variasi->volume = $d['volume'];
                            if($request->variasi[$i]['kelola_stok'] == 1)
                            {
                                $variasi->kelola_stok = 1;
                                $variasi->satuan_id = $d['satuan_id'];
                                $variasi->min_stok = $d['min_stok'];
                            }else{
                                $variasi->kelola_stok = 0;
                                $variasi->satuan_id = null;
                                $variasi->min_stok = null;
                                // dd('kasd');
                            }
                            $variasi->save();
                        }else{
                            $variasi = new ProdukVariasi();
                            $variasi->produk_id = $produk->id;
                            $variasi->nama = $d['nama'];
                            $variasi->sku = $d['sku'];
                            $variasi->hrg_modal = $d['hrg_modal'];
                            $variasi->hrg_jual = $d['hrg_jual'];
                            $variasi->volume = $d['volume'];
                            if($request->variasi[$i]['kelola_stok'] == 1)
                            {
                                $variasi->kelola_stok = 1;
                                $variasi->satuan_id = $d['satuan_id'];
                                $variasi->min_stok = $d['min_stok'];
                            }else{
                                dd('kasd');
                                $variasi->kelola_stok = 0;
                                $variasi->satuan_id = null;
                                $variasi->min_stok = null;
                            }
                            $variasi->save();

                            $detail = new VariasiDetail();
                            $detail->produk_id = $produk->id;
                            $detail->variasi_id = $variasi->id;
                            $detail->qty_tersedia = 0;
                            $detail->save();

                        }
                        $i++;
                    }
                    if($request->has('variasihapus'))
                    {
                        ProdukVariasi::destroy($request->variasihapus);
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

    public function hapus($id)
    {
        DB::beginTransaction();
            try{
        $data = Produk::destroy($id);
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

    public function json(Request $request)
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
                'html' => view('penjualan.include.produk_list', compact('produk'))->render(),
            ]);

        }
    }
}
