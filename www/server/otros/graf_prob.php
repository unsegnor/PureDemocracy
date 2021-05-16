<?php

include_once dirname(__FILE__) . "/../ajax/funciones.php";

$poblacion = 100000;

$muestra = getTamanioMuestra($total, 0.5);

//$muestra = floor($muestra)+1;

$distribucion_real_si = 1;
$distribucion_real_no = 0;


for($distribucion_real_si = 1; $distribucion_real_si >= 0; $distribucion_real_si-=0.001){
    
    $distribucion_real_no = 1- $distribucion_real_si;
    
    $probabilidad_todo_si = pow($distribucion_real_si, $muestra);
    
    $probabilidad_todo_no = pow($distribucion_real_no, $muestra);
    
    
    //echo "<br>Con una distribucion de si/no de $distribucion_real_si/$distribucion_real_no hay una probabilidad de $probabilidad_todo_si de coger todo si y $probabilidad_todo_no de coger todo no";
    echo "<br>$distribucion_real_si,$distribucion_real_no,$probabilidad_todo_si,$probabilidad_todo_no";
}