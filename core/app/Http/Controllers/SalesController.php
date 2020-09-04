<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SalesController extends Controller
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
            $data = Sales::orderBy('created_at', 'DESC')->get();
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
            ->addColumn('kontak', function($row){
                if(!$row->telp)
                {
                    $telp = '-';
                }else{
                    $telp = $row->telp;
                }
                if(!$row->hp)
                {
                    $hp = '-';
                }else{
                    $hp = $row->hp;
                }
                $kontak  = '<span class="text-bold mr-3">No. Telp</span>: '.$telp.'<br>';
                $kontak .= '<span class="text-bold mr-3">No. HP </span>: '.$hp;

                return $kontak;
            })
            ->addColumn('alamat', function($row){
                if($row->daerah_id)
                {
                    $alamat = $row->alamat.'<br>';
                    $alamat  .= ucwords(strtolower($row->daerah->urban)).',';
                    $alamat .= ucwords(strtolower($row->daerah->sub_district)).', ';
                    $alamat .= ucwords(strtolower($row->daerah->city)).', ';
                    $alamat .= ucwords(strtolower($row->daerah->provinsi->province_name));
                }else{
                    $alamat = '-';
                }
                return $alamat;
            })
            ->addColumn('action', function($row){
                    $btn = '<center><a href="'. route('sales.edit', $row->id) .'" class="btn btn-secondary btn-sm mr-5"><i class="si si-note"></i></a>';
                    $btn .= '<button type="button" onClick="hapus(\''.$row->id.'\')" class="btn btn-secondary btn-sm"><i class="si si-trash"></i></button></center>';
                    return $btn;
            })
            ->rawColumns(['nama', 'alamat', 'action', 'kontak'])
            ->make(true);
        }
        return view('sales.index');
    }


    public function tambah(){

        return view('sales.tambah');
    }

    public function simpan(Request $request){

        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'wilayah' => 'required',
            'kd_pos' => 'required',
            'ktp' => 'required',
            'hp' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'kd_pos.required' => 'Kode Pos Wajib Diisi!',
            'ktp.required' => 'No. KTP Wajib Diisi!',
            'hp.required' => 'No. Handphone Wajib Diisi!',
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
                    'nama' => $request->nama,
                    'telp' => $request->telp,
                    'ktp' => $request->ktp,
                    'hp' => $request->hp,
                    'email' => $request->email,
                    'daerah_id' => $request->wilayah,
                    'alamat' => $request->alamat,
                    'keterangan' => $request->keterangan,
                );
               Sales::create($data);

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
            'nama' => 'required',
            'alamat' => 'required',
            'wilayah' => 'required',
            'kd_pos' => 'required',
            'ktp' => 'required',
            'hp' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'kd_pos.required' => 'Kode Pos Wajib Diisi!',
            'ktp.required' => 'No. KTP Wajib Diisi!',
            'hp.required' => 'No. Handphone Wajib Diisi!',
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
                $sales = Sales::find($request->sales_id);
                $sales->update([
                    'nama' => $request->nama,
                    'telp' => $request->telp,
                    'ktp' => $request->ktp,
                    'hp' => $request->hp,
                    'email' => $request->email,
                    'daerah_id' => $request->wilayah,
                    'alamat' => $request->alamat,
                    'keterangan' => $request->keterangan,
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
        $data = Sales::find($id);
        $wilayah  = ucwords(strtolower($data->daerah->urban)).',';
        $wilayah .= ucwords(strtolower($data->daerah->sub_district)).', ';
        $wilayah .= ucwords(strtolower($data->daerah->city)).', ';
        $wilayah .= ucwords(strtolower($data->daerah->provinsi->province_name));
        $data->kd_pos = $data->daerah->postal_code;
        return view('sales.edit', compact('data', 'wilayah'));
    }

    public function json(Request $request)
    {
        if(!isset($request->searchTerm)){
            $fetchData =  Sales::orderBy('created_at', 'DESC')->get();
        }else{
            $cari = $request->searchTerm;
            $fetchData = Sales::where('nama','LIKE',  '%' . $cari .'%')->orderBy('created_at', 'DESC')->get();
        }
        //   dd($fetchData);
          $data = array();
          foreach($fetchData as $row) {
            $data[] = array("id" =>$row->id, "text"=>$row->nama);
          }
          return response()->json($data);
    }

    public function hapus($id)
    {
        $data = Sales::destroy($id);
        if($data){
            return response()->json([
                'fail' => false,
            ]);
        }
    }
}
