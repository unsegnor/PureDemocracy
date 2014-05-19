<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$total = $_REQUEST['total'];
$error = $_REQUEST['error'];

//Calculamos la muestra necesaria
$muestra = getTamanioMuestra($total, $error);

echo "<br>" . $muestra;

$muestra2 = $muestra;

for ($i = 0; $i < 50; $i++) {
//Calculamos la muestra necesaria para representar al resto
    $muestra2 = getTamanioMuestra($total - $muestra2, $error);

    echo "<br>" . $muestra2;
}

$nmuestra = floor($muestra2) + 1;

echo "<br>" . $nmuestra;

//Ahora calculamos el error real
$error_real = getErrorDeMuestra($total - $nmuestra, $nmuestra);

echo "<br>" . $error_real;

