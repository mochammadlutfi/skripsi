
<div class="modal" id="variasiModal"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="btn btn-rounded btn-primary btn-sm keluar" data-dismiss="modal" aria-label="Close">
                  <i class="fa fa-times"></i>
              </button>
            </div>
            <div class="block mb-0">
                <div class="block-content px-25 py-15">
                    <form id="form-peramalan" onsubmit="return false;">
                    @csrf
                    <div class="row">
                        <div class="col-3 py-10 text-center">
                            <img id="modal_produkFoto" src="{{ asset('assets/img/placeholder/product.png') }}" width="100%">
                        </div>
                        <div class="col-9 py-10">
                            <h2 class="font-size-h2 mb-0" id="modal_produkNama">Nama Produk</h2>
                            <span class="mb-0" id="modal_produkKategori">Kategori</span>
                        </div>
                    </div>
                    <table class="table table-vcenter border-bottom mb-0" id="variasi_table">
                        <tbody>
                        </tbody>
                    </table>
                    <div class="text-danger mb-1" id="error-variasi_id"></div>
                    <div class="form-group">
                        <label for="field-tgl_target">Target Peramalan</label>
                        <input type="text" class="form-control" id="field-tgl_target" name="tgl_target" placeholder="Target Peramalan">
                        <div class="invalid-feedback" id="error-tgl_target">Invalid feedback</div>
                    </div>
                    <button type="submit" class="btn btn-alt-primary btn-block">
                        <i class="si si-magic mr-1"></i> Ramal Pengadaan
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
