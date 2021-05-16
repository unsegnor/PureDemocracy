<?php

include_once dirname(__FILE__) . "/../ajax/funciones.php";

for ($poblacion = 1; $poblacion < 100000; $poblacion++) {
    
    $muestra = calculaTamanioMuestra($poblacion, 0.5);


    //Si la muestra es mayor que la mitad entonces la muestra es la mitad mas uno
    
    if ($muestra > $poblacion / 2) {
        $nmuestra = floor($poblacion / 2) + 1;
    } else {
        $nmuestra = floor($muestra) + 1;
    }
/*
    $abstencion = $poblacion - $nmuestra;
    //Si la abstencioón es mayor que la muestra entonces podemos representarlos, si no el error es 0 
    if ($abstencion > $nmuestra) {
        $error_real = getErrorDeMuestra($abstencion, $nmuestra);
        $error_real = $error_real * 10;
    } else {
        $error_real = 0;
    }
 */
    $error_real = getErrorDeMuestra($poblacion, $nmuestra);
     
    echo "<br>$poblacion, $muestra, $nmuestra, $error_real";
}