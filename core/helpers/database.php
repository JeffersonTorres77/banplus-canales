<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public static function iniciar() {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => config('DB.DRIVER', 'mysql'),
            'host'      => config('DB.HOST', 'localhost'),
            'port'      => config('DB.PUERTO', '3306'),
            'database'  => config('DB.NOMBRE', 'database'),
            'username'  => config('DB.USUARIO', 'root'),
            'password'  => config('DB.CLAVE', 'password'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }
}