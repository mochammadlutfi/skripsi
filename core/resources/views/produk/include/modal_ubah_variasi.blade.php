
<div class="modal" id="modal_ubah_variasi"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-header block-header-default">
                        <h3 class="block-title modal-title">Ubah Variasi Produk</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="form-ubah_variasi" onsubmit="return false;">
                        <input type="hidden" name="row_ubah_variasi" id="row_ubah_variasi" value="">
                        <div class="row px-0">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="field-ubah_variasi_nama">Nama</label>
                                    <input type="text" class="form-control" name="ubah_variasi_nama" id="field-ubah_variasi_nama">
                                    <div id="error-ubah_variasi_nama" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="field-ubah_variasi_sku">Kode Produk (SKU)</label>
                                    <input type="text" class="form-control" id="field-ubah_variasi_sku" name="ubah_variasi_sku" placeholder="Masukan Kode Produk (SKU)">
                                    <div id="error-ubah_variasi_sku" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="field-ubah_variasi_hrg_modal">Harga Modal</label>
                                    <input type="text" class="form-control" id="field-ubah_variasi_hrg_modal" name="ubah_variasi_hrg_modal" placeholder="Masukan Harga Modal">
                                    <div id="error-ubah_variasi_hrg_modal" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="field-ubah_variasi_hrg_jual">Harga Jual</label>
                                    <input type="text" class="form-control" id="field-ubah_variasi_hrg_jual" name="ubah_variasi_hrg_jual" placeholder="Masukan Harga Jual">
                                    <div id="error-ubah_variasi_hrg_jual" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row border-bottom mx-0 mb-2">
                            <div class="col-lg-12 px-0">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox checkbox-lg mb-5">
                                        <input class="custom-control-input" type="checkbox" name="ubah_variasi_kelola_stok" id="ubah_variasi_kelola_stok" value="1" checked="">
                                        <label class="custom-control-label" for="ubah_variasi_kelola_stok">Kelola Inventaris</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-0 tanpa_variasi ubah_variasi_inventaris">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="field-ubah_variasi_unit">Satuan Unit</label>
                                    <select class="form-control pilih-satuan" id="field-ubah_variasi_unit" name="ubah_variasi_unit" placeholder="Pilih Satuan Unit" style="width:100%;">
                                    </select>
                                    <div id="error-variasi_unit" class="text-danger font-size-sm"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="field-ubah_variasi_min_stok">Minimum Stok</label>
                                    <input type="number" class="form-control" id="field-ubah_variasi_min_stok" name="ubah_variasi_min_stok" placeholder="Masukan Minimum Stok">
                                    <div id="error-ubah_variasi_min_stok" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="field-variasi_volume">Volume</label>
                                    <input type="number" step=".01"  class="form-control" id="field-variasi_volume" name="variasi_volume" placeholder="Masukan Volume">
                                    <div id="error-variasi_volume" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-right pb-15">
                            <div class="col-lg-4 ml-auto">
                                <button type="submit" class="btn btn-alt-primary btn-block"><i class="si si-check mr-5"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
