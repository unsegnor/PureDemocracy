<?php

include_once dirname(__FILE__)."/clases.php";
include_once dirname(__FILE__)."/../nucleo/controlador.php";


function getDocumentos(){
    if (tiene_permiso("ver_documentos")) {
    $consulta = "SELECT * FROM documentos";
    
    $resultado = ejecutar($consulta)->resultado;
    
    $array = toArray($resultado);
    
    $respuesta = convertDocumentos($array);
    
    return $respuesta;
    }
}

function getDocumentoPorID($id_documento){
    if (tiene_permiso("ver_documentos")) {
    $consulta = "SELECT * FROM documentos WHERE id_documento='".escape($id_documento)."'";
    
    $resultado = ejecutar($consulta)->resultado;
    
    $fila = $resultado->fetch_assoc();
    
    $respuesta = Documento::convert($fila);
    
    return $respuesta;
    }
}
function convertDocumentos($array){
    
    $respuesta = array();
    
    foreach($array as $fila){
        
        $o_documento  = Documento::convert($fila);
        
        $respuesta[$o_documento->id] = $o_documento;
        
    }
    
    return $respuesta;
}

function addDocumento(Documento $documento){
    if (tiene_permiso("add_documentos")) {
    
    $consulta = "INSERT INTO `eideco2`.`documentos` (`ruta`, `nombre_documento`, `tipo_contenido`) VALUES ('"
            .escape($documento->ruta)
            ."','"
            .escape($documento->nombre)
            ."','"
            .escape($documento->tipo)
            . "')";
    
    $res = insert_id($consulta);
    
    return $res;
    }
    
}

function editDocumento(Documento $documento){
    if (tiene_permiso("edit_documentos")) {
    //Seteamos los datos a partir del id del documento
    
    $consulta = "UPDATE documentos SET "
            . "  ruta ='".escape($documento->ruta)."'"
            . ",  nombre_documento ='".escape($documento->nombre)."'"
            . ",  tipo_contenido ='".escape($documento->tipo)."'"
            . " WHERE id_documento='".escape($documento->id)."'";
    
    $res = ejecutar($consulta);
    return $res;
    }
}

function relacionarDocumentoConExpediente($id_documento, $id_expediente){
    $consulta = "INSERT INTO `eideco2`.`expedientes_has_documentos` "
            . " (`expedientes_id_expediente`, `documentos_id_documento`)"
            . " VALUES ('".escape($id_expediente)."'"
            . ",'".escape($id_documento)."')";
    
    return ejecutar($consulta);
}

function relacionarDocumentoConInfo_Reparacion($id_documento, $id_info_reparacion){
    $consulta = "INSERT INTO `eideco2`.`info_reparaciones_has_documentos` "
            . " (`info_reparaciones_id_info_reparacion`, `documentos_id_documento`)"
            . " VALUES ('".escape($id_info_reparacion)."'"
            . ",'".escape($id_documento)."')";
    
    return ejecutar($consulta);
}

function getDocumentosDeExpediente($id_expediente){
    if (tiene_permiso("ver_documentos")) {
    $consulta = "SELECT documentos.* FROM documentos,expedientes_has_documentos"
            . " WHERE"
            . " expedientes_has_documentos.documentos_id_documento = documentos.id_documento "
            . " AND expedientes_has_documentos.expedientes_id_expediente ='".  escape($id_expediente)."'";
    
    $resultado = ejecutar($consulta)->resultado;
    
    $array = toArray($resultado);
    
    $respuesta = convertDocumentos($array);
    
    return $respuesta;
    }
}

function getDocumentosDeInfo_Reparacion($id_info_reparacion){
    if (tiene_permiso("ver_documentos")) {
    $consulta = "SELECT documentos.* FROM documentos,info_reparaciones_has_documentos"
            . " WHERE"
            . " info_reparaciones_has_documentos.documentos_id_documento = documentos.id_documento "
            . " AND info_reparaciones_has_documentos.info_reparaciones_id_info_reparacion ='".  escape($id_info_reparacion)."'";
    
    $resultado = ejecutar($consulta)->resultado;
    
    $array = toArray($resultado);
    
    $respuesta = convertDocumentos($array);
    
    return $respuesta;
    }
}