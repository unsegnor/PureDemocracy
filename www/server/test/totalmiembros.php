<?php

include_once dirname(__FILE__) . "/../ajax/funciones.php";

$listamiembros = getTotalMiembrosDeGrupo(54);

var_dump($listamiembros);

echo "<br>";

actualizarTotalMiembrosDeGrupo(54);