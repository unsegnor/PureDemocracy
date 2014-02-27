<?php
include_once dirname(__FILE__)."/controlador.php";

//Conformamos el permiso que nos viene en la petición
$permiso = Permiso::convert($_REQUEST);

//Vemos si añadimos o modificamos
$accion = $_REQUEST['accion'];

if($accion == "add"){
    
    $res = addPermiso($permiso);
    
}else if($accion == "edit"){
    
    $res = editPermiso($permiso);
    
}

if($res->hayerror){
    $_SESSION['errores'] = $res->errormsg;
}

if (isset($_REQUEST['retorno'])){
    $retorno = $_REQUEST['retorno'];
}else{
    $retorno = "listadopermisos.php";
}
//Redireccionamos al listado de perjuicios
redirect($retorno);


