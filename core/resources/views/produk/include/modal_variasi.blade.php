
<div class="modal" id="variasiModal"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-content px-25 py-15">
                    <form id="form-produkvariasi" onsubmit="return false;">
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
                    <table class="table" id="variasi_table">
                        <tbody>

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-alt-danger" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close mr-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-alt-primary" id="submitvariasi"><i class="si si-check mr-1"></i>Simpan</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
