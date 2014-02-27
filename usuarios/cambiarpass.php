<?php

include_once dirname(__FILE__). "/controlador.php";


$pass = $_REQUEST['pass'];
$id_usuario = $_REQUEST['id_usuario'];

cambiarPass($id_usuario, $pass);

if (isset($_REQUEST['retorno'])){
    $retorno = $_REQUEST['retorno'];
}else{
    $retorno = "listadousuarios.php";
}

//Redireccionamos al listado de perjuicios
redirect($retorno);


