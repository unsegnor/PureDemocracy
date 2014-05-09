<?php

include_once dirname(__FILE__) . "./funciones.php";

$peticion = json_decode($_REQUEST['p']);

$funcionesPermitidas = array(
    'checkLogin'
    , 'registrarUsuario'
    , 'doLogin'
    , 'doLogout'
    , 'getSession'
);


//Si está permitida la ejecutamos y delvolvemos el resultado
if (in_array($peticion->id_funcion, $funcionesPermitidas)) {

    //Si tenemos parámetros los enviamos

    if (isset($peticion->parametros)) {
        $respuesta = call_user_func_array($peticion->id_funcion, $peticion->parametros);
    } else {
        $respuesta = call_user_func($peticion->id_funcion);
    }
    //var_dump($respuesta);
    if ($respuesta == null) {
        $respuesta = new stdClass(); //Generamos una clase estándar que rellenamos para convertir en JSON
        //Si la respuesta es null es que la función no ha podido ser llamada o se ha detenido enmedio
        $respuesta->hayerror = true;
        $respuesta->errormsg = "La función $peticion->id_funcion no existe, no es accesible o sufre de excepciones no controladas.";
    }
} else {
    $respuesta = new stdClass(); //Generamos una clase estándar que rellenamos para convertir en JSON
    $respuesta->hayerror = true;
    $respuesta->errormsg = "Función no permitida $peticion->id_funcion.";
}

echo json_encode($respuesta);

