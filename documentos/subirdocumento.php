<?php

include_once dirname(__FILE__) . "/controlador.php";
include_once dirname(__FILE__) . "/../nucleo/controlador.php";
include_once dirname(__FILE__) . "/../configuraciones/controlador.php";

//TODO Hay que sacarla de BDD
$ruta_principal = getConfiguracion("ruta_principal")->resultado;

//Comprobamos si está asociado a algún expediente o info_reparación
$id_expediente = isset($_REQUEST['id_expediente']) ? $_REQUEST['id_expediente'] : null;
$id_info_reparacion = isset($_REQUEST['id_info_reparacion']) ? $_REQUEST['id_info_reparacion'] : null;

$ruta_relativa = $_REQUEST['ruta_relativa'];

//No permitimos que se salga del directorio principal
$ruta_relativa_sin_retrocesos = str_replace("../", "", $ruta_relativa);

$ruta = $ruta_principal . "/" . $ruta_relativa_sin_retrocesos;

//Comprobar que la extensión está permitida
$allowedExts = array("gif", "jpeg", "jpg", "png");

//Extensiones no permitidas (php)
$deniedExts = array("php");

$n_archivos = count($_FILES['archivos']['name']);
if ($n_archivos) {
    for ($i = 0; $i < $n_archivos; $i++) {

        $temp = explode(".", $_FILES["archivos"]["name"][$i]);
        $extension = end($temp);
        if (($_FILES["archivos"]["size"][$i] < 20971520)
                && !in_array_case_insensitive($extension, $deniedExts)) //TODO de momento nos saltamos la comprobación de extensiones y tipos
        /*
         * &&
          (($_FILES["archivos"]["type"][$i] == "image/gif")
          || ($_FILES["archivos"]["type"][$i] == "image/jpeg")
          || ($_FILES["archivos"]["type"][$i] == "image/jpg")
          || ($_FILES["archivos"]["type"][$i] == "image/pjpeg")
          || ($_FILES["archivos"]["type"][$i] == "text/plain")
          || ($_FILES["archivos"]["type"][$i] == "text/xml")
          || ($_FILES["archivos"]["type"][$i] == "application/pdf")
          || ($_FILES["archivos"]["type"][$i] == "application/vnd.oasis.opendocument.text")
          || ($_FILES["archivos"]["type"][$i] == "image/x-png")
          || ($_FILES["archivos"]["type"][$i] == "image/png"))
          && in_array_case_insensitive($extension, $allowedExts))
         */ {

            //Comprobamos si hay error
            if ($_FILES["archivos"]["error"][$i] > 0) {
                echo "Return Code: " . $_FILES["archivos"]["error"][$i] . "<br>";
            } else {
                echo "Upload: " . $_FILES["archivos"]["name"][$i] . "<br>";
                echo "Type: " . $_FILES["archivos"]["type"][$i] . "<br>";
                echo "Size: " . ($_FILES["archivos"]["size"][$i] / 1024) . " kB<br>";
                echo "Temp file: " . $_FILES["archivos"]["tmp_name"][$i] . "<br>";

                //Comprobamos si exsiten los directorios de la ruta

                $ruta_cp1252 = utf2windows($ruta);
                
                if (!file_exists($ruta_cp1252)) {
                    mkdir($ruta_cp1252, 0777, true);
                }

                if (file_exists($ruta . "/" . $_FILES["archivos"]["name"][$i])) {
                    echo $_FILES["archivos"]["name"][$i] . " already exists. ";
                } else {
                    
                    $ruta_del_archivo_utf8 = $ruta . "/" . $_FILES["archivos"]["name"][$i];
                    $ruta_del_archivo_cp1252 = utf2windows($ruta_del_archivo_utf8);
                    
                    move_uploaded_file($_FILES["archivos"]["tmp_name"][$i], $ruta_del_archivo_cp1252);
                    echo "Stored in: " . $ruta . "/" . $_FILES["archivos"]["name"][$i];

                    //Agregamos el documento a la base de datos
                    $documento = new Documento();
                    $documento->ruta = $ruta_relativa_sin_retrocesos . "/" . $_FILES["archivos"]["name"][$i];
                    $documento->nombre = $_FILES["archivos"]["name"][$i];
                    $documento->tipo = $_FILES["archivos"]["type"][$i];

                    $res = addDocumento($documento);

                    //Si no hay error continuamos
                    if (!$res->hayerror) {

                        $id_documento = $res->resultado;

                        //Si viene asociado a un expediente o info_reparación lo anotamos
                        if ($id_expediente != null) {
                            relacionarDocumentoConExpediente($id_documento, $id_expediente);
                        }

                        if ($id_info_reparacion != null) {
                            relacionarDocumentoConInfo_Reparacion($id_documento, $id_info_reparacion);
                        }
                    }
                }
            }
        } else {
            echo "Invalid file";
        }
    }
}

if (isset($_REQUEST['retorno'])) {
    $retorno = $_REQUEST['retorno'];
} else {
    $retorno = "listadodocumentos.php";
}
//Redireccionamos
redirect($retorno);
//var_dump($_FILES);
