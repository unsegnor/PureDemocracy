<?php
include_once dirname(__FILE__) . "/clases.php";
include_once dirname(__FILE__) . "/BDD.php";
include_once dirname(__FILE__). "/localconfig.php";

//Generamos una conexión principal a bdd
$conn = new BDD(LocalConfig::host, LocalConfig::bdd_name, LocalConfig::user_name, LocalConfig::pass);

function getBDD(){
    global $conn;
    return $conn;
}

?>
