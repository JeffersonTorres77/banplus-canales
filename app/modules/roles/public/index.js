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
            data: "nombre",
            class: "align-middle"
        },
        {
            data: "id",
            width: '110px',
            orderable: false,
            render: function(d, type, row) {
                return `<div class="text-center">
                    <button class="btn btn-outline-primary btn-sm btn-opc ver">
                        <i class="fas fa-eye fa-sm"></i>
                    </button>
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
 * Ver
 */
$("#table-roles tbody").on('click', 'button.ver', function() {
    let data = tabla.row( $(this).parents('tr') ).data();
    $("#modal-ver").modal('show');
});



/**
 * Modificar
 */
$("#table-roles tbody").on('click', 'button.editar', function() {
    let data = tabla.row( $(this).parents('tr') ).data();
    $("#modal-editar").modal('show');
});

$("#modal-editar form").on('submit', function(e) {
    e.preventDefault();
    alert('Form editar');
});



/**
 * Eliminar
 */
$("#table-roles tbody").on('click', 'button.eliminar', function() {
    let data = tabla.row( $(this).parents('tr') ).data();
    $("#modal-eliminar").modal('show');
});

$("#modal-eliminar form").on('submit', function(e) {
    e.preventDefault();
    alert('Form eliminar');
});