<?php
include_once dirname(__FILE__) . "/controlador.php";
include_once dirname(__FILE__) . "/../permisos/controlador.php";

$id_usuario = $_REQUEST['id_usuario'];

//Nos llegan los permisos concedidos
$concedidos = $_REQUEST['concedidos'];

setPermisosDeUsuario($id_usuario, $concedidos);

if (isset($_REQUEST['retorno'])){
    $retorno = $_REQUEST['retorno'];
}else{
    $retorno = "listadousuarios.php";
}
//Redireccionamos al listado de perjuicios
redirect($retorno);
