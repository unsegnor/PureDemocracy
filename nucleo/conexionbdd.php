<?php
include_once dirname(__FILE__). "/localconfig.php";

class Res{
    var $resultado;
    var $hayerror = false;
    var $errormsg;
}

$mysqli = new mysqli($host, $user_name, $pass, $bdd_name) or die("No se pudo conectar a la BDD.");
$mysqli->set_charset("UTF-8");

$mysqli->query("SET NAMES utf8");

function ejecutar($consulta) {
    # Para utilizar una variable global hay que indicarlo dentro de la función    
    global $mysqli;

    $resultado = $mysqli->query($consulta);

    $respuesta = new Res();
    
    if (!$resultado) {
        $respuesta->hayerror = true;
        $respuesta->errormsg = "Falló la consulta $consulta (" . $mysqli->errno . ") " . $mysqli->error;
        trigger_error("Falló la consulta $consulta (" . $mysqli->errno . ") " . $mysqli->error, E_USER_ERROR);
    } else {
        $respuesta->resultado = $resultado;
    }
    
    return $respuesta;
}

function toArray($resultado){
    $respuesta = array();
    
    while($fila = $resultado->fetch_assoc()){
        $respuesta[] = $fila;
    }
    
    return $respuesta;
}

/**
 * 
 * @global mysqli $mysqli
 * @param type $consulta
 * @return type Realiza el insert y devuelve el vector de ids asignadas
 */
function insert_id($consulta) {
    # Para utilizar una variable global hay que indicarlo dentro de la función    
    global $mysqli;
    
    $res = new Res();

    $resultado = $mysqli->query($consulta);

    if (!$resultado) {
        echo "Falló la consulta $consulta (" . $mysqli->errno . ") " . $mysqli->error;
        $res->hayerror = true;
        $res->errormsg = "Falló la consulta $consulta (" . $mysqli->errno . ") " . $mysqli->error;
    } else {
        $res->resultado = $mysqli->insert_id;
    }
    
    return $res;
}

function escape($string){
    global $mysqli;
    return $mysqli->real_escape_string($string);
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
