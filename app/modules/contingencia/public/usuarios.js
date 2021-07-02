/**
 * Tabla
 */
 var tabla = $("#table-usuarios").DataTable({
    language: DT_SPANISH,
    processing: true,
    serverSide: true,
    ajax: {
        url: `${BASE_URL}/contingencia/api/datatable-usuarios/`
    },
    columns: [
        {
            data: "nombre",
            class: "align-middle",
            width: "auto",
            render: function(d, type, row) {
                return `${row.nombres} ${row.apellidos}`;
            }
        },
        {
            data: "numero_lote",
            class: "align-right text-truncate text-center",
            width: "150px",
            render: function(d, type, row) {
                return formatNumber(d);
            }
        },
        {
            data: "referencia_inicial",
            class: "align-right text-center",
            width: "150px",
            render: function(d, type, row) {
                return formatNumber(d);
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
$("#modal-registrar form").on('submit', function(e) {
    e.preventDefault();
    alert();
});