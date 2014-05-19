<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$total = $_REQUEST['total'];

//Para una población dada comprobamos la muestra necesaria para un error desde 0.5 hasta 0

for ($error = 0.5; $error > 0; $error -= 0.001) {

    $muestra = getTamanioMuestra($total, $error);

    $nmuestra = floor($muestra) + 1;

    echo "<br>".$error .",". $nmuestra;
}

//Al final imprimimos el último que es con error 0
echo "<br>"."0" .",". $total;



