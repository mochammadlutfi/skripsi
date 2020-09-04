<?php

namespace App\Utils;

use App\Models\Produk;
use App\Models\ProdukVariasi;
use App\Models\VariasiDetail;
use DB;
use GuzzleHttp\Client;

class Util
{
    /**
     * Cek apakah fitur kelola stok di aktif, jika diaktifkan update stok / kuantitas produk
     *
     * @param $produk_id
     * @param $variasi_id
     * @param $qty_baru
     * @param $qty_lama = 0
     * @param $number_format = null
     * @param $uf_data = true, if false it will accept numbers in database format
     *
     * @return boolean
     */
     public function updateStokProduk($produk_id, $variasi_id, $qty_baru, $qty_lama = 0)
     {
         $qty_diff = $qty_baru - $qty_lama;
 
         $produkvariasi = ProdukVariasi::find($variasi_id);
         //Cek Apakah Produk Variasi Mengelola Stok
         if ($produkvariasi->kelola_stok == 1 && $qty_diff != 0) {
 
             //Tambah kuantitas di Variasi Produk Detail
             $variasi_detail = VariasiDetail::where('variasi_id', $variasi_id)
                                     ->where('produk_id', $produk_id)
                                     ->first();
             if (empty($variasi_detail)) {
                 $variasi_detail = new VariasiDetail();
                 $variasi_detail->variasi_id = $produkvariasi->id;
                 $variasi_detail->produk_id = $produk_id;
                 $variasi_detail->qty_tersedia = 0;
             }
 
             $variasi_detail->qty_tersedia += $qty_diff;
             $variasi_detail->save();
         }
 
         return true;
     }
 
     /**
      * Cek jika kelola stok di produk di aktifkan maka stok produk akan di kurangi.
      *
      * @param $produk_id
      * @param $variasi_id
      * @param $new_quantity
      * @param $old_quantity = 0
      *
      * @return boolean
      */
     public function kurangiStokProduk($produk_id, $variasi_id, $qty_baru, $qty_lama = 0)
     {
         $qty = $qty_baru - $qty_lama;
 
         $produk = ProdukVariasi::find($variasi_id);
         if ($produk->kelola_stok == 1) {
             //Kurangi kuantitas produk pada tabel Variasi Detail
             $coba = VariasiDetail::where('variasi_id', $variasi_id)
                 ->where('produk_id', $produk_id)
                 ->decrement('qty_tersedia', $qty);
             if($coba)
             {
                 return true;
             }else{
                 return false;
             }
         }
     }
}