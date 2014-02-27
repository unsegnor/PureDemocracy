<?php
include_once dirname(__FILE__)."/controlador.php";

//Conformamos el configuracion que nos viene en la petición
$configuracion = Configuracion::convert($_REQUEST);

//Vemos si añadimos o modificamos
$accion = $_REQUEST['accion'];

if($accion == "add"){
    
    $res = addConfiguracion($configuracion);
    
}else if($accion == "edit"){
    
    $res = editConfiguracion($configuracion);
    
}

if($res->hayerror){
    $_SESSION['errores'] = $res->errormsg;
}

if (isset($_REQUEST['retorno'])){
    $retorno = $_REQUEST['retorno'];
}else{
    $retorno = "listadoconfiguraciones.php";
}
//Redireccionamos al listado de perjuicios
redirect($retorno);


