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

    public function usuarios() {
        $usuarios_contingencia = Contingencia_Usuario::select('usuario_id')->get();
        $usuarios_contingencia_ids = [];
        foreach($usuarios_contingencia as $usuario) {
            array_push($usuarios_contingencia_ids, $usuario->usuario_id);
        }
        $usuarios_no_registrados = Usuario::whereNotIn('id', $usuarios_contingencia_ids)->get();

        return Response::view('views/usuarios.html', [
            'usuarios_no_registrados' => $usuarios_no_registrados
        ]);
    }

    public function api($accion = NULL) {
        Handler::setJson();
        if($accion == NULL) throw new Exception('La acción no se ha enviado.');
        switch(strtolower($accion))
        {
            /**
             * DataTable
             */
            case 'datatable-usuarios':
                // Basic
                $table = 'contingencia_usuarios';
                $primaryKey = 'id';
                // Columnas
                $columns = [
                    [ 'db' => 'id', 'dt' => 'id' ],
                    [
                        'db' => 'usuario_id', 'dt' => 'nombre',
                        'formatter' => function($d, $row) {
                            $usuario = Usuario::find($d);
                            return "{$usuario->nombres} {$usuario->apellidos}";
                        }
                    ],
                    [ 'db' => 'numero_lote', 'dt' => 'numero_lote' ],
                    [ 'db' => 'referencia_inicial', 'dt' => 'referencia_inicial' ],
                ];

                $data = SSP::simple( $_GET, $table, $primaryKey, $columns );
                return json_encode( $data );
            break;
            
            /**
             * Registrar Usuario
             */
            case 'registrar-usuario':
            break;
            
            /**
             * Modificar Usuario
             */
            case 'modificar-usuario':
            break;
            
            /**
             * Eliminar Usuario
             */
            case 'eliminar-usuario':
            break;

            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}