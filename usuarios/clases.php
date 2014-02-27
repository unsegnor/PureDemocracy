<?php

class Usuario {

    var $id;
    var $nombre;
    var $login;
    var $pass;
    var $ultimo_acceso;

    public static function convert($fila) {
        $r = new Usuario();
        
        $r->id = $fila['id_usuario'];
        $r->nombre = $fila['nombre'];
        $r->login = $fila['login'];
        $r->pass = $fila['pass'];
        $r->ultimo_acceso = $fila['ultimo_acceso'];
        
        return $r;
    }

}
