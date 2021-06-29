$("#form-login").on('submit', (e) => {
    e.preventDefault();
    let url = `${BASE_URL}/Login/Acceder/`;
    let data = Form.json( $("#form-login") );

    AJAX.enviar({
        url: url,
        data: data,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Loader.hide();
            $("#login-errors-label").html(mensaje);
            $("#login-errors").collapse('show');
        },
        ok(data) {
            $("#login-errors").collapse('hide');
            if(data.login) {
                let url = BASE_URL;
                if(IR_A != "") url += `/${IR_A}`;
                location.href = url;
            }
            else {
                Loader.hide();
                $("#registro-input-usuario").val(data.usuario);
                $("#registro-usuario").html(data.usuario);
                $("#modal-registro").modal('show');
            }
        },
        final() {
            $('[name=pass]').val('');
        }
    });
});

$("#form-registro").on('submit', (e) => {
    e.preventDefault();
    let url = `${BASE_URL}/Login/Registrar/`;
    let data = Form.json( $("#form-registro") );

    AJAX.enviar({
        url: url,
        data: data,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Loader.hide();
            Alerta.error('Registrar usuario', mensaje);
        },
        ok(data) {
            location.href = BASE_URL;
        },
    });0
});