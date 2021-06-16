/**
 * Alertas
 */
const Alerta = {
    autohide: false,
    delay: 5000,

    default(title, content) {
        $(document).Toasts('create', {
            autohide: Alerta.autohide,
            delay: Alerta.delay,
            title: title,
            body: content
        })
    },

    ok(title, content) {
        $(document).Toasts('create', {
            autohide: Alerta.autohide,
            delay: Alerta.delay,
            class: 'bg-success',
            title: title,
            body: content
        });
    },

    error(title, content) {
        $(document).Toasts('create', {
            autohide: Alerta.autohide,
            delay: Alerta.delay,
            class: 'bg-danger',
            title: title,
            body: content
        });
    },

    warning(title, content) {
        $(document).Toasts('create', {
            autohide: Alerta.autohide,
            delay: Alerta.delay,
            class: 'bg-warning',
            title: title,
            body: content
        });
    }
};

/**
 * AJAX
 */
const AJAX = {
    enviar(options) {
        // Validacion
        if(options.url == undefined) throw `No se ha enviado el atributo 'url'.`;

        // Valores por defecto
        if(options.data == undefined) options.data = {};
        if(options.method == undefined) options.method = 'POST';
        if(options.antes == undefined) options.antes = () => {
            Loader.show();
        };
        if(options.error == undefined) options.error = (mensaje) => {
            Loader.hide();
            Alerta.error('Mensaje del sistema', mensaje);
        }
        if(options.ok == undefined) options.ok = (data) => {
            Loader.hide();
            console.log(data);
        };
        if(options.final == undefined) options.final = () => {
            /* Nothing */
        }

        // Envio
        $.ajax({
            url: options.url,
            type: options.method,
            data: JSON.stringify( options.data ),
            processData: false,
            contentType: 'application/json',
            beforeSend: function() {
                options.antes();
            }
        })
        .done(function(data) {
            if(data.status == undefined) throw "No se recibio el parametro 'status'.";
            if(data.body == undefined) throw "No se recibio el parametro data.body'.";

            if(data.status.toLowerCase() !== 'ok') {
                if(AUDITAR) console.warn(data.body);
                options.error(data.body.message);
            }
            else {
                options.ok(data.body);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error(errorThrown);
        })
        .done(function() {
            options.final();
        });
    }
};

/**
 * Modal loading
 */
const Loader = {
    show() {
        $('body').append(`<div class="modal" id="modal-loader" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="spinner-grow text-dark" role="status"></div>
                    </div>
                </div>
            </div>
        </div>`);

        $("#modal-loader").modal('show');
    },
    hide() {
        $("#modal-loader").modal('hide');
        $("#modal-loader").on('hidden.bs.modal', function() {
            $("#modal-loader").remove();
        });
    }
};

/**
 * Form
 */
const Form = {
    json(jqueryForm) {
        let elements = jqueryForm[0].elements;
        let output = {};

        for(let element of elements) {
            if(element.name == "" || element.name == undefined) continue;
            switch( element.type )
            {
                case 'radio':
                    output[element.name] = element.checked;
                    break;
                case 'checkbox':
                    output[element.name] = element.checked;
                    break;
                default:
                    output[element.name] = element.value;
                    break;
            }
        }
        
        return output;
    }
};

/**
 * Cerrar Sesion
*/
function cerrar_sesion() {
    AJAX.enviar({
        url: `${BASE_URL}/cerrar_sesion`,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Loader.hide();
            Alerta.error('Cerrar Sesión', mensaje);
        },
        ok() {
            location.href = `${BASE_URL}/Login/`;
        }
    });
}