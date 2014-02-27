<?php

class Configuracion {

    var $id;
    var $clave;
    var $valor;

    public static function convert($fila) {
        $r = new Configuracion();
        
        $r->id = $fila['id_configuracion'];
        $r->clave = $fila['clave'];
        $r->valor = $fila['valor'];
        
        return $r;
    }

}
