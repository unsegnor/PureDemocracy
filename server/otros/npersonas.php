<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$total = 100000;

//Calcular para cada distribución mayoritaria el número de personas a las que habrá que consultar

$dist = 1;
$error_deseado = 0.5;

for ($dist = 1; $dist >= 0.5; $dist-=0.001) {

    $dist_contraria = 1 - $dist;


    //Calculamos el tamaño de la muestra necesario para el error deseado
    $nmuestra = getTamanioMuestra($total, $error_deseado);

    $error_actual = $error_deseado;
    //Calculamos las probabilidades de cada resultado para esta opción
    //Desde que ningún representante la vote hasta que la voten todos
    echo "<br>$dist";
    $suma = 0;

    $resultados = array();
    for ($votos_favorables = 0; $votos_favorables <= $nmuestra; $votos_favorables++) {

        //Se van a hacer $nmuestra ensayos sí o sí así que tendremos que contar la
        //probabilidad de que salga favorable y no

        $votos_desfavorables = $nmuestra - $votos_favorables;

        $p = pow($dist, $votos_favorables) * pow($dist_contraria, $votos_desfavorables);

        //Hay que multiplicar $p por el número de combinaciones equivalentes (distribución binomial)
        $combinaciones = combinaciones($nmuestra, $votos_favorables);

        $p *= $combinaciones;
        //echo "<br>Con un apoyo de $dist la probabilidad de obtener $votos_favorables votos de una muestra de $nmuestra es $p";
        //echo ",$p";
        //$suma+=$p;
        //Calculamos la menor reducción necesaria del error
        $porcentaje_favorable = $votos_favorables / $nmuestra;
        $minimo_favorable = $porcentaje_favorable - $error_actual;
        $maximo_favorable = $porcentaje_favorable + $error_actual;

        //Si no queda claro necesitamos más gente
        $ampliacion = 0;
        $resultado = 0; //0 -> no se sabe, 1-> aprobado, 2->rechazado
        if ($minimo_favorable > 0.5) {
            //Hemos terminado, la propuesta ha sido aprobada
            $resultado = 1;
        } else if ($maximo_favorable < 0.5) {
            //Hemos terminado, la propuesta ha sido rechazada
            $resultado = 2;
        } else {
            //Necesitamos más votantes
            //Calculamos el error deseado, lo que queda hasta el 0.5
            $error = abs($porcentaje_favorable - 0.5);

            $votantes_necesarios = getTamanioMuestra($total, $error);

            $ampliacion = $votantes_necesarios;
        }

        //Almacenamos cada resultado posible con la probabilidad de que suceda
        $resultados[] = array($votos_favorables, $p, $resultado, $ampliacion);
    }
    //echo ",$suma";
}



