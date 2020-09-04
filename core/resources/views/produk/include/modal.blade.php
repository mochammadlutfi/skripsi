
<div class="modal" id="produkModal" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="btn btn-rounded btn-primary btn-sm keluar" data-dismiss="modal" aria-label="Close">
                  <i class="fa fa-times"></i>
              </button>
            </div>
            <div class="block mb-0">
                <div class="block-content px-25 py-15 bg-gray-lighter">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-kategori">Kategori</label>
                                <select class="form-control" id="field-kategori" name="kategori" placeholder="Pilih Kategori"  style="width: 100%"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-merk">Merk</label>
                                <select class="form-control" id="field-merk" name="merk" placeholder="Pilih Merk" style="width: 100%"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="si si-magnifier"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="field-keyword" class="form-control" placeholder="Cari Produk">
                                    <span class="input-group-append">
                                        <div class="input-group-text bg-transparent"><i class="fa fa-times"></i></div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8" id="produk_nav">
                                    <span>Menampilkan Produk 1-9 Dari 9</span>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-alt-secondary float-right" id="nextProduk">
                                        <i class="fa fa-chevron-right fa-fw"></i>
                                    </button>
                                    <button type="button" class="btn btn-alt-secondary float-left" id="prevProduk">
                                        <i class="fa fa-chevron-left fa-fw"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content px-25 py-15" id="list-produk">
                    <input type="hidden" id="produk_current_page" value="">
                    <div class="row" id="list-produk-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
