<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
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
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if ($request->ajax()) {
            $data = User::orderby('created_at', 'DESC')->get();
            return Datatables::of($data)
            // ->addIndexColumn()
            ->addColumn('nama', function($row){

                    return $row->nama;
            })
            ->addColumn('username', function($row){

                    return ucWords($row->username);
            })
            ->addColumn('jabatan', function($row){

                foreach($row->getRoleNames() as $v){
                    $jabatan = ucwords(str_replace('-', ' ', $v));
                }
                return $jabatan;
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
                if(!$row->email)
                {
                    $email = '-';
                }else{
                    $email = $row->email;
                }

                $kontak  = '<span class="text-bold mr-3">No. Telp</span>: '.$telp.'<br>';
                $kontak .= '<span class="text-bold mr-3">No. HP </span>: '.$hp.'<br>';
                $kontak .= '<span class="text-bold mr-3">Email</span>: '.$email;

                return $kontak;
            })
            ->addColumn('alamat', function($row){
                    $alamat = ucwords(strtolower($row->alamat)).'<br>';

                    $alamat .= ucwords(strtolower($row->daerah->urban)).',';
                    $alamat .= ucwords(strtolower($row->daerah->sub_district)).'<br>';
                    $alamat .= ucwords(strtolower($row->daerah->city)).', ';
                    $alamat .= ucwords(strtolower($row->daerah->provinsi->province_name));
                    return $alamat;
            })
            ->addColumn('action', function($row){
                    $btn = '<center><a href="'. route('pengguna.edit', $row->id) .'" class="btn btn-secondary btn-sm mr-5"><i class="si si-note"></i></a>';
                    $btn .= '<button type="button" onClick="hapus(\''.$row->id.'\')" class="btn btn-secondary btn-sm"><i class="si si-trash"></i></button></center>';
                    return $btn;
            })
            ->rawColumns(['username', 'alamat', 'action', 'kontak'])
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
            'kd_pos' => 'required',
            'username' => 'required',
            'password' => 'required|min:6|confirmed',
            'jabatan' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
            'email.required' => 'Alamat Email Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
            'kd_pos.required' => 'Kode Pos Wajib Diisi!',
            'password.required' => 'Password Wajib Diisi!',
            'password.confirmed' => 'Password Tidak Sesuai Dengan Konfirmasi Password!',
            'jabatan.required' => 'Jabatan Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
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
                $user = new User();
                $user->nama = $request->nama;
                $user->ktp = $request->ktp;
                $user->hp = $request->hp;
                $user->username = $request->username;
                $user->email = $request->email;
                $user->daerah_id = $request->wilayah;
                $user->alamat = $request->alamat;
                $user->password = bcrypt($request->password);
                $user->save();

                $user->assignRole($request->jabatan);
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

    public function edit($id)
    {
        $data = User::find($id);

        $wilayah  = ucwords(strtolower($data->daerah->urban)).',';
        $wilayah .= ucwords(strtolower($data->daerah->sub_district)).', ';
        $wilayah .= ucwords(strtolower($data->daerah->city)).', ';
        $wilayah .= ucwords(strtolower($data->daerah->provinsi->province_name));
        $data->kd_pos = $data->daerah->postal_code;

        foreach($data->getRoleNames() as $v){
            $data->jabatan = ucwords(str_replace('-', ' ', $v));
        }

        return view('pengguna.edit', compact('data', 'wilayah'));
    }

    public function update(Request $request){

        $rules = [
            'nama' => 'required',
            'username' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'wilayah' => 'required',
            'kd_pos' => 'required',
            'username' => 'required',
            'jabatan' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
            'email.required' => 'Alamat Email Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
            'kd_pos.required' => 'Kode Pos Wajib Diisi!',
            'jabatan.required' => 'Jabatan Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
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
                $user = User::find($request->user_id);
                $user->update([
                   'nama' => $request->nama,
                   'ktp' => $request->ktp,
                   'hp' => $request->hp,
                   'username' => $request->username,
                   'email' => $request->email,
                   'daerah_id' => $request->wilayah,
                   'alamat' => $request->alamat,
                ]);

                foreach($user->getRoleNames() as $v){
                    $jabatan = ucwords(str_replace('-', ' ', $v));
                }
                if($jabatan !== $request->jabatan)
                {
                    $user->assignRole($request->jabatan);
                }
            }catch(\QueryException $e){
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

    public function profil(){
        $data = auth()->user();
        $wilayah  = ucwords(strtolower($data->daerah->urban)).',';
        $wilayah .= ucwords(strtolower($data->daerah->sub_district)).', ';
        $wilayah .= ucwords(strtolower($data->daerah->city)).', ';
        $wilayah .= ucwords(strtolower($data->daerah->provinsi->province_name));
        $data->kd_pos = $data->daerah->postal_code;
        return view('pengguna.profil', compact('data', 'wilayah'));
    }

    public function update_profil(Request $request){

        $rules = [
            'nama' => 'required',
            'username' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'wilayah' => 'required',
            'kd_pos' => 'required',
            'username' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
            'email.required' => 'Alamat Email Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
            'kd_pos.required' => 'Kode Pos Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
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
                $user = User::find($request->user_id);
                $user->update([
                   'nama' => $request->nama,
                   'ktp' => $request->ktp,
                   'hp' => $request->hp,
                   'username' => $request->username,
                   'email' => $request->email,
                   'daerah_id' => $request->wilayah,
                   'alamat' => $request->alamat,
                ]);
            }catch(\QueryException $e){
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

    public function ubah_pw(Request $request)
    {
        if($request->isMethod('get'))
        {
            return view('pengguna.ubah_pw');
        }else{
            $rules = [
                'pw_lama' => 'required',
                'pw_baru' => 'min:6|required_with:konf_pw_baru|same:konf_pw_baru',
                'konf_pw_baru' => 'min:6',
            ];

            $pesan = [
                'pw_lama.required' => 'Kata Sandi Lama Wajib Diisi!',
                'pw_baru.required' => 'Kata Sandi Baru Wajib Diisi!',
                'konf_pw_baru.required' => 'Konfirmasi Kata Sandi Baru Wajib Diisi!',
                'pw_baru.same' => 'Kata Sandi Baru Tidak Sama!',
                'pw_baru.min' => 'Kata Sandi Baru Kurang Dari 6 Karakter!',
                'konf_pw_baru.min' => 'Konfirmasi Kata Sandi Baru Kurang Dari 6 Karakter!',
            ];

            $validator = Validator::make($request->all(), $rules, $pesan);
            if ($validator->fails()){
                return response()->json([
                    'fail' => true,
                    'errors' => $validator->errors()
                ]);
            }else{
                if (Hash::check($request->pw_lama, Auth::user()->password)) {
                    $data = User::find(Auth::user()->id);
                    $data->password = Hash::make($request->pw_baru);
                    if($data->save())
                    {
                        return response()->json([
                            'fail' => false,
                        ]);
                    }
                }else{
                    return response()->json([
                        'fail' => true,
                        'errors' => [
                            'pw_lama' => ['Kata Sandi Lama Tidak Sama']
                        ]
                    ]);
                }
            }
        }
    }
}
