<?php

namespace App\Utils;

use App\Models\Pembelian;
use App\Models\Transaksi;
use App\Models\TransaksiBayar;
use App\Unit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembelianUtil extends Util
{
    /**
    * Tambah/Edit Transaksi Pembelian
    *
    * @param object $transaksi
    * @param array $pembelian_data
    * @param array $currency_details
    * @param boolean $enable_product_editing
    * @param string $status_sebelumnya = null
    *
    * @return array
    */
    public function createOrUpdatePembelian($transaksi, $pembelian_data, $status_sebelumnya = null)
    {
        $update_pembelian = [];
        $update_pembelian_id = [0];

        foreach ($pembelian_data as $data => $d) {
            $qty = $d['qty'];
            // dd($d['produk_id']);
            //update existing purchase line
            if (isset($d['pembelian_id']) || isset($d['id'])){
                if(isset($d['pembelian_id']))
                {
                    $pembelian = Pembelian::findOrFail($d['pembelian_id']);
                }else{
                    $pembelian = Pembelian::findOrFail($d['id']);
                }

                // Update stok produk apabila produk sudah ada
                if ($status_sebelumnya == 'dipesan' && $transaksi->status == 'diterima') {

                    // Jika status diterima, maka stok produk ditambahkan
                    $this->updateStokProduk($d['produk_id'], $d['variasi_id'], $qty, 0);

                }elseif ($status_sebelumnya == 'diterima' && $transaksi->status != 'diterima') {

                    // Kurangi stok produk apabila status pembelian dirubah dari diterima menjadi tidak diterima
                    $this->kurangiStokProduk(
                        $d['produk_id'],
                        $d['variasi_id'],
                        $transaksi->toko_id,
                        $pembelian->quantity
                    );
                } elseif ($status_sebelumnya != 'diterima' && $transaksi->status == 'diterima') {
                    $this->updateStokProduk($d['produk_id'], $d['variasi_id'], $qty, 0);
                }
            } else {
                //Buat Data Pembelian Baru
                $pembelian = new Pembelian();
                $pembelian->transaksi_id = $transaksi->id;
                $pembelian->produk_id = $d['produk_id'];
                $pembelian->variasi_id = $d['variasi_id'];
                $pembelian->created_at = $transaksi->created_at;
                $pembelian->updated_at = $transaksi->created_at;

                //Menambah stok produk apabila status pembelian sudah diterima
                if ($transaksi->status == 'diterima') {
                    $this->updateStokProduk($d['produk_id'], $d['variasi_id'], $qty, 0);
                }
            }

            $pembelian->quantity = $qty;
            $pembelian->hrg_beli = $d['harga'];
            $pembelian->sub_total = $d['total'];

            $update_pembelian[] = $pembelian;
        }

        // Inisiasi hapus data pembelian
        $hapus_pembelian_id = [];
        $hapus_pembelian = null;
        if (!empty($update_pembelian_id)) {
            $hapus_pembelian = Pembelian::where('transaksi_id', $transaksi->id)
                    ->whereNotIn('id', $update_pembelian_id)
                    ->get();

            if ($hapus_pembelian->count()) {
                foreach ($hapus_pembelian as $hapus_data) {
                    $hapus_pembelian_id[] = $hapus_data->id;

                // kurangi stok produk apabila status pembelian sudah diterima
                    if ($status_sebelumnya == 'diterima') {
                        $this->kurangiStokProduk(
                            $hapus_data->product_id,
                            $hapus_data->variation_id,
                            $transaksi->toko_id,
                            $hapus_data->quantity
                    );
                    }
                }

            //  Hapus data pembelian produk
                Pembelian::where('transaksi_id', $transaksi->id)
                        ->whereIn('id', $hapus_pembelian_id)
                        ->delete();
            }
        }

        //Update data pembelian
        if (!empty($update_pembelian)) {
            // Menyimpan data pembelian berdasarkan transaksi
            $transaksi->pembelian()->saveMany($update_pembelian);
        }

        return $hapus_pembelian;
    }
}
