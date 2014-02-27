<?php

include_once dirname(__FILE__) . "/clases.php";
include_once dirname(__FILE__) . "/../nucleo/controlador.php";

function getPermisos() {

    $consulta = "SELECT * FROM permisos";

    $resultado = ejecutar($consulta)->resultado;

    $array = toArray($resultado);

    $respuesta = convertPermisos($array);

    return $respuesta;
}

function getPermisoPorID($id_permiso) {
    $consulta = "SELECT * FROM permisos WHERE id_permiso='" . escape($id_permiso) . "'";

    $resultado = ejecutar($consulta)->resultado;

    $fila = $resultado->fetch_assoc();

    $respuesta = Permiso::convert($fila);

    return $respuesta;
}

function convertPermisos($array) {

    $respuesta = array();

    foreach ($array as $fila) {

        $o_permiso = Permiso::convert($fila);

        $respuesta[$o_permiso->id] = $o_permiso;
    }

    return $respuesta;
}

function addPermiso(Permiso $permiso) {
    if (tiene_permiso("add_permisos")) {
        $consulta = "INSERT INTO `eideco2`.`permisos` (`nombre`) VALUES ('"
                . escape($permiso->nombre)
                . "')";

        $res = ejecutar($consulta);

        return $res;
    }
}

function editPermiso(Permiso $permiso) {
    if (tiene_permiso("edit_permisos")) {
        //Seteamos los datos a partir del id del permiso

        $consulta = "UPDATE permisos SET "
                . "  nombre ='" . escape($permiso->nombre) . "'"
                . " WHERE id_permiso='" . escape($permiso->id) . "'";

        $res = ejecutar($consulta);
        return $res;
    }
}

function getPermisosDeUsuario($id_usuario) {
    $consulta = "SELECT * FROM permisos, usuarios_has_permisos WHERE "
            . " permisos.id_permiso = usuarios_has_permisos.permisos_id_permiso"
            . " AND usuarios_has_permisos.usuarios_id_usuario='" . escape($id_usuario) . "'";

    $resultado = ejecutar($consulta)->resultado;

    $array = toArray($resultado);

    $respuesta = convertPermisos($array);

    return $respuesta;
}

function setPermisosDeUsuario($id_usuario, $permisos) {
    if (tiene_permiso("edit_permisos")) {

        //Borramos todos los permisos del usuario
        borrarPermisosDeUsuario($id_usuario);

        //Si hay nuevos que crear
        if (count($permisos) > 0) {

            //Crear los nuevos
            $consulta = "INSERT INTO `eideco2`.`usuarios_has_permisos`"
                    . " (`usuarios_id_usuario`, `permisos_id_permiso`)"
                    . " VALUES";

            $primero = true;

            foreach ($permisos as $permiso) {

                if ($primero) {
                    $primero = false;
                } else {
                    $consulta.=",";
                }

                $consulta.= " ('".  escape($id_usuario)."','". escape($permiso) . "')";
            }

            $res = ejecutar($consulta);
        }
        // sino hemos terminado
    }
}

function borrarPermisosDeUsuario($id_usuario) {
    if (tiene_permiso("edit_permisos")) {

        $consulta = "DELETE FROM usuarios_has_permisos WHERE"
                . " usuarios_id_usuario='" . escape($id_usuario) . "'";

        $res = ejecutar($consulta);
    }
}
