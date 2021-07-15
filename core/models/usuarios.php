<?php

class Usuario extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'usuarios';

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public static function SinContingencia() {
        return self::whereNotIn('id', Contingencia_Usuario::pluck('usuario_id')->toArray())->get();
    }
}