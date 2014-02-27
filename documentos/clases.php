<?php

class Documento {

    var $id;
    var $ruta;
    var $nombre;
    var $tipo;

    public static function convert($fila) {
        $r = new Documento();
        
        $r->id = $fila['id_documento'];
        $r->ruta = $fila['ruta'];
        $r->nombre = $fila['nombre_documento'];
        $r->tipo = $fila['tipo_contenido'];
        
        return $r;
    }

}
