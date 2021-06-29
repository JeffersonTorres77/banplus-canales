<?php

class Rol extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'roles';

    public function esValido($permiso_id) {
        $rows = Permiso_Rol::where('rol_id', $this->id)->where('permiso_id', $permiso_id)->count();
        return ($rows > 0);
    }

    public function cambiarPermiso($permiso_id, $permitido) {
        if($permitido) {
            $pr = new Permiso_Rol;
            $pr->rol_id = $this->id;
            $pr->permiso_id = $permiso_id;
            $pr->save();
        }
        else {
            $pr = Permiso_Rol::where('rol_id', $this->id)->where('permiso_id', $permiso_id)->delete();
        }
    }
}