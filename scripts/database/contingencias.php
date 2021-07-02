<?php

use Illuminate\Database\Capsule\Manager as DB;

$table_contingencias = new table_contingencias;
class table_contingencias
{
    protected $table = "contingencias";

    public function up() {
        DB::schema()->create($this->table, function ($table) {
            $table->increments('id');
            $table->string('usuario');
            $table->string('numero_lote', 3);
            $table->string('nombre_emisor', 200);
            $table->string('referencia_inicial', 12);
            $table->string('referencia_final', 12);
            $table->string('tipo_documento', 50);
            $table->string('rif_empresa', 20);
            $table->string('cuenta_origen', 20);
            $table->integer('cantidad_pagos');
            $table->float('monto_total');
            $table->date('fecha_ejecucion');
            $table->string('referencia', 12);
            $table->text('nombre_excel');
            $table->text('nombre_lot');
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