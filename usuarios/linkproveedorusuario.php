<?php

include_once dirname(__FILE__) . "/controlador.php";

$id_usuario = $_REQUEST['id_usuario'];
$id_proveedor = $_REQUEST['id_proveedor'];

$accion = $_REQUEST['accion'];

if($accion == 'add'){
    addLinkProveedorUsuario($id_usuario, $id_proveedor);
}else if($accion == 'edit'){
    $id_old_proveedor = $_REQUEST['id_old_proveedor'];
    
    editLinkProveedorUsuario($id_usuario, $id_old_proveedor, $id_proveedor);
}

if (isset($_REQUEST['retorno'])){
    $retorno = $_REQUEST['retorno'];
}else{
    $retorno = "listadousuarios.php";
}
//Redireccionamos al listado de perjuicios
redirect($retorno);
        

