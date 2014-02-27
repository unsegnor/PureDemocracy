<?php

include_once dirname(__FILE__)."/controlador.php";
include_once dirname(__FILE__)."/../configuraciones/controlador.php";

$id_documento = $_REQUEST['id_documento'];

//Cargamos la ruta principal
$ruta_principal = getConfiguracion("ruta_principal")->resultado;

//Recuperamos el documento
$documento = getDocumentoPorID($id_documento);

//Determinamos el tipo de lo que estamos mostrando
header("Content-type: ".$documento->tipo);

//Determinamos el nombre (por si no es el mismo con el que lo hemos almacenado
header('Content-Disposition: attachment; filename="'.$documento->nombre.'"');

$ruta_total = utf2windows($ruta_principal."/".$documento->ruta);

//echo $ruta_total;

//Indicamos la ruta real del archivo y lo mostramos
readfile($ruta_total);
?> 
