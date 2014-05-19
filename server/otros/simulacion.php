<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$vsi = $_REQUEST['vsi'];
$vno = $_REQUEST['vno'];
$vdep = $_REQUEST['vdep'];

//Para una población dada comprobamos la muestra necesaria para un error desde 0.5 hasta 0

for ($error = 0.5; $error > 0; $error -= 0.001) {

    $muestra = getTamanioMuestra($total, $error);

    $nmuestra = floor($muestra) + 1;

    echo "<br>".$error .",". $nmuestra;
}

//Al final imprimimos el último que es con error 0
echo "<br>"."0" .",". $total;


function getProbabilidadResultado($apoyosi, $apoyono, $apoyodep, $votosi, $votono, $votodep, $nmuestra, $pabstencion){
    
    
    
    //Probabilidad si fuera uno solo
    $r = $porcentaje_apoyo;
    
    
    
}

/**
 * 
 * @param type $npoblacion Total de la población
 * @param type $opsi Porcentaje de la población que opina Sí
 * @param type $opno Porcentaje de la población que opina No
 * @param type $opdep Porcentaje de la población que opina Depende
 * @param type $abs_ind Porcentaje de la población que se abstendría como individuo
 * @param type $abs_rep Porcentaje de los representantes que se abstendría como individuo y como representante
 * 
 * Entre todos los porcentajes deben sumar 1 ¿y el porcentaje de personas que votan individualmente?
 * podemos simplemente no tenerlo en cuenta ya que siempre sería una ayuda que votaran
 */
function simularVotacion($npoblacion, $opsi, $opno, $opdep, $abs_ind, $abs_rep){
    
    //Calculamos el número de representantes inicial
    $nrepresentantes = getTamanioMuestra($npoblacion, 0.5);
    $nrepresentantes = floor($nrepresentantes)+1;
    
    //Simulamos posibles resultados de la primera ronda
    
    //El único que nos vale para terminar es que todos apoyen la misma opción
    $probabilidad_de_todos_si = getProbabilidadResultado();
    
    
    
    //Devolvemos un array con (resultado, personas a las que se ha preguntado, probabilidad de que ocurra)
    
}



