<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        // Validamos la sesion
        Sesion::auth();
        // Validamos los permisos del menu
        if( !Sesion::usuario()->rol->esValido('menu_usuarios') ) {
            if(!Request::esAjax()) Response::sin_permisos();
            else throw new Exception('Usted no tiene permisos para acceder a esta pagina.');
        }
    }

    public function index() {
        return Response::view('views/index.html');
    }

    public function api($accion = NULL) {
        Handler::setJson();
        if($accion == NULL) throw new Exception('La acción no se ha enviado.');
        switch(strtolower($accion))
        {
            /**
             * DataTable
             */
            case 'datatable':
                // Basic
                $table = 'usuarios';
                $primaryKey = 'id';
                // Columnas
                $columns = [
                    [ 'db' => 'id', 'dt' => 'id' ],
                    [ 'db' => 'nombres', 'dt' => 'nombres' ],
                    [ 'db' => 'apellidos', 'dt' => 'apellidos' ],
                    [
                        'db' => 'rol_id', 'dt' => 'rol',
                        'formatter' => function($d, $row) {
                            return Rol::find($d);
                        }
                    ],
                    [ 'db' => 'activo', 'dt' => 'activo' ],
                    [ 'db' => 'validar_red', 'dt' => 'validar_red' ],
                ];

                $data = SSP::simple( $_GET, $table, $primaryKey, $columns );
                return json_encode( $data );
            break;

            /**
             * Registrar
             */
            case 'registrar':
                throw new Exception('Registrar');
            break;

            /**
             * Modificar
             */
            case 'modificar':
                throw new Exception('Modificar');
            break;

            /**
             * Eliminar
             */
            case 'eliminar':
                throw new Exception('Eliminar');
            break;
            
            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}