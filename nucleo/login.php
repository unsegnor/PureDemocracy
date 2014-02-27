<?php

include_once dirname(__FILE__). "/controlador.php";
include_once dirname(__FILE__). "/../permisos/controlador.php";

$login = $_POST['login'];
$pass = $_POST['pass'];

//Comprobar login y pass
$res = getUsuario($login, $pass);

if (!$res->hayerror) {

    $usuario_encontrado = $res->resultado;

    if ($usuario_encontrado) {
        //Rellenamos la sesión y redireccionamos a la página inicial
        $_SESSION['identificado'] = true;
        $_SESSION['login'] = $usuario_encontrado->login;
        $_SESSION['id_usuario'] = $usuario_encontrado->id;
        
        //Cargamos los permisos del usuario en la sesión
        $permisos_usuario = getPermisosDeUsuario($usuario_encontrado->id);
        $permisos = array();
        foreach($permisos_usuario as $permiso_usuario){
            $permisos[] = $permiso_usuario->nombre;
        }
        
        $_SESSION['permisos'] = $permisos;

        redirect(direcciones::index);
    }
}else{

//Si llegamos hasta aquí es que algo ha fallado
redirect(direcciones::index);

$_SESSION['errores'] = "Login ha fallado.".$res->errormsg;

//El error está en $res->errormsg

}

?>
