<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Repository\Eloquent\PegawaiRepository;
// use App\Repository\Eloquent\OutletRepository;
use Yajra\DataTables\DataTables;

class PenggunaController extends Controller
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

    /**
     * Menampilkan data index pengguna aplikasi
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if ($request->ajax()) {
            $data = $this->pegawaiRepository->getAll();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('nama', function($row){

                    return $row->nama;
            })
            ->addColumn('alamat', function($row){
                    $alamat = ucwords(strtolower($row->alamat)).'<br>';
                    $alamat .= ucwords(strtolower($row->daerah->urban)).',';
                    $alamat .= ucwords(strtolower($row->daerah->sub_district)).', ';
                    $alamat .= ucwords(strtolower($row->daerah->city)).', ';
                    $alamat .= ucwords(strtolower($row->daerah->provinsi->province_name));
                    return $alamat;
            })
            ->addColumn('action', function($row){
                    $btn = '<center><a href="'. route('mitra.pegawai.edit', $row->id) .'" class="btn btn-secondary btn-sm mr-5"><i class="si si-note"></i></a>';
                    $btn .= '<button type="button" onClick="hapus(\''.$row->id.'\')" class="btn btn-secondary btn-sm"><i class="si si-trash"></i></button></center>';
                    return $btn;
            })
            ->rawColumns(['nama', 'alamat', 'action'])
            ->make(true);
        }
        return view('pengguna.index');
    }

    public function tambah(){
        return view('pengguna.tambah');
    }

    public function simpan(Request $request){

        $rules = [
            'nama' => 'required',
            'username' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'wilayah' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
            'email.required' => 'Alamat Email Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{

            DB::beginTransaction();
            try{
                $toko = new Toko();
                $toko->nama = $request->nama;
                $toko->kontak = $request->kontak;
                $toko->daerah_id = $request->wilayah;
                $toko->kdpos = $request->kd_pos;
                $toko->alamat = $request->alamat;
                $toko->lat = $request->lat;
                $toko->lng = $request->lng;
                $toko->save();
            }catch(\Exception $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Menyimpan Data',
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    public function edit($id)
    {
        $toko = Toko::find($id);
        $d = Daerah::find($toko->daerah_id);

        $daerah  = ucwords(strtolower($d->urban)).',';
        $daerah .= ucwords(strtolower($d->sub_district)).', ';
        $daerah .= ucwords(strtolower($d->city)).', ';
        $daerah .= ucwords(strtolower($d->provinsi->province_name));
        // dd($daerah);
        return view('pengguna.edit', compact('toko', 'daerah'));
    }

    public function update(Request $request){

        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'wilayah' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Toko Wajib Diisi!',
            'alamat.required' => 'Alamat Toko Wajib Diisi!',
            'kontak.required' => 'Kontak Toko Wajib Diisi!',
            'wilayah.required' => 'Wilayah Toko Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{

            DB::beginTransaction();
            try{
                $toko = Pengguna::find($request->toko_id);
                $toko->nama = $request->nama;
                $toko->kontak = $request->kontak;
                $toko->daerah_id = $request->wilayah;
                $toko->kdpos = $request->kd_pos;
                $toko->alamat = $request->alamat;
                $toko->lat = $request->lat;
                $toko->lng = $request->lng;
                $toko->save();
            }catch(\Exception $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Menyimpan Data',
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

}
