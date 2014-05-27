<?php

include_once dirname(__FILE__) . "/BDD.php";
include_once dirname(__FILE__). "/LocalConfig.php";

//Generamos una conexión principal a bdd
$conn = new BDD(LocalConfig::host, LocalConfig::bdd_name, LocalConfig::user_name, LocalConfig::pass);

function getBDD(){
    global $conn;
    return $conn;
}

function ejecutar($consulta){
    global $conn;
    return $conn->ejecutar($consulta);
}

function escape($string){
    global $conn;
    return $conn->escape($string);
}

function insert_id($consulta){
    global $conn;
    return $conn->insert_id($consulta);
}

function toArray($resultado){
    global $conn;
    return $conn->toArray($resultado);
}

function toArrayID($resultado, $id){
    global $conn;
    return $conn->toArrayID($resultado, $id);
}

?>
