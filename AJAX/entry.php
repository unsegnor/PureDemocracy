<?php

include_once dirname(__FILE__) . "/../general/data.php";
include_once dirname(__FILE__) . "/../objetivos/controlador.php";

$peticion = json_decode($_REQUEST['p']);

$funcionesPermitidas = array(
    'nuevaPropuesta'
    , 'test'
    , 'testParametros'
    , 'addReparacionMin'
    , 'set'
);

$respuesta = new stdClass(); //Generamos una clase estándar que rellenamos para convertir en JSON
//Si está permitida la ejecutamos y delvolvemos el resultado
if (in_array($peticion->id_funcion, $funcionesPermitidas)) {

    //Si tenemos parámetros los enviamos

    if (isset($peticion->parametros)) {
        $respuesta->resultado = call_user_func_array($peticion->id_funcion, $peticion->parametros);
    } else {
        $respuesta->resultado = call_user_func($peticion->id_funcion);
    }
} else {

    $respuesta->hayerror = true;
    $respuesta->errormsg = "Función $peticion->id_funcion no permitida.";
}

echo json_encode($respuesta);

function test() {
    return "Hola wei!";
}

function testParametros($a, $b) {
    return $a + $b;
}
