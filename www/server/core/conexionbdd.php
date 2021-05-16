<?php
include_once dirname(__FILE__) . "/clases.php";
include_once dirname(__FILE__) . "/BDD.php";

//Generamos una conexión principal a bdd
$conn = new BDD("pd-database", "pdbdd", "dbuser", "dbpass");

function getBDD(){
    global $conn;
    return $conn;
}

?>
