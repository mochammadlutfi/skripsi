@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection


@section('content')
<div class="content">
    <form id="form-kendaraan" onsubmit="return false;">
        <div class="content-heading pt-0 mb-3">
            Tambah Kendaraan
            <button type="submit" class="btn btn-secondary float-right mr-5">
                <i class="si si-paper-plane mr-1"></i>
                Simpan Kendaraan
            </button>
        </div>
        <div class="block">
            <div class="block-content pb-15">
                <div class="row px-0">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="field-jenis">Jenis Kendaraan</label>
                            <select class="form-control" name="jenis" id="field-jenis">
                                <option value="">Pilih</option>
                                <option value="motor">Motor</option>
                                <option value="mobil">Mobil</option>
                            </select>
                            <div id="error-jenis" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-tipe">Tipe</label>
                            <input type="text" class="form-control" id="field-tipe" name="tipe" placeholder="Masukan Tipe Kendaraan">
                            <div id="error-tipe" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="field-no_polisi">No Polisi</label>
                            <input type="text" class="form-control" name="no_polisi" id="field-no_polisi" placeholder="Masukan No. Polisi">
                            <div id="error-no_polisi" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-max_kapasitas">Kapasitas Maksimal</label>
                            <input type="text" class="form-control" name="max_kapasitas" id="field-max_kapasitas" placeholder="Masukan Kapasitas Maksimal">
                            <div id="error-max_kapasitas" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop
@push('scripts')
<script>
$(document).ready(function () {
    $("#form-kendaraan").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-kendaraan')[0]);
        $.ajax({
            url: laroute.route('kendaraan.simpan'),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('assets/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                $('.is-invalid').removeClass('is-invalid');
                if (response.fail == false) {
                    Swal.fire({
                        title: "Berhasil",
                        text: "Kendaraan Baru Berhasil Ditambahkan",
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    window.setTimeout(function () {
                        location.href = laroute.route('kendaraan');
                    }, 1500);
                } else {
                    Swal.close();
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error adding / update data');
            }
        });
    });
});
</script>
@endpush
