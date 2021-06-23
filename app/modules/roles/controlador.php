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
            /**
             * DataTable
             */
            case 'datatable':
                // Basic
                $table = 'roles';
                $primaryKey = 'id';
                // Columnas
                $columns = [
                    [ 'db' => 'id', 'dt' => 'id' ],
                    [ 'db' => 'nombre', 'dt' => 'nombre' ],
                ];

                $data = SSP::simple( $_GET, $table, $primaryKey, $columns );
                foreach($data['data'] as $key => $value) {
                    $data['data'][$key]['cant_usuarios'] = Usuario::where('rol_id', $value['id'])->count();         
                }
                
                return json_encode( $data );
            break;

            /**
             * Registrar
             */
            case 'registrar':
                $nombre = Request::input('nombre', $obligatorio = TRUE);

                if( Rol::where('nombre', $nombre)->count() > 0 ) throw new Exception("El rol <b>{$nombre}</b> ya existe.");

                $rol = new Rol;
                $rol->nombre = $nombre;
                $rol->save();

                return Response::json([ 'ok' => TRUE ]);
            break;

            /**
             * Modificar
             */
            case 'modificar':
                $id = Request::input('id', $obligatorio = TRUE);
                $nombre = Request::input('nombre', $obligatorio = TRUE);
                
                $rol = Rol::find($id);
                if($rol == NULL) throw new Exception("El rol solicitado ({$id}) no existe.");
                if( Rol::where('nombre', $nombre)->where('id', '<>', $rol->id)->count() > 0 ) throw new Exception("El rol <b>{$nombre}</b> ya existe.");
                $rol->nombre = $nombre;
                $rol->save();

                return Response::json([ 'ok' => TRUE ]);
            break;

            /**
             * Eliminar
             */
            case 'eliminar':
                $id = Request::input('id', $obligatorio = TRUE);

                DB::beginTransaction();

                $rol = Rol::find($id);
                if($rol == NULL) throw new Exception("El rol solicitado ({$id}) no existe.");

                $cant_usuarios = Usuario::where('rol_id', $rol->id)->count();
                if($cant_usuarios > 0) {
                    $reemplazo_id = Request::input('rol_id-reemplazo', $obligatorio = TRUE);
                    $rol_reemplazo = Rol::find($reemplazo_id);
                    if($rol_reemplazo == NULL) throw new Exception("El rol de reemplazo solicitado ({$reemplazo_id}) no existe.");
                    if($rol_reemplazo->id == $rol->id) throw new Exception('El rol de reemplazo no puede ser el mismo rol a eliminar.');
                    Usuario::where('rol_id', $rol->id)->update([
                        'rol_id' => $rol_reemplazo->id
                    ]);
                }

                $rol->delete();
                DB::commit();

                return Response::json([ 'ok' => TRUE ]);
            break;
            
            /**
             * Ninguna de las anteriores
             */
            default: throw new Exception('Acción invalida.');
        }
    }
}