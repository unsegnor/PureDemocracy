<?php

include_once dirname(__FILE__) . "/conexionbdd.php";
include_once dirname(__FILE__) . "/clases.php";
include_once dirname(__FILE__) . "/../usuarios/controlador.php";

function linkTo($texto, $pagina) {
    return "<a href=\"$pagina\">$texto</a>";
}

function redirect($nombre_pagina) {
    header("location:$nombre_pagina");
}

//Funciones para trabajar con fechas y conversiones

function f_php2bdd($fecha) {
    $respuesta = "NULL";
    if ($fecha != null) {
        $respuesta = "'" . escape($fecha->format(Formatos::formato_fechas_mysql)) . "'";
    }
    return $respuesta;
}

function f_bdd2php($fecha) {
    $respuesta = null;
    if ($fecha != null && $fecha != "" && $fecha != 0 && $fecha != "0") {
        $respuesta = new DateTime($fecha);
    }
    return $respuesta;
}

function f_php2html($fecha) {
    $respuesta = "";
    if ($fecha != null) {
        $respuesta = $fecha->format(Formatos::formato_fechas_html5);
    }
    return $respuesta;
}

function fyh_php2html($fecha) {
    $respuesta = "";
    if ($fecha != null) {
        $respuesta = $fecha->format(Formatos::formato_fechayhora_html5);
    }
    return $respuesta;
}

function numeric($array, $id) {
    $respuesta = 0;
    //Si no es numérico devolvemos cero
    if (isset($array[$id])) {
        $dato = $array[$id];
        if (is_numeric($dato)) {
            $respuesta = $dato;
        }
    }

    return $respuesta;
}

/**
 * Convierte el booleano desde bdd
 * @param type $dato
 * @return type
 */
function getOrZero($array, $id) {
    return isset($array[$id]) ? $array[$id] : 0;
}

function getOrNull($array, $id) {
    return isset($array[$id]) ? $array[$id] : null;
}

/**
 * Se utiliza para almacenar valores en la base de dato propagando el NULL como valor válido
 * @param type $dato
 * @return type
 */
function setOrNull($dato) {
    return $dato == null ? "NULL" : "'" . escape($dato) . "'";
}

/**
 * Se utiliza para almacenar valores en la base de datos poniendo un 0 si no hay nada como valor válido
 * @param type $dato
 * @return type
 */
function setOrZero($dato) {
    return $dato == null || $dato == "" ? 0 : "'" . escape($dato) . "'";
}

/**
 * Devuelve la fecha en el formato que queremos verla
 * @param type $fecha
 */
function f_php2str($fecha) {
    $respuesta = "";
    if ($fecha != null) {
        $respuesta = $fecha->format(Formatos::formato_fechas);
    }
    return $respuesta;
}

function fyh_php2str($fecha) {
    $respuesta = "";
    if ($fecha != null) {
        $respuesta = $fecha->format(Formatos::formato_fechayhora);
    }
    return $respuesta;
}

function f_bdd2html($fecha) {
    return f_php2html(f_bdd2php($fecha));
}

function f_bdd2str($fecha) {
    return f_php2str(f_bdd2php($fecha));
}

function getMenu() { //TODO la id de usuario la usaremos para determinar los permisos
    $respuesta = "<table border='1'><tr><td>";


    //Mostramos el nombre del usuario
    $nombre_usuario = $_SESSION['login'];
    $respuesta.= "<strong>$nombre_usuario</strong>";

    $respuesta .= "</td><td>";

    $respuesta.= linkTo("Inicio", direcciones::index);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Configuracion", direcciones::configuracion);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Poblaciones", direcciones::poblaciones);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Permisos", direcciones::permisos);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Tipos de siniestro", direcciones::tipos_siniestro);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Usuarios", direcciones::usuarios);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Entidades", direcciones::entidades);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Grupos", direcciones::grupos);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Proveedores", direcciones::proveedores);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Peritos", direcciones::peritos);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Expedientes", direcciones::expedientes);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Buscar_expediente", direcciones::buscar_expedientes);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Facturas", direcciones::facturas);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Estados reparaciones", direcciones::estados_reparaciones);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Cobros pendientes", direcciones::cobros_pendientes);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Pendientes de facturar", direcciones::pendientes_facturar);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Listos para facturar", direcciones::listos_facturar);

    $respuesta .= "</td><td>";

    $respuesta.= linkTo("Desconectarse", direcciones::logout);



    $respuesta.= "</td></tr></table>";

    return $respuesta;
}

function getAdminMenu() { //TODO la id de usuario la usaremos para determinar los permisos
    $respuesta = "<table border='1'><tr><td>";

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Reparaciones", direcciones::reparaciones);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Info_reparaciones", direcciones::info_reparaciones);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Perjudicados", direcciones::perjuicios);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Documentos", direcciones::documentos);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Servicios facturas", direcciones::servicios_facturas);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Estados reparaciones", direcciones::estados_reparaciones);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Aseguradoras", direcciones::aseguradoras);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Presupuestos(BASE)", direcciones::presupuestos);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Servicios presupuesto", direcciones::servicios_presupuesto);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Órdenes de trabajo", direcciones::ordenes_de_trabajo);

    $respuesta .= "</td><td>";
    $respuesta .= linkTo("Cobros", direcciones::cobros);

    $respuesta .= "</td><td>";

    $respuesta.= linkTo("Desconectarse", direcciones::logout);



    $respuesta.= "</td></tr></table>";

    return $respuesta;
}

function getUsuario($login, $pass) {

    $consulta = "SELECT * FROM usuarios WHERE login ='" . escape($login) . "'";

    $res = ejecutar($consulta);

    if (!$res->hayerror) {

        //Comprobamos que sólo tengamos uno
        $resultado = $res->resultado;

        if ($resultado->num_rows == 1) {
            //Lo tenemos, lo cargamos
            $fila = $resultado->fetch_assoc();

            $usuario = new Usuario();
            $usuario->login = $fila['login'];
            $usuario->pass = $fila['pass'];
            $usuario->id = $fila['id_usuario'];

            //Comprobamos la contraseña
            $sha_pass = sha1($pass);

            if ($usuario->pass == $sha_pass) {
                //Contraseña correcta -> devolvemos el usuario
                $res->resultado = $usuario;
            } else {
                $res->resultado = null;
                $res->hayerror = true;
                $res->errormsg = "Contraseña incorrecta.";
            }
        } else {
            $res->resultado = null;
            $res->hayerror = true;
            $res->errormsg = "Login incorrecto";
        }
    } else {
        $res->resultado = null;
    }


    return $res;
}

function login_libre($login) {


    $consulta = "SELECT login FROM usuarios WHERE login ='" . escape($login) . "'";

    $resultado = ejecutar($consulta)->resultado;

    if ($resultado->num_rows > 0) {
        $respuesta = false;
    } else {
        $respuesta = true;
    }

    return $respuesta;
}

function tiene_permiso($nombre_permiso) {
    //De entrada no tiene permiso
    $respuesta = false;


    //Primero comprobamos que esté logueado
    if (isset($_SESSION['identificado'])) {

        //Si es el usuario kalati tiene permiso
        if ($_SESSION['login'] == 'kalati') {
            $respuesta = true;
        } else {
            //Sino comprobamos los permisos del usuario
            $permisos = $_SESSION['permisos'];

            if (in_array_case_insensitive($nombre_permiso, $permisos)) {
                $respuesta = true;
            }
        }
    }

    return $respuesta;
}

function in_array_case_insensitive($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

/**
 * Comprueba si es una imagen y devuelve true en caso de que lo sea
 * @param type $ruta
 */
function isImagen($ruta) {

    $respuesta = false;

    $extensiones_imagen = array("gif", "jpeg", "jpg", "png");

    $temp = explode(".", $ruta);
    $extension = end($temp);

    if (in_array_case_insensitive($extension, $extensiones_imagen)) {
        $respuesta = true;
    }

    return $respuesta;
}

function utf2windows($ruta) {
    return iconv("utf-8", "cp1252", $ruta);
}

//Control de errores

function warnings($num_error, $error_msg, $archivo_error, $linea_error, $contexto_error) {
    //Van al log de warnings
}

function warningsDeUsuario($num_error, $error_msg, $archivo_error, $linea_error, $contexto_error) {
    //Se indican como advertencias en pantalla
}

function errorDeUsuario($num_error, $error_msg, $archivo_error, $linea_error, $contexto_error) {
    //Se indican como errores en pantalla
    echo "<b>Error</b> [$num_error] $error_msg en $archivo_error linea $linea_error <br>";
    echo "Finalizando la ejecución.";
    
    //También lo guardamos en el log
    error_log("Error [$num_error] $error_msg en $archivo_error linea $linea_error cuando $contexto_error");
    die();
}

function errorGrave($num_error, $error_msg, $archivo_error, $linea_error, $contexto_error) {
    //Se indican en pantalla y van al correo
    echo "<b>Error grave</b> [$num_error] $error_msg en $archivo_error linea $linea_error <br>";

    echo "Finalizando la ejecución.";

    error_log("Error [$num_error] $error_msg en $archivo_error linea $linea_error cuando $contexto_error", 1, "lacuentadevictor@gmail.com", "From: bot@idecomultiservicio.com");

    echo "Se ha enviado un correo a Víctor.";

    die();
}

set_error_handler("warnings", E_WARNING | E_NOTICE);
set_error_handler("warningsDeUsuario", E_USER_WARNING | E_USER_NOTICE);
set_error_handler("errorDeUsuario", E_USER_ERROR);
set_error_handler("errorGrave", E_ERROR | E_RECOVERABLE_ERROR);
