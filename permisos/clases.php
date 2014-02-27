<?php

class Permiso {

    var $id;
    var $nombre;

    public static function convert($fila) {
        $r = new Permiso();
        
        $r->id = $fila['id_permiso'];
        $r->nombre = $fila['nombre'];
        
        return $r;
    }

}
