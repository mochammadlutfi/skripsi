<?php

namespace App\Http\Controllers;

use App\Models\Daerah;
use App\Models\BisnisKategori;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function bisnisKategori(Request $request)
    {
        if(!isset($request->searchTerm)){
            $fetchData = BisnisKategori::orderBy('created_at', 'DESC')->get();
          }else{
            $cari = $request->searchTerm;
            $fetchData = BisnisKategori::where('nama','LIKE',  '%' . $cari .'%')->orderBy('created_at', 'DESC')->get();
          }

          $data = array();
          foreach($fetchData as $row) {
            $data[] = array("id" =>$row->id, "text"=>$row->nama);
          }

          return response()->json($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function wilayah(Request $request)
    {
        if($request->has('searchTerm')){
            $cari = $request->searchTerm;
                $fetchData = Daerah::where('urban','LIKE',  '%' . $cari .'%')->get();

            $data = array();
            foreach($fetchData as $row) {
                $data[] = array(
                    "id" =>$row->id,
                    // "text"=> ucwords(strtolower($row->urban)).', kec. '. ucwords(strtolower($row->sub_district)).', '.ucwords(strtolower($row->city)).', '.ucwords(strtolower($row->provinsi->province_name)),
                    "text" => 'Desa\Kel. '.ucwords(strtolower($row->urban)).', Kec. '. ucwords(strtolower($row->sub_district)).', '.ucwords(strtolower($row->city)).', '.ucwords(strtolower($row->provinsi->province_name)),
                );
            }
            return response()->json($data);
        }
    }

    public function getPos(Request $request)
    {
        if($request->daerah_id){
            $daerah = Daerah::find($request->daerah_id);
            return response()->json($daerah->postal_code);
        }
    }
}
