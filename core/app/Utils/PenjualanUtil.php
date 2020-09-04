<?php

namespace App\Utils;

use App\Models\Toko_id;

use App\Models\Produk;
use App\Models\ProdukVariasi;
use App\Models\VariasiDetail;
use App\Models\Penjualan;
use App\Models\Transaksi;
use App\TransactionSellLinesPurchaseLines;
use App\Unit;
use Illuminate\Support\Facades\DB;

class PenjualanUtil extends Util
{
/**
     * Tambah/Edit Transaksi Penjualan
     *
     * @param object/int $transaksi
     * @param array $penjualan
     * @param array $outlet_id
     * @param boolean $return_deleted = false
     * @param array $extra_line_parameters = []
     *   Example: ['database_trasnaction_linekey' => 'products_line_key'];
     *
     * @return boolean/object
     */
    public function createOrUpdatePenjualan($transaksi, $produkJual, $return_deleted = false, $status_before = null, $extra_line_parameters = [])
    {
        $penjualan = [];
        $modifiers_array = [];
        $edit_id = [];
        $modifiers_formatted = [];
        $combo_lines = [];
        $products_modified_combo = [];
        foreach ($produkJual as $produk) {
            $multiplier = 1;

            //Cek apakah penjualan_id ada, Paremeter penjualan_id digunakan untuk ubah data penjualan
            if (!empty($produk['penjualan_id'])) {
                $edit_id[] = $produk['penjualan_id'];

                $this->editPenjualan($produk, $status_before, $multiplier);

                //Ubah atau buat data penjualan
            } else{

                // Menghitung unit satuan produk dan harga sebelum diskon
                $line = [
                    'produk_id' => $produk['produk_id'],
                    'variasi_id' => $produk['variasi_id'],
                    'quantity' =>  $produk['qty'],
                    'hrg_jual' => $produk['harga'],
                    'sub_total' => $produk['harga'] * $produk['qty'],
                    'created_at' => $transaksi->created_at,
                    'updated_at' => $transaksi->created_at,
                ];

                $penjualan[] = new Penjualan($line);
            }
        }

        //Delete the products removed and increment product stock.
        $hapus = [];
        if (!empty($edit_id)) {
            $hapus = Penjualan::where('transaksi_id', $transaksi->id)
                    ->whereNotIn('id', $edit_id)
                    ->select('id')->get()->toArray();
            $hapus = array_merge($hapus);

            $adjust_qty = $status_before == 'draft' ? false : true;

            $this->deleteSellLines($hapus, $location_id, $adjust_qty);
        }

        if (!empty($penjualan)) {
            // Tambah Data Penjualan Per Produk
            $transaksi->penjualan()->saveMany($penjualan);
        }

        if ($return_deleted) {
            return $hapus;
        }
        return true;
    }

    public function hitungVolume($penjualan)
    {
        $total_volume = 0;
        foreach ($penjualan as $jual){
            $total_volume += $jual['beban'];
        }

        return $total_volume;
    }

}
