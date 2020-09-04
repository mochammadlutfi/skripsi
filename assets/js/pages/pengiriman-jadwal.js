
$(document).ready(function () {

    $('#master').on('click', function(e) {
        if($(this).is(':checked',true))
        {
        $(".sub_chk").prop('checked', true);
        } else {
        $(".sub_chk").prop('checked',false);
        }
    });


    $('#konfirmasi').on('click', function(e) {
        var allVals = [];
        $(".sub_chk:checked").each(function() {
            allVals.push($(this).attr('data-id'));
        });
        if(allVals.length <=0)
        {
            Swal.fire({
                title: "Gagal!",
                text: 'Tidak Ada Data Pengiriman Yang Dipilih!',
                timer: 1500,
                showConfirmButton: false,
                icon: 'error'
            });
        }else{
            var join_selected_values = allVals.join(",");
            Swal.fire({
                title: `Konfrimasi Pengiriman!`,
                showConfirmButton: false,
                icon: 'warning',
                html: `Perbaharui Status Pengiriman
                    <br><br>
                    <button type="button" class="btn btn-keluar btn-alt-danger"><i class="si si-close mr-1"></i>Batalkan</button> 
                    <button type="button" class="btn btn-dikirim btn-alt-primary"><i class="si si-reload mr-1"></i>Dikirim</button>
                    <button type="button" class="btn btn-diterima btn-alt-success"><i class="si si-check mr-1"></i>Diterima</button>`,
                showCancelButton: false,
                showConfirmButton: false,
                onBeforeOpen: () => {

                    const keluar = document.querySelector('.btn-keluar')
                    const dikirim = document.querySelector('.btn-dikirim')
                    const diterima = document.querySelector('.btn-diterima')
        
                    keluar.addEventListener('click', () => {
                        konfirmasi('batal', join_selected_values);
                    })
        
                    dikirim.addEventListener('click', () => {
                        konfirmasi('dikirim', join_selected_values);
                    })
        
                    diterima.addEventListener('click', () => {
                        konfirmasi('diterima',join_selected_values);
                    })
                }
            });
        }
    });


    $('#tunda').on('click', function(e) {
        var allVals = [];
        $(".sub_chk:checked").each(function() {
            allVals.push($(this).attr('data-id'));
        });
        if(allVals.length <=0)
        {
            Swal.fire({
                title: "Gagal!",
                text: 'Tidak Ada Data Pengiriman Yang Dipilih!',
                timer: 1500,
                showConfirmButton: false,
                icon: 'error'
            });
        }else{
            var join_selected_values = allVals.join(",");
            Swal.fire({
                title: "Anda Yakin?",
                text: "Status Pengiriman Akan Menjadi Belum Dikirim!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Ya, Konfirmasi!',
                cancelButtonText: 'Tidak, Batalkan!',
                reverseButtons: true,
                allowOutsideClick: false,
                confirmButtonColor: '#af1310',
                cancelButtonColor: '#fffff',
            })
            .then((result) => {
                if (result.value) {
                $.ajax({
                    url: laroute.route('pengiriman.konfirmasi'),
                    type: "POST",
                    data: {
                        ids : join_selected_values,
                        tipe : 'tunda',
                    },
                    dataType: "JSON",
                    beforeSend: function(){
                        Swal.fire({
                            title: 'Tunggu Sebentar...',
                            text: ' ',
                            imageUrl: laroute.url('assets/img/loading.gif', ['']),
                            showConfirmButton: false,
                            allowOutsideClick: false,
                        });
                    },
                    success: function(data) {
                        Swal.fire({
                            title: "Berhasil",
                            text: 'Pengiriman Berhasil Dibatalkan!',
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'success'
                        });
                        window.setTimeout(function(){
                            location.reload();
                        } ,1500);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.close();
                        alert('Eror Koneksi Ke Server');
                    }
                });
                } else {
                    window.setTimeout(function(){
                        location.reload();
                    } ,1500);
                }
            });
        }
    });

    $('.tgl_kirim').datepicker({
        startDate: 'today',
        format: 'dd-mm-yyyy',
        language: 'id',
    });

    $("#form-pengiriman").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-pengiriman')[0]);
        $.ajax({
            url: laroute.route('pengiriman.update'),
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
                    $('#modal_embed').modal('hide');
                    Swal.fire({
                        title: "Berhasil",
                        text: "Data Pengiriman Berhasil Diperbaharui",
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    window.setTimeout(function () {
                        location.reload();
                    }, 1500);
                } else {
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSimpan').text('Simpan');
                $('#btnSimpan').attr('disabled', false);

            }
        });
    });

    $("#form-perbaikan").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-perbaikan')[0]);
        $.ajax({
            url: laroute.route('pengiriman.perbaikan'),
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
                        text: "Data Pengiriman Berhasil Diperbaharui!",
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    window.setTimeout(function () {
                        location.reload();
                    }, 1500);
                } else {
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSimpan').text('Simpan');
                $('#btnSimpan').attr('disabled', false);

            }
        });
    });

    $(document).on('click', 'button#btn-bagi', function() {
        if($('#tabel-pengiriman tbody tr').length == 1){
            bagi = parseInt($('#count').val());
            bagi += 1;
            utama = $('[name="beban"]');
            volume = utama.val() / bagi;
            $('[name="pengiriman[0][volume]"]').val(utama.val()-volume);
            // $('[name="pengiriman[0][volume]"]').attr({
            //     "max" : utama.val()-volume,
            //  });
            tgl = $('[name="pengiriman[0][tgl_kirim]"]').val();

            $('#count').val(bagi);
            $('#tabel-pengiriman tr:last').after(`
            <tr>
                <td>
                    Pengiriman `+ bagi +`
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="field-beban">Volume Pengiriman</label>
                        <div class="col-lg-8">
                        <input type="hidden" name="pengiriman[`+ bagi +`][id]" value="">
                            <input type="number" step=".01" class="form-control input_beban" id="volume" name="pengiriman[`+ bagi +`][volume]" value="`+ volume +`">
                            <div class="text-danger" id="error-beban"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="field-tgl_kirim">Tanggal Pengiriman</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control tgl_kirim" id="field-tgl_kirim" name="pengiriman[`+ bagi +`][tgl_kirim]" placeholder="Tanggal Pengiriman" value="`+ tgl +`">
                            <div class="text-danger" id="error-tgl_kirim"></div>
                        </div>
                    </div>
                </td>
            </tr>`);
            $('.tgl_kirim').datepicker({
                startDate: 'today',
                format: 'dd-mm-yyyy',
                language: 'id',
            });
        }
    });


    // $(document).on('change', '.input_beban', function() {
    //     var row = $(this).closest('tr');

    //     var sum = 0;
    //         $('.input_beban').each(function(){
    //             sum += parseFloat($(this).val());
    //     });
    //     var volume = row.find('.input_beban').val();

    //     utama = $('[name="beban"]').val();

    // });

    $(document).on('click', 'button#btn-hapus', function() {
        $("#tabel-pengiriman").each(function(){
            if($('#tabel-pengiriman tbody tr').length == 2){
                $('#tabel-pengiriman tbody tr:last').remove();
                $('#count').val(1);
                utama = $('[name="beban"]').val();
                $('[name="pengiriman[0][volume]"]').val(utama);
                
                // $('[name="pengiriman[0][volume]"]').attr({
                //     "max" : utama,
                // });
            }
        });
    });

});

function konfirmasi(status, ids)
{
    $.ajax({
        url: laroute.route('pengiriman.konfirmasi'),
        type: "POST",
        data: {
            ids : ids,
            tipe : status,
        },
        dataType: "JSON",
        beforeSend: function(){
            Swal.fire({
                title: 'Tunggu Sebentar...',
                text: ' ',
                imageUrl: laroute.url('assets/img/loading.gif', ['']),
                showConfirmButton: false,
                allowOutsideClick: false,
            });
        },
        success: function(data) {
            Swal.fire({
                title: "Berhasil",
                text: 'Pengiriman Berhasil Dikonfirmasi!',
                timer: 3000,
                showConfirmButton: false,
                icon: 'success'
            });
            window.setTimeout(function(){
                location.reload();
            } ,1500);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            alert('Eror Koneksi Ke Server');
        }
    });
}

function edit(id){
    $('#form-pengiriman')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : laroute.route('pengiriman.edit', {id : id}),
        type: "GET",
        dataType: "JSON",
        success: function(response)
        {
            $('[name="pengiriman_id"]').val(response.id);
            $('[name="tgl_kirim"]').val(response.tgl_kirim);
            $('#modal_form').modal({
                backdrop: 'static',
                keyboard: false
            })
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
        }
    });
}


function perbaiki(id){
    $('#form-perbaikan')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : laroute.route('pengiriman.edit', {id : id}),
        type: "GET",
        dataType: "JSON",
        success: function(response)
        {
            $("#tabel-pengiriman > tbody:last").children('tr:not(:first)').remove();
            $('#count').val(1);
            
            $('[name="pengiriman_id"]').val(response.id);
            $('[name="pengiriman[0][id]"]').val(response.id);
            $('[name="beban"]').val(response.beban);
            
            // $('[name="pengiriman[0][volume]"]').attr({
            //     "max" : response.beban,
            //  });
            $('[name="pengiriman[0][volume]"]').val(response.beban);
            $('[name="pengiriman[0][tgl_kirim]"]').val(response.tgl_kirim);
            $('#modal-perbaikan').modal({
                backdrop: 'static',
                keyboard: false
            })
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
        }
    });
}