<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_contingencia_usuarios = new table_contingencia_usuarios;
class table_contingencia_usuarios
{
    protected $table = "contingencia_usuarios";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');

            $table->integer('usuario_id')->unsigned()->index()->unique();
            $table->foreign('usuario_id')->references('id')->on('usuarios');

            $table->integer('numero_lote');
            $table->integer('referencia_inicial');
            $table->timestamps();
        });

        return $this;
    }

    public function down() {
        DB::schema()->dropIfExists($this->table);

        return $this;
    }

    public function default() {
        return $this;
    }
}