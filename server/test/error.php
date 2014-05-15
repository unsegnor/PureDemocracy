<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$total = $_REQUEST['total'];
$muestra = $_REQUEST['muestra'];
$error = getErrorDeMuestra($total, $muestra);

echo $error."<br>";

echo 50+($error*100);



