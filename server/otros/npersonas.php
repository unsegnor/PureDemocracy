<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$total = 100000;

//Calcular para cada distribución mayoritaria el número de personas a las que habrá que consultar

$dist = 1;
$error_deseado = 0.5;

for($dist = 1; $dist >=0.5; $dist-=0.001){
    
    $dist_contraria = 1-$dist;
    
        
    //Calculamos el tamaño de la muestra necesario para el error deseado
    $nmuestra = getTamanioMuestra($total, $error_deseado);
    
    //Calculamos las probabilidades de cada resultado para esta opción
    //Desde que ningún representante la vote hasta que la voten todos
    echo "<br>$dist";
    $suma = 0;
    
    $resultados = array();
    for($votos_favorables = 0; $votos_favorables <= $nmuestra; $votos_favorables++ ){
        
        //Se van a hacer $nmuestra ensayos sí o sí así que tendremos que contar la
        //probabilidad de que salga favorable y no
        
        $votos_desfavorables = $nmuestra-$votos_favorables;
        
        $p = pow($dist, $votos_favorables) * pow($dist_contraria, $votos_desfavorables);
        
        //Hay que multiplicar $p por el número de combinaciones equivalentes (distribución binomial)
        $combinaciones = combinaciones($nmuestra, $votos_favorables);

        $p *= $combinaciones;
        //echo "<br>Con un apoyo de $dist la probabilidad de obtener $votos_favorables votos de una muestra de $nmuestra es $p";
        
        //echo ",$p";
        //$suma+=$p;
        
        //Calculamos la menor reducción necesaria del error
        
        //Almacenamos cada resultado posible con la probabilidad de que suceda
        $resultados[] = array($votos_favorables,$p);
        
    }
    //echo ",$suma";
}



