$(document).ready(function () {
    
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

    $("#form-ubah_pw").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-ubah_pw')[0]);
        $.ajax({
            url: laroute.route('ubah_pw'),
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
                        text: "Password Berhasil Diperbaharui",
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