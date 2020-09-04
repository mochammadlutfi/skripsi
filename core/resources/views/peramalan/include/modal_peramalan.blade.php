
<div class="modal" id="peramalanModal"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="btn btn-rounded btn-primary btn-sm keluar" data-dismiss="modal" aria-label="Close">
                  <i class="fa fa-times"></i>
              </button>
            </div>
            <div class="block mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title" id="judul"></h3>
                </div>
                <div class="block-content px-25 py-15">
                    <form id="form-hasil" onsubmit="return false;">
                    @csrf
                        <input name="variasi_id" id="peramalan_variasi_id" type="hidden" value="">
                        <input name="peramalan" id="peramalan_hasil" type="hidden" value="">
                        <h2 class="font-size-h3 text-center border-bottom mb-1 pb-3" id="target_peramalan">Januari 2019</h2>
                        <h2 class="font-size-h2 text-center" id="hasil_peramalan">Hasil Peramalan</h2>
                        <button type="submit" class="btn btn-alt-primary btn-block">
                            <i class="si si-magic mr-1"></i> Tambah Ke Keranjang Pembelian
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
