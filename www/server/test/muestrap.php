<?php

include_once dirname(__FILE__) . "/../ajax/funciones.php";

$total = $_REQUEST['total'];
$error = $_REQUEST['error'];

//Vamos aumentando en 1 la muestra y calculando el error

//Hasta el valor sin contar los votos individuales
$maxtmuestra = getTamanioMuestra($total, $error);
$parar = false;
$tmuestra = 1;
while(!$parar){
    
    //Calculamos el error que sufrimos con esta muestra
    
    $error_actual = getErrorDeMuestra($total-$tmuestra, $tmuestra);
    
    echo "<br>".$tmuestra."-->".$error_actual;

    /*
     * Paramos si:
     * el error es inferior al error deseado
     * el tamaño de la muestra supera la mitad del total
     * el tamaño de la muestra es superior al tamaño teórico
     */
    
    if($error_actual <= $error
            || $tmuestra > $total/2
            || $tmuestra > $maxtmuestra){
        $parar = true;
    }
    
    $tmuestra++;
    
}

