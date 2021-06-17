<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        Sesion::auth();
    }

    public function index() {
        return Response::view('views/index.html');
    }

    public function api($accion = NULL) {
        Handler::setJson();
        if($accion == NULL) throw new Exception('La acción no se ha enviado.');
        switch(strtolower($accion))
        {
            case 'datatable':
                // Basic
                $table = 'roles';
                $primaryKey = 'id';
                // Columnas
                $columns = [
                    [ 'db' => 'id', 'dt' => 'id' ],
                    [ 'db' => 'nombre', 'dt' => 'nombre' ],
                ];
                
                return json_encode( SSP::simple( $_GET, $table, $primaryKey, $columns ) );
                break;
            
            default:
                throw new Exception('Acción invalida.');
                break;
        }
    }
}