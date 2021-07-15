/**
 * Para los inputs file
 */
bsCustomFileInput.init();

/**
 * Tabla
 */
 var tabla = $("#table-contingencias").DataTable({
    language: DT_SPANISH,
    processing: true,
    serverSide: true,
    ajax: {
        url: `${BASE_URL}/contingencia/api_contingencias/datatable/`
    },
    columns: [
        {
            data: "fecha_registro",
            class: "align-middle text-center",
            width: "100px",
        },
        {
            data: "referencia_inicial",
            class: "align-middle text-center",
            width: "120px",
            render: function(d, type, row) {
                return `${ numberFormat(row.referencia_inicial) } - ${ numberFormat(row.referencia_final) }`;
            }
        },
        {
            data: "numero_lote",
            class: "align-middle text-center",
            width: "60px",
            render: function(d, type, row) {
                return numberFormat(d);
            }
        },
        {
            data: "nombre_emisor",
            class: "align-middle text-truncate",
            width: "auto",
        },
        {
            data: "nombre_excel",
            class: "align-middle text-center",
            width: "100px",
            render: function(d, type, row) {
                return "";
            }
        },
        {
            data: "id",
            width: '80px',
            class: "align-middle text-truncate",
            orderable: false,
            render: function(d, type, row) {
                return `<div class="text-center">
                    <button class="btn btn-outline-info btn-sm btn-opc ver">
                        <i class="fas fa-eye fa-sm"></i>
                    </button>
                </div>`;
            }
        }
    ]
});

/**
 * Cargar contingencia
 */
$("#btn-cargar-contingencia").on('click', function() {
    AJAX.enviar({
        url: `${BASE_URL}/contingencia/api_contingencias/datos-precarga/`,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Consulta de datos de PreCarga', mensaje);
        },
        ok(data) {
            $("#modal-cargar-contingencia form [name=numero_lote]").val( data.numero_lote );
            $("#modal-cargar-contingencia form [name=referencia_inicial]").val( data.referencia_inicial );
            $("#modal-cargar-contingencia").modal('show');
        },
        final() {
            Loader.hide();
        }
    });
});

$("#modal-cargar-contingencia form").on('submit', function(e) {
    e.preventDefault();

    let data = new FormData(this);
    
    AJAX.cargar({
        url: `${BASE_URL}/contingencia/api_contingencias/cargar-contingencia/`,
        data: data,
        antes() {
            ProgressBar.show();
            ProgressBar.classColor('bg-info');
        },
        error(mensaje) {
            Alerta.error('Cargar contingencia', mensaje);
            ProgressBar.classColor('bg-danger');
        },
        carga(porcentaje, cargado, total) {
            ProgressBar.change(porcentaje);
            console.log(cargado, total);
        },
        ok(data) {
            console.log(data);
            ProgressBar.classColor('bg-success');
        },
        final() {
            ProgressBar.hide();
        }
    });
});