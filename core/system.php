<?php

class Sistema
{
    public static function ArchivosDeCarpeta($rutaCarpeta, $withSubFolders = FALSE) {
        $archivos = scandir($rutaCarpeta);
        $salida = [];

        foreach($archivos as $archivo) {
            if($archivo == '.' || $archivo == '..') continue;
            $rutaArchivo = "{$rutaCarpeta}/{$archivo}";

            if(is_dir($rutaArchivo)) {
                $archivosDeCarpeta = self::ArchivosDeCarpeta($rutaArchivo);
                $salida = array_merge($salida, $archivosDeCarpeta);
            }
            else {
                array_push($salida, str_replace('\\', '/', $rutaArchivo));
            }
        }
        
        return $salida;
    }
    
    public static function ExisteCarpeta($ruta) {
        return (file_exists($ruta) && is_dir($ruta));
    }
    
    public static function ExisteArchivo($ruta) {
        return (file_exists($ruta) && is_file($ruta));
    }
}