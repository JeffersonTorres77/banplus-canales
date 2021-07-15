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
        return Response::view('views/usuarios.html');
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
                        'db' => 'usuario_id', 'dt' => 'usuario',
                        'formatter' => function($d, $row) {
                            $usuario = Usuario::find($d);
                            return $usuario;
                        }
                    ],
                    [ 'db' => 'numero_lote', 'dt' => 'numero_lote' ],
                    [ 'db' => 'referencia_inicial', 'dt' => 'referencia_inicial' ],
                ];

                $data = SSP::simple( $_GET, $table, $primaryKey, $columns );
                return json_encode( $data );
            break;

            /**
             * Consultar usuarios disponibles para el registro
             */
            case 'usuarios-disponibles':
                return Response::json([ 'usuarios' => Usuario::SinContingencia() ]);
            break;

            /**
             * Registrar Usuario
             */
            case 'registrar-usuario':
                // Tomamos los parametros
                $usuario_id = Request::input('usuario_id', $requerido = TRUE);
                $numero_lote = Request::input('numero_lote', $requerido = TRUE);
                $referencia_inicial = Request::input('referencia_inicial', $requerido = TRUE);

                // Validamos
                $objUsuario = Usuario::find($usuario_id);
                if($objUsuario == NULL) throw new Exception('El usuario no existe.');
                if(Contingencia_Usuario::where('usuario_id', $objUsuario->id)->count() > 0) throw new Exception('El usuario ya esta registrado en la contingencia.');
                if( !is_numeric($numero_lote) ) throw new Exception("El campo 'Numero de lote' debe ser numerico.");
                if( !is_numeric($referencia_inicial) ) throw new Exception("El campo 'Referencia inicial' debe ser numerico.");

                // Convertimos
                $numero_lote = (int) $numero_lote;
                $referencia_inicial = (int) $referencia_inicial;

                // Guardamos
                DB::beginTransaction();

                $objUsuarioContingencia = new Contingencia_Usuario;
                $objUsuarioContingencia->usuario_id = $objUsuario->id;
                $objUsuarioContingencia->numero_lote = $numero_lote;
                $objUsuarioContingencia->referencia_inicial = $referencia_inicial;
                $objUsuarioContingencia->save();

                DB::commit();

                // Respondemos
                return Response::json([ 'ok' => TRUE ]);
            break;
            
            /**
             * Editar Usuario
             */
            case 'editar-usuario':
                // Tomamos los parametros
                $usuario_id = Request::input('usuario_id', $requerido = TRUE);
                $numero_lote = Request::input('numero_lote', $requerido = TRUE);
                $referencia_inicial = Request::input('referencia_inicial', $requerido = TRUE);
                
                // Validamos
                $objUsuario = Contingencia_Usuario::where('usuario_id', $usuario_id)->first();
                if($objUsuario == NULL) throw new Exception('El usuario no existe.');
                if( !is_numeric($numero_lote) ) throw new Exception("El campo 'Numero de lote' debe ser numerico.");
                if( !is_numeric($referencia_inicial) ) throw new Exception("El campo 'Referencia inicial' debe ser numerico.");
                
                // Convertimos
                $numero_lote = (int) $numero_lote;
                $referencia_inicial = (int) $referencia_inicial;

                // Guardamos
                DB::beginTransaction();

                $objUsuario->numero_lote = $numero_lote;
                $objUsuario->referencia_inicial = $referencia_inicial;
                $objUsuario->save();

                DB::commit();

                // Respondemos
                return Response::json([ 'ok' => TRUE ]);
            break;
            
            /**
             * Eliminar Usuario
             */
            case 'eliminar-usuario':
                // Tomamos los parametros
                $usuario_id = Request::input('usuario_id', $requerido = TRUE);
                
                // Validamos
                $objUsuario = Contingencia_Usuario::where('usuario_id', $usuario_id)->first();
                if($objUsuario == NULL) throw new Exception('El usuario no existe.');

                // Eliminamos
                DB::beginTransaction();

                $objUsuario->delete();

                DB::commit();

                // Respondemos
                return Response::json([ 'ok' => TRUE ]);
            break;

            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}