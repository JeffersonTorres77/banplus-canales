<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        Sesion::auth_inverse();
    }

    public function index() {
        $ir_a = Request::get('ir_a', FALSE);
        return Response::view('views/index.html', [
            'ir_a' => $ir_a
        ]);
    }

    public function acceder() {
        $user = Request::input('user');
        $pass = Request::input('pass');
        
        $usuario = Usuario::where('usuario', $user)->first();

        if($usuario != NULL) {
            if( $usuario->validar_red ) {
                $ldap = new LDAP;
                if( !$ldap->conectar($user, $pass) ) throw new Exception("Contraseña incorrecta.");
            }
            else {
                if( $usuario->clave !== $pass ) throw new Exception("Contraseña incorrecta.");
            }
            if( $usuario->activo == FALSE ) throw new Exception('Usuario no activo.');
            Sesion::crear($usuario->usuario);
            return Response::json(['login' => TRUE]);
        }
        else {
            $ldap = new LDAP;
            if( !$ldap->conectar($user, $pass) ) throw new Exception("El usuario no se encuentra.");
            $datos = $ldap->consultar_usuario($user);
            
            return Response::json([
                'login' => FALSE,
                'usuario' => $datos['usuario'],
                'nombre' => $datos['cn'],
            ]);
        }
    }

    public function registrar() {
        $user = Request::input('user');
        $pass = Request::input('pass');
        
        if(Usuario::where('usuario', $user)->first() != NULL) throw new Exception('El usuario ya existe.');

        $ldap = new LDAP;
        if( !$ldap->conectar($user, $pass) ) throw new Exception("El usuario no se encuentra.");
        $datos = $ldap->consultar_usuario($user);

        DB::beginTransaction();

        $usuario = new Usuario;
        $usuario->rol_id = 2;
        $usuario->usuario = $datos['usuario'];
        $usuario->nombres = $datos['nombres'];
        $usuario->apellidos = $datos['apellidos'];
        $usuario->correo = $datos['correo'];
        $usuario->cargo = $datos['cargo'];
        $usuario->departamento = $datos['departamento'];
        $usuario->validar_red = TRUE;
        $usuario->clave = NULL;
        $usuario->activo = TRUE;
        $usuario->save();
        
        DB::commit();

        Sesion::crear($usuario->usuario);

        return Response::json(['ok' => TRUE]);
    }
}