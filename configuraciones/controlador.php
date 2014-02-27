<?php

include_once dirname(__FILE__)."/clases.php";
include_once dirname(__FILE__)."/../nucleo/controlador.php";


function getConfiguraciones(){
    if (tiene_permiso("ver_configuraciones")) {
    $consulta = "SELECT * FROM configuraciones";
    
    $resultado = ejecutar($consulta)->resultado;
    
    $array = toArray($resultado);
    
    $respuesta = convertConfiguraciones($array);
    
    return $respuesta;
    }
}

function getConfiguracionPorID($id_configuracion){
    if (tiene_permiso("ver_configuraciones")) {
    $consulta = "SELECT * FROM configuraciones WHERE id_configuracion='".escape($id_configuracion)."'";
    
    $resultado = ejecutar($consulta)->resultado;
    
    $fila = $resultado->fetch_assoc();
    
    $respuesta = Configuracion::convert($fila);
    
    return $respuesta;
    }
}
function convertConfiguraciones($array){
    
    $respuesta = array();
    
    foreach($array as $fila){
        
        $o_configuracion  = Configuracion::convert($fila);
        
        $respuesta[$o_configuracion->id] = $o_configuracion;
        
    }
    
    return $respuesta;
}

function addConfiguracion(Configuracion $configuracion){
    if (tiene_permiso("add_configuraciones")) {
    
    $consulta = "INSERT INTO `eideco2`.`configuraciones` (`clave`,`valor`) VALUES ('"
            .escape($configuracion->clave)
            ."','"
            .escape($configuracion->valor)
            . "')";
    
    $res = ejecutar($consulta);
    
    return $res;
    }
    
}

function editConfiguracion(Configuracion $configuracion){
    if (tiene_permiso("edit_configuraciones")) {
    //Seteamos los datos a partir del id del configuracion
    
    $consulta = "UPDATE configuraciones SET "
            . "  clave ='".escape($configuracion->clave)."'"
            . ",  valor ='".escape($configuracion->valor)."'"
            . " WHERE id_configuracion='".escape($configuracion->id)."'";
    
    $res = ejecutar($consulta);
    return $res;
    }
}

function getConfiguracion($clave) {
    if (tiene_permiso("ver_configuraciones")) {
        $consulta = "SELECT * FROM configuraciones WHERE clave ='" . escape($clave) . "'";

        $res = ejecutar($consulta);

        if (!$res->hayerror) {

            $fila = $res->resultado->fetch_assoc();

            $res->resultado = $fila['valor'];
        }

        return $res;
    }
}