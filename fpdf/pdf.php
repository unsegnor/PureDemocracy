<?php

//Generamos el pdf
require('/fpdf.php');


function t($texto) {
    return iconv('UTF-8', 'windows-1252', $texto);
}
