<?php
include_once dirname(__FILE__) . "/../nucleo/controlador.php";

//Aquí vamos a diseñar todas las acciones que podrán llevarse a cabo con los objetivos según el esquema

function nuevaPropuesta($descripcion){
    if(tiene_permiso("add_objetivo")){
    //Añadir la propuesta 
    $consulta = "INSERT INTO `pdbdd`.`objetivos` (`descripcion`, `estado_objetivo`) VALUES ('".escape($descripcion)."', '1')";
    
    $res = ejecutar($consulta);
    }else{
        $res = new Res();
        $res->hayerror = true;
        $res->errormsg[] = "Permisos insuficientes.";
    }
    return $res;
}

