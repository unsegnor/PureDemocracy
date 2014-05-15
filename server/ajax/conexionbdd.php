<?php

include_once dirname(__FILE__) . "/localconfig.php";

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
        //trigger_error("Falló la consulta $consulta (" . $mysqli->errno . ") " . $mysqli->error, E_USER_ERROR);
    } else {
        $respuesta->resultado = $resultado;
    }

    return $respuesta;
}

function toArray($resultado) {
    $respuesta = array();

    while ($fila = $resultado->fetch_assoc()) {
        $respuesta[] = $fila;
    }

    return $respuesta;
}

function toArrayID($resultado, $id) {
    $respuesta = array();

    while ($fila = $resultado->fetch_assoc()) {
        $respuesta[(int) ($fila[$id])] = $fila;
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

function escape($string) {
    global $mysqli;
    return $mysqli->real_escape_string($string);
}

function iniciar_transaccion() {
    global $mysqli;
    return $mysqli->autocommit(false);
}

function commit() {
    global $mysqli;
    $r = $mysqli->commit();
    $mysqli->autocommit(true);
    return $r;
}

function rollback() {
    global $mysqli;
    $r = $mysqli->rollback();
    $mysqli->autocommit(true);
    return $r;
}

?>
