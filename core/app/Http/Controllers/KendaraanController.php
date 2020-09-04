<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KendaraanController extends Controller
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

    /**
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Kendaraan::orderBy('created_at', 'DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('ktp', function($row){
                if(!$row->ktp)
                {
                    return '-';
                }
                return $row->ktp;
            })
            ->addColumn('nama', function($row){

                    return $row->nama;
            })
            ->addColumn('kapasitas', function($row){

                    return $row->max_kapasitas. ' CBM';
            })
            ->addColumn('action', function($row){
                    $btn = '<center><a href="'. route('kendaraan.edit', $row->id) .'" class="btn btn-secondary btn-sm mr-5"><i class="si si-note"></i></a>';
                    $btn .= '<button type="button" onClick="hapus(\''.$row->id.'\')" class="btn btn-secondary btn-sm"><i class="si si-trash"></i></button></center>';
                    return $btn;
            })
            ->rawColumns(['nama', 'alamat', 'action', 'kapasitas'])
            ->make(true);
        }
        return view('kendaraan.index');
    }


    public function tambah(){

        return view('kendaraan.tambah');
    }

    public function simpan(Request $request){

        $rules = [
            'jenis' => 'required',
            'no_polisi' => 'required',
            'tipe' => 'required',
            'max_kapasitas' => 'required',
        ];

        $pesan = [
            'jenis.required' => 'Jenis Kendaraan Wajib Diisi!',
            'no_polisi.required' => 'No Polisi Wajib Diisi!',
            'tipe.required' => 'Tipe Kendaraan Wajib Diisi!',
            'max_kapasitas.required' => 'Kapasitas Maksimum Wajib Diisi!',
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
                $data = array(
                    'jenis' => $request->jenis,
                    'no_polisi' => $request->no_polisi,
                    'tipe' => $request->tipe,
                    'max_kapasitas' => $request->max_kapasitas,
                );
               Kendaraan::create($data);

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

    public function update(Request $request){

        $rules = [
            'jenis' => 'required',
            'no_polisi' => 'required',
            'tipe' => 'required',
            'max_kapasitas' => 'required',
        ];

        $pesan = [
            'jenis.required' => 'Jenis Kendaraan Wajib Diisi!',
            'no_polisi.required' => 'No Polisi Wajib Diisi!',
            'tipe.required' => 'Tipe Kendaraan Wajib Diisi!',
            'max_kapasitas.required' => 'Kapasitas Maksimum Wajib Diisi!',
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
                $kendaraan = Kendaraan::find($request->kendaraan_id);
                $kendaraan->update([
                    'jenis' => $request->jenis,
                    'no_polisi' => $request->no_polisi,
                    'tipe' => $request->tipe,
                    'max_kapasitas' => $request->max_kapasitas,
                ]);
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'error' => $e,
                    'pesan' => 'Error Menyimpan Data',
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    public function edit($id){
        $data = Kendaraan::find($id);
        return view('kendaraan.edit', compact('data'));
    }


    public function hapus($id)
    {
        $data = Kendaraan::destroy($id);
        if($data){
            return response()->json([
                'fail' => false,
            ]);
        }
    }
}
