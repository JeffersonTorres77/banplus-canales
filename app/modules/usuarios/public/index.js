/**
 * Tabla
 */
 var tabla = $("#table-usuarios").DataTable({
    language: DT_SPANISH,
    processing: true,
    serverSide: true,
    ajax: {
        url: `${BASE_URL}/Usuarios/API/datatable/`
    },
    columns: [
        {
            data: "nombres",
            class: "align-middle",
            width: "auto",
            render: function(d, type, row) {
                return `${row.nombres} ${row.apellidos}`;
            }
        },
        {
            data: "rol",
            class: "align-middle text-truncate text-center",
            width: "120px",
            render: function(d, type, row) {
                return d.nombre;
            }
        },
        {
            data: "activo",
            class: "align-middle text-center",
            width: "50px",
            render: function(d, type, row) {
                d = (d == 0) ? false : true;
                return `<div class="badge badge-${ (d) ? 'success' : 'danger' }">${ (d) ? 'Si' : 'No' }</div>`;
            }
        },
        {
            data: "id",
            width: '40px',
            class: "align-middle text-truncate",
            orderable: false,
            render: function(d, type, row) {
                return `<div class="text-center">
                    <button class="btn btn-outline-primary btn-sm btn-opc ver">
                        <i class="fas fa-eye fa-sm"></i>
                    </button>
                </div>`;
            }
        }
    ]
});