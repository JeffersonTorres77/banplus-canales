<?php

use Illuminate\Database\Capsule\Manager as DB;

switch(strtolower($accion))
{
    /**
     * DataTable
     */
    case 'datatable':
        // Basic
        $table = 'contingencias';
        $primaryKey = 'id';
        // Columnas
        $columns = [
            [ 'db' => 'id', 'dt' => 'id' ],
            [
                'db' => 'usuario', 'dt' => 'usuario',
                'formatter' => function($d, $row) {
                    return Usuario::where('usuario', $d)->first();
                }
            ],
            [ 'db' => 'numero_lote', 'dt' => 'numero_lote' ],
            [ 'db' => 'nombre_emisor', 'dt' => 'nombre_emisor' ],
            [ 'db' => 'referencia_inicial', 'dt' => 'referencia_inicial' ],
            [ 'db' => 'referencia_final', 'dt' => 'referencia_final' ],
            [ 'db' => 'tipo_documento', 'dt' => 'tipo_documento' ],
            [ 'db' => 'rif_empresa', 'dt' => 'rif_empresa' ],
            [ 'db' => 'cuenta_origen', 'dt' => 'cuenta_origen' ],
            [ 'db' => 'cantidad_pagos', 'dt' => 'cantidad_pagos' ],
            [ 'db' => 'monto_total', 'dt' => 'monto_total' ],
            [ 'db' => 'fecha_ejecucion', 'dt' => 'fecha_ejecucion' ],
            [ 'db' => 'referencia', 'dt' => 'referencia' ],
            [ 'db' => 'nombre_excel', 'dt' => 'nombre_excel' ],
            [ 'db' => 'nombre_lot', 'dt' => 'nombre_lot' ],
            [ 'db' => 'created_at', 'dt' => 'fecha_registro' ],
        ];

        $data = SSP::simple( $_GET, $table, $primaryKey, $columns );
        return json_encode( $data );
    break;
    
    /**
     * Datos PreCarga
     */
    case 'datos-precarga':
        $objUsuarioContingencia = Contingencia_Usuario::where('usuario_id', Sesion::usuario()->id)->first();
        if($objUsuarioContingencia == NULL) throw new Exception('El usuario actual no tiene datos de contingencia.');

        $numero_lote = $objUsuarioContingencia->numero_lote;
        $referencia_inicial = $objUsuarioContingencia->referencia_inicial;

        return Response::json([
            'numero_lote' => $numero_lote,
            'referencia_inicial' => $referencia_inicial,
        ]);
    break;

    /**
     * Cargar archivo de contingencia
     */
    case 'cargar-contingencia':
        $archivo = Request::files('archivo', $requerido = TRUE);
        $nombre_emisor = Request::post('nombre_emisor', $requerido = TRUE);
        $numero_lote = Request::post('numero_lote', $requerido = TRUE);
        $referencia_inicial = Request::post('referencia_inicial', $requerido = TRUE);

        sleep(1);

        return Response::json([ 'ok' => TRUE ]);
    break;

    /**
     * Ninguna de las anteriores
     */
    default: throw new Exception('Acción invalida.');
}