<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuario
 *
 * @author Víctor Calatayud Asensio <vcalatayud@kalati.es>
 */
class Usuario {

    var $id;
    var $nombre;
    var $apellidos;
    var $email;
    var $pass;

    static function registerNew(BDD $bdd, $nombre, $apellidos, $email, $pass) {

        //Codificamos la contraseña
        $pass_sha = sha1($pass);

        //Registramos un nuevo usuario
        $res = $bdd->ejecutar("INSERT INTO `pdbdd`.`usuarios` "
                . "(`nombre`, `apellidos`, `email`, `pass`) "
                . "VALUES (" . $bdd->escape($nombre) . ", " . $bdd->escape($apellidos) . ", " . $bdd->escape($email) . ", " . $bdd->escape($pass_sha) . ")");

        return $res;
    }

    function doLogin(BDD $bdd, $id, $pass) {
        
    }

}
