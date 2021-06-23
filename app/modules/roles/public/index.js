/**
 * Tabla
 */
var tabla = $("#table-roles").DataTable({
    language: DT_SPANISH,
    processing: true,
    serverSide: true,
    ajax: {
        url: `${BASE_URL}/Roles/API/datatable/`
    },
    columns: [
        {
            data: "id",
            class: "align-middle text-center",
            width: "50px"
        },
        {
            data: "nombre",
            class: "align-middle text-truncate",
            width: "auto"
        },
        {
            data: "cant_usuarios",
            class: "align-middle text-center",
            width: "50px",
            render: function(d, type, row) {
                return `<div class="badge badge-${ (d <= 0) ? 'warning' : 'info' }">${d}</div>`;
            }
        },
        {
            data: "id",
            width: '70px',
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


/**
 * Nuevo
 */
$("#modal-nuevo form").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/roles/api/registrar/`,
        data: Form.json( $("#modal-nuevo form") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Registrar usuario', mensaje);
        },
        ok(data) {
            $("#modal-nuevo").modal('hide');
            Alerta.ok('Registrar usuario', 'Usuario registrado exitosamente.');
            tabla.ajax.reload();
        },
        final() {
            Loader.hide();
        }
    });
});

$("#modal-nuevo").on('hidden.bs.modal', function() {
    $("#modal-nuevo form")[0].reset();
})



/**
 * Modificar
 */
$("#table-roles tbody").on('click', 'button.editar', function() {
    let data = tabla.row( $(this).parents('tr') ).data();

    $("[data-key=nombre]").html(data.nombre);

    $("[value-key=nombre]").val(data.nombre);
    $("[value-key=id]").val(data.id);

    $("#modal-editar").modal('show');
});

$("#modal-editar form").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/roles/api/modificar/`,
        data: Form.json( $("#modal-editar form") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Modificar usuario', mensaje);
        },
        ok(data) {
            $("#modal-editar").modal('hide');
            Alerta.ok('Modificar usuario', 'Usuario modificado exitosamente.');
            tabla.ajax.reload();
        },
        final() {
            Loader.hide();
        }
    });
});



/**
 * Eliminar
 */
$("#table-roles tbody").on('click', 'button.eliminar', function() {
    let data = tabla.row( $(this).parents('tr') ).data();

    $("[data-key=nombre]").html(data.nombre);
    $("[data-key=cant_usuarios]").html(data.cant_usuarios);

    $("[value-key=id]").val(data.id);

    let roles = tabla.data().toArray();
    $("#select-roles").html('');
    for(let rol of roles) {
        if(rol.id == data.id) continue;
        $("#select-roles").append(`<option value="${rol.id}">${rol.nombre}</option>`);
    }

    if(data.cant_usuarios > 0) {
        $("#div-sustituir-rol").removeClass('d-none').addClass('d-block');
        $("#select-roles").attr('required', '');
    }
    else {
        $("#div-sustituir-rol").removeClass('d-block').addClass('d-none');
        $("#select-roles").removeAttr('required');
    }

    $("#modal-eliminar").modal('show');
});

$("#modal-eliminar form").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/roles/api/eliminar/`,
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
            tabla.ajax.reload();
        },
        final() {
            Loader.hide();
        }
    });
});