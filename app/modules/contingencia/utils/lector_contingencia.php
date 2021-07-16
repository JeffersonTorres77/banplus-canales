<?php

class LectorContingencia
{
    /**
     * Atributos internos
     */
    protected $rutaArchivo;
    protected $reader;
    protected $spreadsheet;
    protected $sheet;
    protected $fila_inicio_pagos = 9;
    protected $fila_fin_pagos;

    /**
     * Atributos publicos
     */
    public $tipo_documento;
    public $rif_empresa;
    public $cuenta_origen;
    public $cantidad_pagos;
    public $total;
    public $fecha_ejecucion;
    public $referencia;
    public $pagos;

    /**
     * Valores fijos
     */
    protected const ELOT                    = "ELOT";
    protected const BIN_BANCO               = "0174";
    protected const IDENTIFICADOR_ORIGEN    = "000";
    protected const CODIGO_MONEDA           = "VES";
    protected const TIPO_OPERACION          = "010";
    protected const SUBTIPO_CREDITO         = "220";
    protected const CAMPOS_ADICIONALES      = "NO INDICADA                                                                     0000000000000000000000000CREDITO DIRECTO ORDINARIO                                                       ";

    /**
     * Constructor
     */
    public function __construct($rutaArchivo) {
        if( !Sistema::ExisteArchivo($rutaArchivo) ) throw new Exception("El archivo '{$rutaArchivo}' no existe.");
        $this->rutaArchivo = $rutaArchivo;
        $tipoArchivo = \PhpOffice\PhpSpreadsheet\IOFactory::identify($this->rutaArchivo);
        $this->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($tipoArchivo);
        $this->spreadsheet = $this->reader->load($this->rutaArchivo);
        $this->sheet = $this->spreadsheet->getSheet(0);
        $this->fila_fin_pagos = $this->sheet->getHighestRow();

        $this->DatosBasicos();
        $this->DatosPagos();
    }

    /**
     * Datos Basicos
     * Datos del encabezado del excel
     */
    protected function DatosBasicos() {
        $this->tipo_documento   = $this->sheet->getCell('B5')->getValue();
        $this->rif_empresa      = $this->sheet->getCell('C5')->getValue();
        $this->cuenta_origen    = $this->sheet->getCell('D5')->getValue();
        $this->fecha_ejecucion  = now('Y-m-d');
        $this->referencia       = $this->sheet->getCell('H5')->getValue();
    }

    /**
     * Datos de los pagos
     * Apartir de B8-I8, hasta donde llegue
     */
    protected function DatosPagos() {
        $cantidad_pagos = 0;
        $total = 0;
        $this->pagos = [];
        for($i=$this->fila_inicio_pagos; $i<=$this->fila_fin_pagos; $i++)
        {
            $tipo_documento     = $this->sheet->getCell("B{$i}")->getValue();
            $numero_documento   = $this->sheet->getCell("C{$i}")->getValue();
            $nombre             = $this->sheet->getCell("D{$i}")->getValue();
            $cuenta             = $this->sheet->getCell("E{$i}")->getValue();
            $monto              = $this->sheet->getCell("F{$i}")->getValue();
            $referencia         = $this->sheet->getCell("G{$i}")->getValue();
            $correo             = $this->sheet->getCell("H{$i}")->getValue();
            $ejecutar           = $this->sheet->getCell("I{$i}")->getValue();

            $ejecutar = (strtolower($ejecutar) == 'si') ? TRUE : FALSE;
            if(!$ejecutar) continue;

            $cantidad_pagos += 1;
            $total += $monto;

            array_push($this->pagos, [
                "tipo_documento"    => $tipo_documento,
                "numero_documento"  => $numero_documento,
                "nombre"            => $nombre,
                "cuenta"            => $cuenta,
                "monto"             => $monto,
                "referencia"        => $referencia,
                "correo"            => $correo,
                "ejecutar"          => $ejecutar,
            ]);
        }

        $this->pagos = json_decode( json_encode($this->pagos) );
        $this->cantidad_pagos = $cantidad_pagos;
        $this->total = $total;
    }
    
    /**
     * Generar contenido del LOT
     */
    public function GenerarLOT($numero_lote) {
        /**
         * Encabezado
         */
        // Numero lote
        $numero_lote = str_pad($numero_lote, 3, '0', STR_PAD_LEFT);
        //Cantidad de registros
        $cantidad_pagos = str_pad($this->cantidad_pagos, 4, '0', STR_PAD_LEFT);
        // Total
        $total = round($this->total * 100, 0);
        $total = str_pad($total, 16, '0', STR_PAD_LEFT);
        $lot_encabezado = self::ELOT.self::BIN_BANCO.self::IDENTIFICADOR_ORIGEN.$numero_lote.self::CODIGO_MONEDA.$cantidad_pagos.$total;

        /**
         * Pagos
         */
        $lot_pagos = "";
        foreach($this->pagos as $pago) {
            
        }
        
        /**
         * Salida
         */
        $salida = $lot_encabezado;
        return $salida;
    }
}