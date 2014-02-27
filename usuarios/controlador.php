<?php

include_once dirname(__FILE__) . "/clases.php";
include_once dirname(__FILE__) . "/../nucleo/controlador.php";

function getUsuarios() {
    if (tiene_permiso("ver_usuarios")) {
        $consulta = "SELECT * FROM usuarios";

        $resultado = ejecutar($consulta)->resultado;

        $array = toArray($resultado);

        $respuesta = convertUsuarios($array);

        return $respuesta;
    }
}

function getUsuarioPorID($id_usuario) {
    if (tiene_permiso("ver_usuarios")) {
        $consulta = "SELECT * FROM usuarios WHERE id_usuario='" . escape($id_usuario) . "'";

        $resultado = ejecutar($consulta)->resultado;

        $fila = $resultado->fetch_assoc();

        $respuesta = Usuario::convert($fila);

        return $respuesta;
    }
}

function convertUsuarios($array) {

    $respuesta = array();

    foreach ($array as $fila) {

        $o_usuario = Usuario::convert($fila);

        $respuesta[$o_usuario->id] = $o_usuario;
    }

    return $respuesta;
}

function addUsuario(Usuario $usuario) {
    if (tiene_permiso("add_usuarios")) {
        $consulta = "INSERT INTO `eideco2`.`usuarios` (`login`"
                . ", `pass`,`nombre`)"
                . " VALUES ('"
                . escape($usuario->login)
                . "','"
                . escape($usuario->pass)
                . "','"
                . escape($usuario->nombre)
                . "')";

        $res = ejecutar($consulta);

        return $res;
    }
}

function editUsuario(Usuario $usuario) {
    if (tiene_permiso("edit_usuarios")) {
        //Seteamos los datos a partir del id del usuario

        $consulta = "UPDATE usuarios SET "
                . "  nombre ='" . escape($usuario->nombre) . "'"
                . ", login='" . escape($usuario->login) . "'"
                . " WHERE id_usuario='" . escape($usuario->id) . "'";

        $res = ejecutar($consulta);
        return $res;
    }
}

function cambiarPass($id_usuario, $nueva_pass) {
    if (tiene_permiso("edit_usuarios")) {
        $pass = sha1($nueva_pass);

        $consulta = "UPDATE usuarios SET "
                . "  pass ='" . escape($pass) . "'"
                . " WHERE id_usuario='" . escape($id_usuario) . "'";

        return ejecutar($consulta);
    }
}

function addLinkProveedorUsuario($id_usuario, $id_proveedor) {
    if (tiene_permiso("edit_usuarios")) {
        $consulta = "INSERT INTO `eideco2`.`usuarios_son_proveedores`"
                . " (`usuarios_id_usuario`, `proveedores_id_proveedor`)"
                . " VALUES ('"
                . escape($id_usuario)
                . "','"
                . escape($id_proveedor)
                . "')";

        return ejecutar($consulta);
    }
}

function editLinkProveedorUsuario($id_usuario, $id_old_proveedor, $id_proveedor) {
    if (tiene_permiso("edit_usuarios")) {

        $consulta = "UPDATE usuarios_son_proveedores SET"
                . " proveedores_id_proveedor='" . escape($id_proveedor) . "'"
                . " WHERE usuarios_id_usuario='" . escape($id_usuario) . "'"
                . " AND proveedores_id_proveedor='" . escape($id_old_proveedor) . "'";
        return ejecutar($consulta);
    }
}

function addPermisoUsuario($id_usuario, $id_permiso) {
    if (tiene_permiso("edit_usuarios")) {
        $consulta = "INSERT INTO `eideco2`.`usuarios_has_permisos`"
                . " (`usuarios_id_usuario`, `permisos_id_permiso`)"
                . " VALUES ('"
                . escape($id_usuario)
                . "','"
                . escape($id_permiso)
                . "')";

        return ejecutar($consulta);
    }
}

function quitarPermisoUsuario($id_usuario, $id_permiso) {
    if (tiene_permiso("edit_usuarios")) {
        $consulta = "DELETE FROM usuarios_has_permisos WHERE"
                . " usuarios_id_usuario='" . escape($id_usuario) . "'"
                . " AND permisos_id_permiso='" . escape($id_permiso) . "'";

        return ejecutar($consulta);
    }
}
