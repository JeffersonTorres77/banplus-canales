<?php

use Illuminate\Database\Capsule\Manager as DB;

class controlador
{
    public function __construct() {
        Sesion::auth();
    }

    public function index() {
        return Response::view('views/index.html', [
            'es_de_contingencia' => Sesion::usuario()->esDeContingencia(),
        ]);
    }

    public function api_contingencias($accion = NULL) {
        Handler::setJson();
        if($accion == NULL) throw new Exception('La acción no se ha enviado.');
        return require_once(__DIR__."/apis/contingencias.php");
    }

    public function usuarios() {
        return Response::view('views/usuarios.html');
    }

    public function api_usuarios($accion = NULL) {
        Handler::setJson();
        if($accion == NULL) throw new Exception('La acción no se ha enviado.');
        return require_once(__DIR__."/apis/usuarios.php");
    }
}