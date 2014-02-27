<?php
include_once dirname(__FILE__)."/controlador.php";

//Conformamos el documento que nos viene en la petición
$documento = Documento::convert($_REQUEST);

//Vemos si añadimos o modificamos
$accion = $_REQUEST['accion'];

if($accion == "add"){
    
    $res = addDocumento($documento);
    
}else if($accion == "edit"){
    
    $res = editDocumento($documento);
    
}

if($res->hayerror){
    $_SESSION['errores'] = $res->errormsg;
}

if (isset($_REQUEST['retorno'])){
    $retorno = $_REQUEST['retorno'];
}else{
    $retorno = "listadodocumentos.php";
}
//Redireccionamos al listado de perjuicios
redirect($retorno);


