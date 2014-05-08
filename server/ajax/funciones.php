<?php

include_once dirname(__FILE__) . "./../core/clases.php";
include_once dirname(__FILE__) . "./conexionbdd.php";

function checkLogin() {
    $res = new Res();

    //Comprobamos en la sesión si el usuario actual está identificado
    if (isset($_SESSION['pd_identificado']) && $_SESSION['pd_identificado']) {
        $res->resultado = true;
    } else {
        $res->resultado = false;
    }

    return $res;
}

function doLogin($email, $pass) {
    //Logeamos con el id y la contraseña
    
    //Codificamos la contraseña
    $pass_sha = sha1($pass);
    
    //Comprobamos si existe alguna cuenta con ese email y contraseña
    $res = ejecutar("SELECT usuarios.nombre"
            . ", usuarios.apellidos"
            . ", usuarios.email"
            . ", usuarios.verificado"
            . " FROM usuarios WHERE"
            . " usuarios.email = '".escape($email)."'"
            . " AND usuarios.pass = '".escape($pass_sha)."'");
    
    if(!$res->hayerror){
        
        //Si receibimos algún resultado lo logueamos
        if($res->resultado->num_rows > 0){
            $fila = $res->resultado->fetch_assoc();
            
            $_SESSION['pd_identificado'] = true;
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['apellidos'] = $fila['apellidos'];
            $_SESSION['email'] = $fila['email'];
            $_SESSION['verificado'] = $fila['verificado'];
            
            $res->resultado = true;
        }else{
            $res->hayerror = true;
            $res->errormsg = "No existe una cuenta con ese correo y contraseña.";
        }
        
    }
    
    return $res;
    
}

function doLogout(){
    unset($_SESSION);
    session_destroy();
    
    $res = new Res();
    $res->resultado = true;
    
    return $res;
}

function registrarUsuario($nombre, $apellidos, $email, $pass) {

    //Comprobamos si ya existe la dirección de email
    $res = existeEmail($email);

    if (!$res->hayerror) {

        if (!$res->resultado) {

            $pass_sha = sha1($pass);

            $res = ejecutar("INSERT INTO `pdbdd`.`usuarios` "
                    . "(`nombre`, `apellidos`, `email`, `pass`) "
                    . " VALUES ('" . escape($nombre) . "'"
                    . ",'" . escape($apellidos) . "'"
                    . ",'" . escape($email) . "'"
                    . ",'" . escape($pass_sha) . "')");
        } else {
            $res->hayerror = true;
            $res->errormsg = "La dirección de email ya existe. "
                    . "Confirma que es tu dirección. "
                    . "Comprueba si ya tienes una cuenta abierta "
                    . "o inténtalo de nuevo más tarde.";
        }
    }

    return $res;
}

function existeEmail($email) {

    $res = ejecutar("SELECT COUNT(*) as existe FROM usuarios WHERE email='" . escape($email) . "'");

    if (!$res->hayerror) {
        //Recogemos el resultado
        $fila = $res->resultado->fetch_assoc();

        $existe = $fila['existe'];

        if ($existe > 0) {
            $res->resultado = true;
        } else {
            $res->resultado = false;
        }
    }

    return $res;
}

function getUsuarioActual(){
    
    //Comprobamos que estemos logueados
    $res = checkLogin();
    
    if(!$res->hayerror){
        
        if($res->resultado){
            //Estamos logueados
            
        }else{
            //No estamos logueados
        }
    }
    
    return $res;
}