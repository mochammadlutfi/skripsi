<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PelangganController extends Controller
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
            $data = Pelanggan::orderBy('created_at', 'DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('nama', function($row){

                    return $row->nama;
            })
            ->addColumn('kontak', function($row){
                if(!$row->telp || $row->telp == '')
                {
                    $telp = '-';
                }else{
                    $telp = $row->telp;
                }
                if(!$row->email || $row->email == '')
                {
                    $email = '-';
                }else{
                    $email = $row->email;
                }
                $kontak  = '<span class="text-bold mr-3">No. Telp</span>: '.$telp.'<br>';
                $kontak .= '<span class="text-bold mr-3">Email </span>: '.$email;

                return $kontak;
            })
            ->addColumn('perwakilan', function($row){
                if(!$row->perwakilan_nama || $row->perwakilan_nama == '')
                {
                    $nama = '-';
                }else{
                    $nama = $row->perwakilan_nama;
                }
                if(!$row->perwakilan_kontak || $row->perwakilan_kontak == '')
                {
                    $hp = '-';
                }else{
                    $hp = $row->perwakilan_kontak;
                }
                $kontak  = '<span class="text-bold mr-3">Nama</span>: '.$nama.'<br>';
                $kontak .= '<span class="text-bold mr-3">Kontak  </span>: '.$hp;

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
                    $btn = '<center><a href="'. route('pelanggan.edit', $row->id) .'" class="btn btn-secondary btn-sm mr-5"><i class="si si-note"></i></a>';
                    $btn .= '<button type="button" onClick="hapus(\''.$row->id.'\')" class="btn btn-secondary btn-sm"><i class="si si-trash"></i></button></center>';
                    return $btn;
            })
            ->rawColumns(['nama', 'alamat', 'action', 'kontak', 'perwakilan'])
            ->make(true);
        }
        return view('pelanggan.index');
    }


    public function tambah(){

        return view('pelanggan.tambah');
    }

    public function simpan(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'wilayah' => 'required',
            'lat' => 'required',
            'lng' => 'required'
        ];

        $pesan = [
            'nama.required' => 'Nama Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'lat.required' => 'Latitude Wajib Diisi!',
            'lng.required' => 'Langtitude Wajib Diisi!',
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
                    'daerah_id' => $request->wilayah,
                    'kd_pos' => $request->kd_pos,
                    'alamat' => $request->alamat,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'perwakilan_nama' => $request->perwakilan_nama,
                    'perwakilan_kontak' => $request->perwakilan_kontak,
                    'email' => $request->email,
                    'keterangan' => $request->keterangan,
                );
                Pelanggan::create($data);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'log' => $e,
                    // 'pesan' => 'Error Menyimpan Data',
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'wilayah' => 'required',
            'lat' => 'required',
            'lng' => 'required'
        ];

        $pesan = [
            'nama.required' => 'Nama Pelanggan Wajib Diisi!',
            'alamat.required' => 'Alamat Pelanggan Wajib Diisi!',
            'wilayah.required' => 'Wilayah Pelanggan Wajib Diisi!',
            'lat.required' => 'Latitude Wajib Diisi!',
            'lng.required' => 'Langtitude Wajib Diisi!',
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
                $pelanggan = Pelanggan::find($request->pelanggan_id);
                $pelanggan->update([
                    'nama' => $request->nama,
                    'telp' => $request->telp,
                    'daerah_id' => $request->wilayah,
                    'kd_pos' => $request->kd_pos,
                    'alamat' => $request->alamat,
                    'perwakilan_nama' => $request->perwakilan_nama,
                    'perwakilan_kontak' => $request->perwakilan_kontak,
                    'email' => $request->email,
                    'keterangan' => $request->keterangan,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                ]);
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'log_error' => $e,
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
        $data = Pelanggan::find($id);
        $wilayah  = ucwords(strtolower($data->daerah->urban)).',';
        $wilayah .= ucwords(strtolower($data->daerah->sub_district)).', ';
        $wilayah .= ucwords(strtolower($data->daerah->city)).', ';
        $wilayah .= ucwords(strtolower($data->daerah->provinsi->province_name));

        $data->kd_pos = $data->daerah->postal_code;
        return view('pelanggan.edit', compact('data', 'wilayah'));
    }

    public function json(Request $request)
    {
        if(!isset($request->searchTerm)){
            $fetchData =  Pelanggan::orderBy('created_at', 'DESC')->get();
          }else{
            $cari = $request->searchTerm;
            $fetchData = Pelanggan::where('nama','LIKE',  '%' . $cari .'%')->orderBy('created_at', 'DESC')->get();
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
        $data = Pelanggan::destroy($id);
        if($data){
            return response()->json([
                'fail' => false,
            ]);
        }
    }
}
