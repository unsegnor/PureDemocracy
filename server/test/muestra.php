<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$total = $_REQUEST['total'];
$error = $_REQUEST['error'];
$muestra = getTamanioMuestra($total, $error);

echo $muestra;

$nmuestra = floor($muestra) + 1;

echo "<br>".$nmuestra;



