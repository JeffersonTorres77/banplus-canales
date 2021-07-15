/**
 * Tabla
 */
 var tabla = $("#table-usuarios").DataTable({
    language: DT_SPANISH,
    processing: true,
    serverSide: true,
    ajax: {
        url: `${BASE_URL}/contingencia/api_usuarios/datatable/`
    },
    columns: [
        {
            data: "usuario",
            class: "align-middle",
            width: "auto",
            render: function(d, type, row) {
                return `${d.nombres} ${d.apellidos}`;
            }
        },
        {
            data: "numero_lote",
            class: "align-middle text-right",
            width: "150px",
            render: function(d, type, row) {
                return formatNumber(d, 0);
            }
        },
        {
            data: "referencia_inicial",
            class: "align-middle text-right",
            width: "150px",
            render: function(d, type, row) {
                return formatNumber(d, 0);
            }
        },
        {
            data: "id",
            width: '80px',
            class: "align-middle text-truncate",
            orderable: false,
            render: function(d, type, row) {
                return `<div class="text-center">
                    <button class="btn btn-outline-success btn-sm btn-opc editar">
                        <i class="fas fa-edit fa-sm"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm btn-opc eliminar">
                        <i class="fas fa-trash-alt fa-sm"></i>
                    </button>
                </div>`;
            }
        }
    ]
});

function actualizar_tabla() {
    tabla.ajax.reload();
}

/**
 * Registrar
 */
$("#btn-nuevo-usuario").on('click', function() {
    AJAX.enviar({
        url: `${BASE_URL}/contingencia/api_usuarios/usuarios-disponibles/`,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Consultar usuarios', mensaje);
        },
        ok(data) {
            let select_usuarios = $("#modal-registrar form [name=usuario_id]");
            select_usuarios.html('');

            if(data.usuarios.length > 0) {
                for(let usuario of data.usuarios) {
                    select_usuarios.append(`<option value="${usuario.id}">${usuario.nombres} ${usuario.apellidos}</option>`);
                }
            }
            else {
                select_usuarios.append(`<option value="">No hay usuarios disponibles.</option>`);
            }
            
            $("#modal-registrar").modal('show');
        },
        final() {
            Loader.hide();
        }
    });
});

$("#modal-registrar form").on('submit', function(e) {
    e.preventDefault();
    
    AJAX.enviar({
        url: `${BASE_URL}/contingencia/api_usuarios/registrar-usuario/`,
        data: Form.json( $("#modal-registrar form") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Registrar usuario', mensaje);
        },
        ok(data) {
            $("#modal-registrar").modal('hide');
            Alerta.ok('Registrar usuario', 'Usuario registrado exitosamente.');
            actualizar_tabla();
            $("#modal-registrar form")[0].reset();
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Editar
 */
 $("#table-usuarios tbody").on('click', 'button.editar', function() {
    let data = tabla.row( $(this).parents('tr') ).data();
    
    $("#modal-editar [name=usuario_id]").val(data.usuario.id);
    $("#modal-editar [name=numero_lote]").val(data.numero_lote);
    $("#modal-editar [name=referencia_inicial]").val(data.referencia_inicial);
    $("#modal-editar [data=nombre]").html(`${data.usuario.nombres} ${data.usuario.apellidos}`);

    $("#modal-editar").modal('show');
});

$("#modal-editar form").on('submit', function(e) {
    e.preventDefault();
    
    AJAX.enviar({
        url: `${BASE_URL}/contingencia/api_usuarios/editar-usuario/`,
        data: Form.json( $("#modal-editar form") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Editar usuario', mensaje);
        },
        ok(data) {
            $("#modal-editar").modal('hide');
            Alerta.ok('Editar usuario', 'Usuario editado exitosamente.');
            actualizar_tabla();
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Eliminar
 */
 $("#table-usuarios tbody").on('click', 'button.eliminar', function() {
    let data = tabla.row( $(this).parents('tr') ).data();
        
    $("#modal-eliminar [name=usuario_id]").val(data.usuario.id);
    $("#modal-eliminar [data=nombre]").html(`${data.usuario.nombres} ${data.usuario.apellidos}`);

    $("#modal-eliminar").modal('show');
});

$("#modal-eliminar form").on('submit', function(e) {
    e.preventDefault();
    
    AJAX.enviar({
        url: `${BASE_URL}/contingencia/api_usuarios/eliminar-usuario/`,
        data: Form.json( $("#modal-eliminar form") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Eliminar usuario', mensaje);
        },
        ok(data) {
            $("#modal-eliminar").modal('hide');
            Alerta.ok('Eliminar usuario', 'Usuario eliminado exitosamente.');
            actualizar_tabla();
        },
        final() {
            Loader.hide();
        }
    });
});