<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$n = $_REQUEST['n'];
$k = $_REQUEST['k'];

$resultado = combinaciones($n, $k);

echo $resultado."<br>";



