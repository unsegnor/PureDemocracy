<?php

include_once dirname(__FILE__) . "/../ajax/funciones.php";

$total = $_REQUEST['total'];
$error = $_REQUEST['error'];
$muestra = getTamanioMuestra($total, $error);

echo $muestra;

//Si la muestra es superior a la mitad del total entonces cogemos la mitad mas uno
if ($muestra > $total / 2) {
    $nmuestra = floor($total / 2) + 1;
} else {
    $nmuestra = floor($muestra) + 1;
}

echo "<br>" . $nmuestra;

//Ahora calculamos el error real
$error_real = getErrorDeMuestra($total-$nmuestra, $nmuestra);

echo "<br>" . $error_real;

