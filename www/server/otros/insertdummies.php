<?php

include_once dirname(__FILE__) . "/../ajax/funciones.php";

$inicio = $_REQUEST['primero'];
$cantidad = $_REQUEST['cantidad'];

$consulta = "INSERT INTO `pdbdd`.`usuario` (`nombre`, `apellidos`, `email`, `pass`, `verificado`) VALUES ";

$primero = true;

for ($n = $inicio; $n < $cantidad; $n++) {

    if($primero){
        $primero = false;
    }else{
        $consulta.= ",";
    }
    
    $consulta.=  "('Mr.Dummie$n', 'Dum Dum$n', 'soyfalso$n@falso.fal', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 0)";
}

var_dump(ejecutar($consulta));