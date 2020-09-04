$(document).ready(function () {

    var wilayahSelect = $('#field-wilayah').select2({
        placeholder: 'Cari Kelurahan',
        language: 'id',
        ajax: {
            url: laroute.route('wilayah.json'),
            type: 'POST',
            dataType: 'JSON',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        },
        
        minimumInputLength: 3,
        // templateResult: formatResult,
        templateResult: function(response) {
            if(response.loading)
            {
                return "Mencari...";
            }else{
                var selectionText = response.text.split(",");
                var $returnString = $('<span>'+selectionText[0] + '</br>' + selectionText[1] + ', ' + selectionText[2]+ ', ' + selectionText[3] +'</span>');
                return $returnString;
            }
        },
        templateSelection: function(response) {
            return response.text;
        },
    });

    wilayahOption = new Option($('#field-wilayah').attr("data-text"), $('#field-wilayah').attr("data-id"), true, true);
    wilayahSelect.append(wilayahOption).trigger('change');

    $(document).on('change', '#field-wilayah', function() {
        $.ajax({
            method: 'POST',
            url: laroute.route('getPos.json'),
            data: {
                daerah_id : $('#field-wilayah').val(),
            },
            dataType: 'JSON',
            success: function(response) {
                $('#field-kd_pos').val(response)
            },
        });
    });
    
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });

    $("#show_hide_Knfpassword a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_Knfpassword input').attr("type") == "text"){
            $('#show_hide_Knfpassword input').attr('type', 'password');
            $('#show_hide_Knfpassword i').addClass( "fa-eye-slash" );
            $('#show_hide_Knfpassword i').removeClass( "fa-eye" );
        }else if($('#show_hide_Knfpassword input').attr("type") == "password"){
            $('#show_hide_Knfpassword input').attr('type', 'text');
            $('#show_hide_Knfpassword i').removeClass( "fa-eye-slash" );
            $('#show_hide_Knfpassword i').addClass( "fa-eye" );
        }
    });

    $("#form-profil").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-profil')[0]);
        $.ajax({
            url: laroute.route('update.profil'),
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
                        text: "Profil Berhasil Diperbaharui",
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    window.setTimeout(function () {
                        location.reload();
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
                $('#btnSimpan').text('Simpan');
                $('#btnSimpan').attr('disabled', false);
            }
        });
    });
});