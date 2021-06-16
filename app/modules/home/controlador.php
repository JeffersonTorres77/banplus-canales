<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        Sesion::auth();
    }

    public function index() {
        return Response::view('views/index.html', [
            'isSesion' => Sesion::validar()
        ]);
    }

    public function test() {
        return Response::view('views/index.html', [
            'isSesion' => Sesion::validar()
        ]);
    }
}