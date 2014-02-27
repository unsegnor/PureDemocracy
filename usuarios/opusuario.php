<?php
include_once dirname(__FILE__)."/controlador.php";

//Conformamos el usuario que nos viene en la petición
$usuario = Usuario::convert($_REQUEST);

//Vemos si añadimos o modificamos
$accion = $_REQUEST['accion'];

if($accion == "add"){
    
    $res = addUsuario($usuario);
    
}else if($accion == "edit"){
    
    $res = editUsuario($usuario);
    
}

if($res->hayerror){
    $_SESSION['errores'] = $res->errormsg;
}

if (isset($_REQUEST['retorno'])){
    $retorno = $_REQUEST['retorno'];
}else{
    $retorno = "listadousuarios.php";
}
//Redireccionamos al listado de perjuicios
redirect($retorno);


