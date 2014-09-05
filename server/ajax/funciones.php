<?php

include_once dirname(__FILE__) . "./constantes.php";
include_once dirname(__FILE__) . "./../core/clases.php";
include_once dirname(__FILE__) . "./conexionbdd.php";

function checkLogin() {
    $res = false;
//Comprobamos en la sesión si el usuario actual está identificado
    if (isset($_SESSION['pd_identificado']) && $_SESSION['pd_identificado']) {
        $res = true;
    }
    return $res;
}

//Necesita estar logueado para pasar a la siguiente línea
function nl() {
    if (!checkLogin()) {
        throw new Exception("Necesita estar logueado en el sistema.");
    }
}

function doLogin($email, $pass) {
//Logeamos con el id y la contraseña
//Codificamos la contraseña
    $pass_sha = sha1($pass);

//Comprobamos si existe alguna cuenta con ese email y contraseña
    $res = ejecutar("SELECT usuario.nombre"
            . ", usuario.idusuario"
            . ", usuario.apellidos"
            . ", usuario.email"
            . ", usuario.verificado"
            . " FROM usuario WHERE"
            . " usuario.email = '" . escape($email) . "'"
            . " AND usuario.pass = '" . escape($pass_sha) . "'");

//Si receibimos algún resultado lo logueamos
    if ($res->num_rows > 0) {
        $fila = $res->fetch_assoc();

        $_SESSION['pd_identificado'] = true;
        $_SESSION['idusuario'] = $fila['idusuario'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['apellidos'] = $fila['apellidos'];
        $_SESSION['email'] = $fila['email'];
        $_SESSION['verificado'] = $fila['verificado'];

        $res = true;
    } else {
        throw new Exception("No existe una cuenta con ese correo y contraseña.");
    }

    return $res;
}

function verificarloginfacebook($userID, $token) {

    //Enviamos el token del usuario y el nuestro público a facebook para que nos devuelva los datos relacionados

    $res = false;

    $user_token_url = urlencode($token);
    $app_id = '605582532896240';
    $app_token = '605582532896240|fVsRDcw9D5z3yXIIG6eyvK0bakw';

    //Hacemos una llamada a facebook para que nos verifique el token
    $url = "https://graph.facebook.com/debug_token?input_token=$user_token_url&access_token=$app_token";

    $respuesta = file_get_contents($url);

    //Aquí hemos recibido la información de facebook para validarla
    //Decodificamos el json
    $r = json_decode($respuesta);
    $datos = $r->data;

    //comprobamos que sea válida la asociación
    //y que coincidan los identificadores de usuario y aplicación
    if ($datos->is_valid && $datos->app_id == $app_id & $datos->user_id == $userID) {
        $res = true;
    }
    //Si no coinciden o falla algo sigue siendo false

    return $res;
}

function loginfacebook($nombre, $apellidos, $email, $userID, $token, $expiresin, $verificado) {

    //Hay que validar la información para que nadie pueda suplantar a un usuario de facebook

    $respuesta = false;

    if (verificarloginfacebook($userID, $token)) {

        //Comprobamos si existe el userID en facebook

        $consulta = "SELECT idusuario, nombre, apellidos, email, verificado FROM usuario WHERE fbid = '" . escape($userID) . "'";
        $res = ejecutar($consulta);

        if ($res->num_rows > 0) {
            //Existe como usuario de facebook anterior así que rellenamos la sesión
            $fila = $res->fetch_assoc();

            $_SESSION['pd_identificado'] = true;
            $_SESSION['idusuario'] = $fila['idusuario'];
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellidos'] = $apellidos;
            $_SESSION['email'] = $email;
            $_SESSION['verificado'] = $verificado ? 1 : 0;

            //Actualizamos también los datos por si han cambiado desde la última conexión
            $respuesta = ejecutar("UPDATE usuario SET "
                    . " nombre = '" . escape($nombre) . "'"
                    . ", apellidos = '" . escape($apellidos) . "'"
                    . ", email = '" . escape($email) . "'"
                    . ", fbid = '" . escape($userID) . "'"
                    . ", fbtoken = '" . escape($token) . "'"
                    //." fbexpiresin = ".escape($apellidos)
                    . ", verificado = " . ($verificado ? 1 : 0)
                    . " WHERE idusuario = " . escape($fila['idusuario']));
        } else {

            //Comprobamos si existe el email ya en BDD
            $consulta = "SELECT idusuario, nombre, apellidos, email, verificado FROM usuario WHERE email = '" . escape($email) . "'";
            $res = ejecutar($consulta);

            if ($res->num_rows > 0) {

                //Si ya existe entonces lo asociamos a la cuenta
                $fila = $res->fetch_assoc();

                $_SESSION['pd_identificado'] = true;
                $_SESSION['idusuario'] = $fila['idusuario'];
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellidos'] = $apellidos;
                $_SESSION['email'] = $email;
                $_SESSION['verificado'] = $verificado ? 1 : 0;

                //Y lo actualizamos
                $respuesta = ejecutar("UPDATE usuario SET "
                        . " nombre = '" . escape($nombre) . "'"
                        . ", apellidos = '" . escape($apellidos) . "'"
                        . ", email = '" . escape($email) . "'"
                        . ", fbid = '" . escape($userID) . "'"
                        . ", fbtoken = '" . escape($token) . "'"
                        //." fbexpiresin = ".escape($apellidos)
                        . ", verificado = " . ($verificado ? 1 : 0)
                        . " WHERE idusuario = " . escape($fila['idusuario']));
            } else {
                //Si no lo encuentra lo creamos con contraseña aleatoria


                $pass = getRandomString(16);


                $pass_sha = sha1($pass);


                $idusuario = insert_id("INSERT INTO `pdbdd`.`usuario` "
                        . "(`nombre`, `apellidos`, `email`, `pass`"
                        . ", `fbid`, `fbtoken`, `verificado`) "
                        . " VALUES ('" . escape($nombre) . "'"
                        . ",'" . escape($apellidos) . "'"
                        . ",'" . escape($email) . "'"
                        . ",'" . escape($pass_sha) . "'"
                        . ",'" . escape($userID) . "'"
                        . ",'" . escape($token) . "'"
                        //. ",'" . escape($expiresin) . "'"
                        . "," . ($verificado ? 1 : 0) . ""
                        . ")");


                //Rellenamos la sesión
                $_SESSION['pd_identificado'] = true;
                $_SESSION['idusuario'] = $idusuario;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellidos'] = $apellidos;
                $_SESSION['email'] = $email;
                //TODO verificación del email
                $_SESSION['verificado'] = $verificado ? 1 : 0;

                $respuesta = true;
            }
        }
    } else {
        throw new Exception("No se han podido validar los datos. 1.El token de cliente o de aplicación está caducado ó 2.no corresponde con sus identificadores de cliente y aplicación respectivamente ó 3.Facebook no responde correctamente.");
    }

    return $respuesta;
}

function getRandomString($length = 6) {
    $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?";
    $validCharNumber = strlen($validCharacters);

    $result = "";

    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }

    return $result;
}

function doLogout() {
    unset($_SESSION);
    session_destroy();

    $res = true;

    return $res;
}

function registrarUsuario($nombre, $apellidos, $email, $pass) {

//Comprobamos si ya existe la dirección de email
    $res = existeEmail($email);


    if (!$res) {

        $pass_sha = sha1($pass);

        $res = ejecutar("INSERT INTO `pdbdd`.`usuario` "
                . "(`nombre`, `apellidos`, `email`, `pass`) "
                . " VALUES ('" . escape($nombre) . "'"
                . ",'" . escape($apellidos) . "'"
                . ",'" . escape($email) . "'"
                . ",'" . escape($pass_sha) . "')");
    } else {
        throw new Exception("La dirección de email ya existe. "
        . "Confirma que es tu dirección. "
        . "Comprueba si ya tienes una cuenta abierta "
        . "o inténtalo de nuevo más tarde.");
    }


    return $res;
}

function existeEmail($email) {

    $res = ejecutar("SELECT COUNT(*) as existe FROM usuario WHERE email='" . escape($email) . "'");


//Recogemos el resultado
    $fila = $res->fetch_assoc();

    $existe = $fila['existe'];

    if ($existe > 0) {
        $res = true;
    } else {
        $res = false;
    }


    return $res;
}

function getSession() {
//Comprobamos que estemos logueados

    if (checkLogin()) {
//Estamos logueados
//Devolvemos los datos de la sesión
        $res = $_SESSION;
    } else {
//No estamos logueados
        throw new Exception("No hay sesión iniciada.");
    }

    return $res;
}

function getUsuarioActual() {

    if (checkLogin()) {

        $id_usuario = $_SESSION['idusuario'];

        $r = ejecutar("SELECT nombre, apellidos FROM usuario WHERE idusuario = " . escape($id_usuario));

        return toArray($r);
    } else {
        throw new Exception("No esta logueado.");
    }
}

function setUsuarioActual($datos) {
    nl();

    $id_usuario = $_SESSION['idusuario'];

    $r = ejecutar("UPDATE usuario SET "
            . " nombre = '" . escape($datos->nombre) . "'"
            . ", apellidos = '" . escape($datos->apellidos) . "'"
            . " WHERE idusuario = " . escape($id_usuario));

    return $r;
}

function getGrupos() {
//Comprobamos que estemos logueados
    if (checkLogin()) {

        $consulta = "SELECT * FROM grupo";

        $res = ejecutar($consulta);
        $res = toArray($res);
    }

    return $res;
}

function getGruposDeUsuarioActual() {
    if (checkLogin()) {
        $id_usuario = $_SESSION['idusuario'];

        $res = ejecutar("SELECT grupo.* FROM grupo, miembro"
                . " WHERE"
                . " miembro.usuario_idusuario = " . escape($id_usuario)
                . " AND"
                . " miembro.grupo_idgrupo = grupo.idgrupo");

        $res = toArray($res);

        return $res;
    }
}

function getDetalleDeGrupo($id_grupo) {
    if (checkLogin()) {

        $id_usuario = $_SESSION['idusuario'];

        $consulta = "SELECT grupo.*"
                . ", CASE WHEN miembro.usuario_idusuario IS NOT NULL THEN 1 ELSE 0 END as es_miembro "
                . " FROM grupo"
                . " LEFT JOIN miembro"
                . " ON miembro.grupo_idgrupo =" . escape($id_grupo)
                . " AND miembro.usuario_idusuario=" . escape($id_usuario)
                . " WHERE grupo.idgrupo = " . escape($id_grupo);

        $res = ejecutar($consulta);
        $res = $res->fetch_assoc();

//Determinamos si es un miembro nato
        $es_nato = esNato($id_usuario, $id_grupo);

        $res['es_nato'] = $es_nato;

        $nmiembros = getNTotalMiembrosDeGrupo($id_grupo);

        $res['nmiembros'] = $nmiembros;

        return $res;
    }
}

function esNato($id_usuario, $id_grupo) {
//Tenemos que contar todos los usuarios que forman parte bien del grupo directamente o de cualquier subgrupo
    $subgrupos = getSubgruposID($id_grupo, 0);

//Componemos la consulta y contamos sólo los miembros natos o no de los grupos componentes de éste
    $consulta = "SELECT COUNT(*) as es_nato"
            . " FROM usuario,miembro WHERE "
            . " miembro.usuario_idusuario = usuario.idusuario "
            . " AND usuario.idusuario = " . escape($id_usuario)
            . " AND ( 0 ";

    foreach ($subgrupos as $subgrupo) {

        $consulta.= " OR miembro.grupo_idgrupo = " . escape($subgrupo);
    }

    $consulta.=")";

    $res = ejecutar($consulta);

    $res = $res->fetch_assoc();

    return $res['es_nato'];
}

/**
 * 
 * @param type $id_usuario
 * @param type $id_grupo
 * @return type devuelve 1 si es miembro original o nato
 */
function esMiembro($id_usuario, $id_grupo) {
//Tenemos que contar todos los usuarios que forman parte bien del grupo directamente o de cualquier subgrupo
    $subgrupos = getSubgruposID($id_grupo, 0);

//Componemos la consulta y contamos sólo los miembros natos o no de los grupos componentes de éste
    $consulta = "SELECT COUNT(*) as es_miembro"
            . " FROM usuario,miembro WHERE "
            . " miembro.usuario_idusuario = usuario.idusuario "
            . " AND usuario.idusuario = " . escape($id_usuario)
            . " AND ("
            . " miembro.grupo_idgrupo = " . escape($id_grupo);

    foreach ($subgrupos as $subgrupo) {

        $consulta.= " OR miembro.grupo_idgrupo = " . escape($subgrupo);
    }

    $consulta.=")";

    $res = ejecutar($consulta);

    $res = $res->fetch_assoc();

    return $res['es_miembro'];
}

function solicitarIngresoEnGrupo($id_grupo) {
    if (checkLogin()) {

        $id_usuario = $_SESSION['idusuario'];

//Ingresar al usuario directamente en el grupo
        addMiembro($id_usuario, $id_grupo);
    }
}

function solicitarBaja($id_grupo) {
    if (checkLogin()) {
        $id_usuario = $_SESSION['idusuario'];

        delMiembro($id_usuario, $id_grupo);
    }
}

function getGrupoPorID($id_grupo) {
    if (checkLogin()) {
        $res = ejecutar("SELECT * FROM grupo WHERE idgrupo=" . escape($id_grupo));
        $res = $res->fetch_assoc();
        return $res;
    }
}

function addGrupo($nombre) {
    if (checkLogin()) {
        $res = insert_id("INSERT INTO `pdbdd`.`grupo` (`nombre`) VALUES ('" . escape($nombre) . "')");
        return $res;
    }
}

function addMiembro($id_usuario, $id_grupo) {
    if (checkLogin()) {

        iniciar_transaccion();

        try {

//Añadimos el miembro
            ejecutar("INSERT INTO `pdbdd`.`miembro` (`grupo_idgrupo`, `usuario_idusuario`, `puntos_participacion`, `voluntad`, `ultima_actualizacion`) "
                    . "VALUES (" . escape($id_grupo) . ", " . escape($id_usuario) . ", " . Constantes::puntos_iniciales . ", 2, now())");

            actualizarTotalMiembrosDeGrupo($id_grupo);

            validar_transaccion();
        } catch (Exception $e) {
//Si hay algún error cancelamos la transacción y seguimos propagándola
            cancelar_transaccion();
            throw $e;
        }
    }
}

function getSubGrupos($id_grupo, $nivel) {
//Comprobamos que esté logueado
    if (checkLogin()) {

//Obtenemos todos los grupos que componen el grupo indicado así como sus subgrupos hasta el nivel especificado
        if ($nivel == 1) {
//Si el nivel es uno la consulta es sencilla
            $res = ejecutar("SELECT grupo.* FROM grupo, subgrupo WHERE subgrupo.idgrupo = " . escape($id_grupo)
                    . " AND subgrupo.idsubgrupo = grupo.idgrupo");

            $res = toArray($res);
        } else {
//Sino pues ya tenemos que obtener toda la tabla e ir seleccionando
        }

        return $res;
    }
}

function addSubGrupo($id_supergrupo, $nombre) {
    if (checkLogin()) {
        $id_subgrupo = addGrupo($nombre);

//Ahora añadimos el grupo al supergrupo
        hacerSubGrupo($id_supergrupo, $id_subgrupo);
    }
}

function hacerSuperGrupo($id_subgrupo, $id_supergrupo) {
//Se añade directamente
    hacerSubGrupo($id_supergrupo, $id_subgrupo);
}

function addNuevoSuperGrupo($id_subgrupo, $nombre) {
//Se añade directamente
    $id_supergrupo = addGrupo($nombre);

    hacerSuperGrupo($id_subgrupo, $id_supergrupo);
}

function proponerSuperGrupo($id_subgrupo, $id_supergrupo) {
//Se crea una propuesta que se votará para determinar si se convierte en supergrupo o no
    if (checkLogin()) {
        
    }
}

function miembrode($id_grupo) {
    $res = false;
    if (checkLogin()) {
//Si estamos logueados comprobamos si el usuario actual es miembro del grupo indicado
        $id_usuario = $_SESSION['idusuario'];
        $res = esMiembro($id_usuario, $id_grupo);
    }
    return $res;
}

function crearVotacionDeGrupo($id_grupo, $enunciado) {

//Tenemos que generar una votación que será gestionada por el check-script
//Tenemos que determinar de algún modo las personas a las que va dirigida la votación, con el grupo
//Obtenemos el número total de personas del grupo
    $total_censo = getNTotalMiembrosDeGrupo($id_grupo);

//Calculamos el número de representantes que tocan
    $tmuestra = getTamanioMuestra($total_censo, 0.5);

//echo "<br>tmuestra = ".$tmuestra;
//Si salen más representantes que la mitad del censo cogemos la mitad más uno ya que el error que queremos es menor del 0.5
    /* if ($tmuestra > $total_censo / 2) {
      $tmuestra = floor($total_censo / 2) + 1;
      } */

//Obtenemos las ids de los representantes
    $representantes = getRepresentantesDeGrupo($tmuestra, $id_grupo);

    try {
        iniciar_transaccion();

//Toda votación necesita un censo así que es lógico que toda votación tenga que obligatoriamente tener asignado un grupo
        $consulta = "INSERT INTO `pdbdd`.`votacionsnd` SET "
                . " fecha_creacion = NOW()"
                . ", timein = NOW()"
                . ", checktime = NOW() + INTERVAL " . Constantes::checktime_minutos . " MINUTE"
                . ", activa = 1"
                . ", nrepresentantes = " . $tmuestra
                . ", censo = " . escape($id_grupo)
                . ", enunciado = '" . escape($enunciado) . "'";

        $id_votacion = insert_id($consulta);

//Añadimos los representantes a la votación
        $consulta = "INSERT INTO `pdbdd`.`votosnd` "
                . "(`usuario_idusuario`, `votacionsnd_idvotacionsnd`"
                . ", `representante`) VALUES ";

        $primero = true;

        foreach ($representantes as $representante) {

            if ($primero) {
                $primero = false;
            } else {
                $consulta.=",";
            }

            $consulta.="(" . $representante['usuario_idusuario'] . ""
                    . "," . $id_votacion . ""
                    . ",1)";
        }

        $res = ejecutar($consulta);

//Si hemos llegado hasta aquí es que todo ha ido bien
//Ya tenemos la votación creada con censo y representantes asignados
        validar_transaccion();

//Devolvemos el id de la nueva votación
        return $id_votacion;
    } catch (Exception $e) {
        cancelar_transaccion();
        throw $e;
    }

//Si se aprueba o se rechaza pos yasta, si queda "depende" se abre un espacio de debate sobre la pregunta, si queda desierta se penaliza al grupo, si queda denunciada se penaliza al promotor
}

function addPregunta($id_grupo, $enunciado) {
    if (miembrode($id_grupo)) {
//Creamos la votación
        crearVotacionDeGrupo($id_grupo, $enunciado);
    }
}

function getVotacionesSNDDeGrupoParaUsuarioActual($id_grupo) {

    if (checkLogin()) {

        $id_usuario = $_SESSION['idusuario'];

        $supergrupos = getSupergruposID($id_grupo, 0);

//Obtener las votaciones del grupo y sus supergrupos
        $consulta = "SELECT votacionsnd.*"
                . ", votosnd.valor as valor"
                . ", votosnd.representante as representante"
                . ", grupo.nombre as nombregrupo"
                . " FROM votacionsnd LEFT JOIN votosnd"
                . " ON votosnd.votacionsnd_idvotacionsnd = votacionsnd.idvotacionsnd"
                . " AND votosnd.usuario_idusuario = " . escape($id_usuario)
                . " LEFT JOIN grupo ON votacionsnd.censo = grupo.idgrupo"
                . " WHERE ";

        $consulta .= " votacionsnd.censo = " . escape($id_grupo);

        foreach ($supergrupos as $supergrupo) {
            $consulta .= " OR votacionsnd.censo = " . $supergrupo;
        }

        $res = ejecutar($consulta);

        $res = toArray($res);

        return $res;
    }
}

//Debe devolver todas las votaciones en las que el usuario es representante
function getVotacionesSNDPendientesDeUsuarioActualComoRepresentante() {

    if (checkLogin()) {

        $id_usuario = $_SESSION['idusuario'];

//Obtener las votaciones del grupo y sus supergrupos
        $consulta = "SELECT votacionsnd.*"
                . ", votosnd.valor as valor"
                . ", votosnd.representante as representante"
                . ", grupo.nombre as nombregrupo"
                . " FROM votacionsnd,votosnd,grupo"
                . " WHERE votosnd.votacionsnd_idvotacionsnd = votacionsnd.idvotacionsnd"
                . " AND votosnd.usuario_idusuario = " . escape($id_usuario)
                . " AND votosnd.representante = 1"
                . " AND votosnd.valor IS NULL"
                . " AND votacionsnd.censo = grupo.idgrupo";

        $res = ejecutar($consulta);

        $res = toArray($res);

        return $res;
    }
}

function getVotacionesSNDDeGrupo($id_grupo) {

    $supergrupos = getSupergruposID($id_grupo, 0);

//Obtener las votaciones del grupo y sus supergrupos
    $consulta = "SELECT votacionsnd.* FROM votacionsnd WHERE ";

    $consulta .= " votacionsnd.censo = " . escape($id_grupo);

    foreach ($supergrupos as $supergrupo) {
        $consulta .= " OR votacionsnd.censo = " . $supergrupo;
    }

    $res = ejecutar($consulta);

    $res = toArray($res);

    return $res;
}

function hacerSubGrupo($id_supergrupo, $id_subgrupo) {
    ejecutar("INSERT INTO `pdbdd`.`subgrupo` (`idgrupo`, `idsubgrupo`) VALUES (" . escape($id_supergrupo) . "," . escape($id_subgrupo) . ")");
}

function getSuperGrupos($id_grupo, $nivel) {
//Comprobamos que esté logueado
    if (checkLogin()) {

//Obtenemos todos los grupos de los que forma parte el grupo especificado hasta un nivel concreto
        if ($nivel == 1) {
//Si el nivel es uno la consulta es sencilla
            $res = ejecutar("SELECT grupo.* FROM grupo, subgrupo WHERE subgrupo.idsubgrupo = " . escape($id_grupo)
                    . " AND subgrupo.idgrupo = grupo.idgrupo");

            $res = toArray($res);
        } else {
//Sino pues ya tenemos que obtener toda la tabla e ir seleccionando
        }

        return $res;
    }
}

//Función no permitida desde fuera
function delMiembro($id_usuario, $id_grupo) {
//Borramos el usuario y reducimos el número de miembros del grupo

    iniciar_transaccion();
    try {
//Borramos al miembro
        ejecutar("DELETE FROM miembro WHERE miembro.usuario_idusuario=" . escape($id_usuario) . " AND miembro.grupo_idgrupo=" . escape($id_grupo));

        actualizarTotalMiembrosDeGrupo($id_grupo);

        validar_transaccion();
    } catch (Exception $e) {
        cancelar_transaccion();
        throw $e;
    }
}

function actualizarMiembrosDeGrupo($id_grupo) {
//Actualizar el número de miembros del grupo
    ejecutar("UPDATE grupo SET nmiembros = "
            . "(SELECT COUNT(*) FROM miembro WHERE miembro.grupo_idgrupo=" . escape($id_grupo) . ")"
            . " WHERE grupo.idgrupo=" . escape($id_grupo));
}

function actualizarTotalMiembrosDeGrupo($id_grupo) {
//Tenemos que contar todos los usuarios que forman parte bien del grupo directamente o de cualquier subgrupo
    $subgrupos = getSubgruposID($id_grupo, 0);

//Componemos la consulta que actualice directamente el valor del número de miembros
    $consulta = "UPDATE grupo SET "
            . " nmiembros = (SELECT COUNT(DISTINCT(usuario.idusuario)) FROM usuario, miembro WHERE "
            . " miembro.usuario_idusuario = usuario.idusuario"
            . " AND (";

//Contamos también los miembros del propio grupo
    $consulta.=" miembro.grupo_idgrupo = " . escape($id_grupo);

    foreach ($subgrupos as $subgrupo) {
        $consulta.= " OR miembro.grupo_idgrupo = " . escape($subgrupo);
    }

    $consulta.="))"
            . " WHERE grupo.idgrupo = " . escape($id_grupo);

//echo $consulta;

    ejecutar($consulta);
}

function getTotalMiembrosDeGrupo($id_grupo) {
//Tenemos que contar todos los usuarios que forman parte bien del grupo directamente o de cualquier subgrupo
    $subgrupos = getSubgruposID($id_grupo, 0);

//Componemos la consulta
    $consulta = "SELECT usuario.idusuario FROM usuario,miembro WHERE "
            . " miembro.usuario_idusuario = usuario.idusuario AND (";

//Contamos también los miembros del propio grupo
    $consulta.=" miembro.grupo_idgrupo = " . escape($id_grupo);

    foreach ($subgrupos as $subgrupo) {

        $consulta.= " OR miembro.grupo_idgrupo = " . escape($subgrupo);
    }

    $consulta.=") GROUP BY usuario.idusuario";

    $res = ejecutar($consulta);

    $res = toArray($res);

    return $res;
}

//Devuelve los miembros del grupo que pueden votar, activos y con voluntad de participar y puntos
function getNTotalMiembrosDeGrupo($id_grupo) {
//Tenemos que contar todos los usuarios que forman parte bien del grupo directamente o de cualquier subgrupo
    $subgrupos = getSubgruposID($id_grupo, 0);

//Componemos la consulta que actualice directamente el valor del número de miembros
    $consulta = "SELECT COUNT(DISTINCT(usuario.idusuario)) as total FROM usuario, miembro WHERE "
            . " miembro.usuario_idusuario = usuario.idusuario"
            . " AND miembro.voluntad = 2"
            . " AND miembro.puntos_participacion >= 0"
            . " AND (";

//Contamos también los miembros del propio grupo
    $consulta.=" miembro.grupo_idgrupo = " . escape($id_grupo);

    foreach ($subgrupos as $subgrupo) {
        $consulta.= " OR miembro.grupo_idgrupo = " . escape($subgrupo);
    }

    $consulta.=" )";

    $res = ejecutar($consulta);

    $fila = $res->fetch_assoc();

    $res = $fila['total'];

    return $res;
}

function getMiembrosNatos($id_grupo) {
//Tenemos que contar todos los usuarios que forman parte bien del grupo directamente o de cualquier subgrupo
    $subgrupos = getSubgruposID($id_grupo, 0);

//Componemos la consulta y contamos sólo los miembros natos o no de los grupos componentes de éste
    $consulta = "SELECT usuario.idusuario FROM usuario,miembro WHERE "
            . " miembro.usuario_idusuario = usuario.idusuario AND (";

    $primero = true;
    foreach ($subgrupos as $subgrupo) {

        if ($primero) {
            $primero = false;
        } else {
            $consulta.= " OR ";
        }

        $consulta.= " miembro.grupo_idgrupo = " . escape($subgrupo);
    }

    $consulta.=") GROUP BY usuario.idusuario";

    $res = ejecutar($consulta);

    $res = toArray($res);

    return $res;
}

function getSubgruposID($id_grupo, $nivel) {
//Obtenemos todos los grupos que componen el grupo indicado así como sus subgrupos hasta el nivel especificado
    $res = array();
    if ($nivel == 1) {
//Si el nivel es uno la consulta es sencilla
        $res = ejecutar("SELECT grupo.idgrupo FROM grupo, subgrupo WHERE subgrupo.idgrupo = " . escape($id_grupo)
                . " AND subgrupo.idsubgrupo = grupo.idgrupo");

        $res = toArray($res);
    } else {
//Sino pues ya tenemos que obtener toda la tabla e ir seleccionando
//Obtenemos toda la tabla de subgrupos
        $mapaGrupos = getMapaGrupos();

        if ($nivel == 0) {
//Obtenemos todos los hijos
//Vamos recorriendo y añadiendo los hijos a la respuesta
//echo "Comenzamos recorrido del árbol";

            $respuesta = array();

            $subnivel = array();
            $subnivel[$id_grupo] = $id_grupo;
            $nextnivel = array();

//Mientras hay hijos
            while (count($subnivel) > 0) {
//Recorrer el mapa y anotar los hijos
//echo "<br>Tenemos grupos que observar";
//var_dump($subnivel);
//echo "<br>";
                foreach ($mapaGrupos as $relacion) {
//Si el grupo es padre anotamos al hijo
                    if (isset($subnivel[$relacion['idgrupo']])) {
//echo "<br>El grupo " . $relacion['idgrupo'] . " se encuentra entre los que observar";
                        $id_hijo = $relacion['idsubgrupo'];
//echo "<br>Su subgrupo es " . $id_hijo;
//Si no estaba ya en la respuesta lo añadimos (evitamos ciclos)
                        if (!isset($respuesta[$id_hijo])) {
//echo "<br>$id_hijo no estaba en la respuesta así que lo añadimos";
                            $nextnivel[$id_hijo] = $id_hijo;
                            $respuesta[$id_hijo] = $id_hijo;
//echo "La respuesta queda así:";
//var_dump($respuesta);
                        }
                    }
                }
//Ahora actualizamos el subnivel
                $subnivel = $nextnivel;
                $nextnivel = array();
            }

//Aquí ya hemos recorrido todos los hijos hasta que ya están todos en el vector respuesta
            $res = $respuesta;
        }
    }
//echo "Los subgrupos de $id_grupo son :";
//var_dump($res);
    return $res;
}

function getSupergruposID($id_grupo, $nivel) {
//Obtenemos todos los grupos de los que foma parte el grupo indicado así como sus supergrupos hasta el nivel especificado
    if ($nivel == 1) {
//Si el nivel es uno la consulta es sencilla
        $res = ejecutar("SELECT grupo.idgrupo FROM grupo, subgrupo WHERE subgrupo.idsubgrupo = " . escape($id_grupo)
                . " AND subgrupo.idgrupo = grupo.idgrupo");

        $res = toArray($res);
    } else {
//Sino pues ya tenemos que obtener toda la tabla e ir seleccionando
//Obtenemos toda la tabla de subgrupos
        $mapaGrupos = getMapaGrupos();

        if ($nivel == 0) {
//Obtenemos todos los hijos
//Vamos recorriendo y añadiendo los hijos a la respuesta

            $respuesta = array();

            $supernivel = array();
            $supernivel[$id_grupo] = $id_grupo;
            $nextnivel = array();

//Mientras hay superiores
            while (count($supernivel) > 0) {
//Recorrer el mapa y anotar los padres
                foreach ($mapaGrupos as $relacion) {
//Si el grupo es hijo anotamos al padre
                    if (isset($supernivel[$relacion['idsubgrupo']])) {
                        $id_padre = $relacion['idgrupo'];
//Si no estaba ya en la respuesta lo añadimos (evitamos ciclos)
                        if (!isset($respuesta[$id_padre])) {
                            $nextnivel[$id_padre] = $id_padre;
                            $respuesta[$id_padre] = $id_padre;
                        }
                    }
                }
//Ahora actualizamos el subnivel
                $supernivel = $nextnivel;
                $nextnivel = array();
            }

//Aquí ya hemos recorrido todos los hijos hasta que ya están todos en el vector respuesta
            $res = $respuesta;
        }
    }

    return $res;
}

function getSupergruposIDConRuta($id_grupo, $nivel) {
//Obtenemos todos los grupos de los que foma parte el grupo indicado así como sus supergrupos hasta el nivel especificado
    if ($nivel == 1) {
//Si el nivel es uno la consulta es sencilla
        $res = ejecutar("SELECT grupo.idgrupo FROM grupo, subgrupo WHERE subgrupo.idsubgrupo = " . escape($id_grupo)
                . " AND subgrupo.idgrupo = grupo.idgrupo");

        $res = toArray($res);
    } else {
//Sino pues ya tenemos que obtener toda la tabla e ir seleccionando
//Obtenemos toda la tabla de subgrupos
        $mapaGrupos = getMapaGruposConNombre();

        if ($nivel == 0) {
//Obtenemos todos los hijos
//Vamos recorriendo y añadiendo los hijos a la respuesta

            $respuesta = array();

            $supernivel = array();
            $supernivel[$id_grupo] = $id_grupo;
            $nextnivel = array();

//Mientras hay superiores
            while (count($supernivel) > 0) {
//Recorrer el mapa y anotar los padres
                foreach ($mapaGrupos as $relacion) {
//Si el grupo es hijo anotamos al padre
                    if (isset($supernivel[$relacion['idsubgrupo']])) {
                        $id_padre = $relacion['idgrupo'];
                        $nombre_padre = $relacion['nombregrupo'];
//Si no estaba ya en la respuesta lo añadimos (evitamos ciclos)
                        if (!isset($respuesta[$id_padre])) {
                            $nextnivel[$id_padre] = $id_padre;
                            $respuesta[$id_padre] = array($id_padre, $nombre_padre);
                        }
                    }
                }
//Ahora actualizamos el subnivel
                $supernivel = $nextnivel;
                $nextnivel = array();
            }

//Aquí ya hemos recorrido todos los hijos hasta que ya están todos en el vector respuesta
            $res = $respuesta;
        }
    }

    return $res;
}

function getMapaGrupos() {
    $consulta = "SELECT * FROM subgrupo";
    $res = ejecutar($consulta);
    $res = toArray($res);
    return $res;
}

function getMapaGruposConNombre() {
    $consulta = "SELECT subgrupo.*"
            . ", g_grupo.nombre as nombregrupo"
            . ", g_subgrupo.nombre as nombresubgrupo"
            . " FROM subgrupo, grupo as g_grupo, grupo as g_subgrupo"
            . " WHERE subgrupo.idgrupo = g_grupo.idgrupo"
            . " AND subgrupo.idsubgrupo = g_subgrupo.idgrupo";
    $res = ejecutar($consulta);
    $res = toArray($res);
    return $res;
}

function getObjetivosConInfo() {
//Comprobamos que estemos logueados
    $res = checkLogin();

    if (!$res->hayerror) {

        if ($res->resultado) {
//Estamos logueados

            $id_usuario = $_SESSION['idusuario'];

            $res = ejecutar("SELECT objetivo.*"
                    . ", votosnd.valor as voto"
                    . ", votosnd.representante as representante"
                    . ", votacionsnd.checktime as checktime"
                    . ", estado_objetivo.nombre as nombre_estado"
                    . ", estado_objetivo.proceso as nombre_proceso"
                    . " FROM objetivo "
                    . " LEFT JOIN objetivo_has_votacionsnd ON objetivo_has_votacionsnd.objetivo_idobjetivo = objetivo.idobjetivo"
                    . " AND objetivo_has_votacionsnd.nombre = 'Aprobación'"
                    . " LEFT JOIN votacionsnd ON objetivo_has_votacionsnd.votacionsnd_idvotacionsnd = votacionsnd.idvotacionsnd"
                    . " LEFT JOIN votosnd ON votosnd.votacionsnd_idvotacionsnd = votacionsnd.idvotacionsnd"
                    . " AND votosnd.usuario_idusuario =" . escape($id_usuario)
                    . ", estado_objetivo"
                    . " WHERE objetivo.estado_objetivo_idestado_objetivo = estado_objetivo.idestado_objetivo"
                    . " ORDER BY checktime ASC, representante DESC");

            if (!$res->hayerror) {
                $res->resultado = toArray($res->resultado);

//Convertimos los números
                $array = $res->resultado;

                $l = count($array);

                for ($i = 0; $i < $l; $i++) {
                    $array[$i]['progreso_actual'] = (float) $array[$i]['progreso_actual'];
                    $array[$i]['progreso_maximo'] = (float) $array[$i]['progreso_maximo'];

                    if ($array[$i]['voto'] != null) {
                        $array[$i]['voto'] = (int) $array[$i]['voto'];
                    }

                    $array[$i]['representante'] = (int) $array[$i]['representante'];
                }

                $res->resultado = $array;
            }
        } else {
//No estamos logueados
        }
    }

    return $res;
}

function getObjetivos() {
//Comprobamos que estemos logueados
    $res = checkLogin();

    if (!$res->hayerror) {

        if ($res->resultado) {
//Estamos logueados

            $res = ejecutar("SELECT * FROM objetivo");

            if (!$res->hayerror) {
                $res->resultado = toArray($res->resultado);

//Convertimos los números
                $array = $res->resultado;

                $l = count($array);

                for ($i = 0; $i < $l; $i++) {
                    $array[$i]['progreso_actual'] = (float) $array[$i]['progreso_actual'];
                    $array[$i]['progreso_maximo'] = (float) $array[$i]['progreso_maximo'];
                }

                $res->resultado = $array;
            }
        } else {
//No estamos logueados
        }
    }

    return $res;
}

function addObjetivo($descripcion) {
//Comprobamos que estemos logueados
    $res = checkLogin();

    if (!$res->hayerror) {

        if ($res->resultado) {
//Estamos logueados
//Obtenemos el total de la población actual
            $total = getTotalIndividuos()->resultado;

//Determinamos el número de representantes necesarios en función de la población total y el error máximo
//La primera vez nos basta un error inferior al 0.5
            $nmuestra = getTamanioMuestra($total, 0.5);

//Ahora calculamos el error en el que incurrimos al utilizar la muestra
            $error = getErrorDeMuestra($total, $nmuestra);

//Seleccionamos a los representantes antes de comenzar la transacción
            $representantes = getRepresentantes($nmuestra)->resultado;

//Iniciamos la transacción
            iniciar_transaccion();

//Añadimos el objetivo
            $res = insert_id("INSERT INTO `pdbdd`.`objetivo` (`descripcion`) "
                    . "VALUES ('" . escape($descripcion) . "')");


            if (!$res->hayerror) {

                $id_objetivo = $res->resultado;

//Añadimos la votación 
                $res = insert_id("INSERT INTO `pdbdd`.`votacionsnd` "
                        . "(`error`, `timein`, `checktime`, `timeout`, `ampliaciones`, `activa`, `finalizada`, `resultado`) "
                        . "VALUES "
                        . "(" . $error . "" //El error que tiene de momento
                        . ", NOW()" //Tiempo de creación
                        . ", NOW() + INTERVAL " . Constantes::checktime_minutos . " MINUTE" //El tiempo de checkeo son 2 días, 48 horas
                        . ", NOW() + INTERVAL 10 MINUTE" //El tiempo máximo de vida de la votación son 8 días
                        . ", 0" //Comienza con 0 ampliaciones
                        . ", 1" //La creamos como activa
                        . ", 0" //No finalizada
                        . ", NULL" //No tiene resultado asignado aún
                        . ")");

                if (!$res->hayerror) {

//Obtenemos el id de la votación
                    $id_votacion = $res->resultado;

//Asociamos la votación con el objetivo
                    $res = ejecutar("INSERT INTO `pdbdd`.`objetivo_has_votacionsnd` "
                            . "(`objetivo_idobjetivo`, `votacionsnd_idvotacionsnd`, `nombre`) "
                            . "VALUES "
                            . "(" . $id_objetivo . ""
                            . "," . $id_votacion . ""
                            . ", 'Aprobación'"
                            . ")");

                    if (!$res->hayerror) {


//Añadimos los representantes a la votación
//Seleccionamos y añadimos a los representantes
                        $consulta = "INSERT INTO `pdbdd`.`votosnd` "
                                . "(`usuario_idusuario`, `votacionsnd_idvotacionsnd`"
                                . ", `representante`) VALUES ";

                        $primero = true;
                        foreach ($representantes as $representante) {

                            if ($primero) {
                                $primero = false;
                            } else {
                                $consulta.=",";
                            }

                            $consulta.="(" . $representante['idusuario'] . ""
                                    . "," . $id_votacion . ""
                                    . ",1)";
                        }

                        $res = ejecutar($consulta);

                        if (!$res->hayerror) {

//Anotamos notificaciones para los representantes
//TODO no es necesario, se pueden consultar luego
                        }
                    }
                }
            }

            if (!$res->hayerror) {
//Si todo ha ido bien comitamos
                commit();
            } else {
//Sino rollback
                rollback();
            }
        } else {
//No estamos logueados
        }
    }

    return $res;
}

function addNRepresentantesAVotacion($n, $id_votacion) {
    $representantes = getRepresentantes($n)->resultado;
    return addRepresentantesAVotacion($representantes, $id_votacion);
}

function addRepresentantesAVotacion($representantes, $id_votacion) {
//Añadimos los representantes a la votación
//Seleccionamos y añadimos a los representantes
    $consulta = "INSERT INTO `pdbdd`.`votosnd` "
            . "(`usuario_idusuario`, `votacionsnd_idvotacionsnd`"
            . ", `representante`) VALUES ";

    $primero = true;
    foreach ($representantes as $representante) {

        if ($primero) {
            $primero = false;
        } else {
            $consulta.=",";
        }

        $consulta.="(" . $representante['idusuario'] . ""
                . "," . $id_votacion . ""
                . ",1)";
    }

//Si ya existían simplemente los marcamos como representantes
    $consulta.= " ON DUPLICATE KEY UPDATE "
            . " representante=1";

    $res = ejecutar($consulta);

    return $res;
}

function getTotalIndividuos() {
//Comprobamos que estemos logueados
    $res = checkLogin();

    if (!$res->hayerror) {

        if ($res->resultado) {
//Estamos logueados

            $res = ejecutar("SELECT COUNT(*) as total FROM usuario");

            if (!$res->hayerror) {

//Obtenemos la fila
                $fila = $res->resultado->fetch_assoc();

                $res->resultado = $fila['total'];
            }
        } else {
//No estamos logueados
        }
    }

    return $res;
}

function getErrorDeMuestra($total, $muestra) {

//Si la muestra es mayor o igual que el total el error es 0
    if ($muestra >= $total) {
        $result = 0;
    } else if ($muestra < 1) {
//Si la muestra es menor que 1 entonces el error es máximo: 1
        $result = 1;
    } else {

        $z = Constantes::z;
        $a = $z * $z * 0.25;
        $n = $muestra;
        $N = $total;

        $t1 = sqrt((-$a) * ($n - $N));
        $t2 = sqrt($n * $N);
        $result = abs($t1 / $t2);
    }
    return $result;
}

function getErrorDeMuestra2($total, $muestra) {

//Si la muestra es mayor o igual que el total el error es 0
    if ($muestra >= $total) {
        $result = 0;
    } else {

        $z = Constantes::z;

        $t1 = ($total - $muestra) / ($muestra * ($total - 1));
        $result = ($z / 2) * sqrt($t1);
    }
    return $result;
}

function calculaTamanioMuestra2($total, $error) {
    $z = Constantes::z;

    $r = ($z * $z * 0.25) / ($error * $error);

    $muestra = $r / (1 + (($r - 1) / $total));

    return $muestra;
}

function calculaTamanioMuestra($total, $error) {
    $z = Constantes::z;

    $a = $z * $z * 0.25;

    $n = $total;

    $r = ($a * $n) / (($n * $error * $error) + $a);

    return $r;
}

function getTamanioMuestra($total, $error) {
    $muestra = calculaTamanioMuestra($total, $error);

//TODO esto es peligroso, es la corrección de la función según el error por el que preguntamos
    if ($error == 0.5) {
//Si la muestra es mayor que la mitad entonces la muestra es la mitad mas uno
        if ($muestra > $total / 2) {
            $nmuestra = floor($total / 2) + 1;
        } else {
            $nmuestra = floor($muestra) + 1;
        }
    } else {
        $nmuestra = floor($muestra) + 1;
    }

    return $nmuestra;
}

function getTamanioMuestraPro($total, $error) {
    $z = Constantes::z;
    $a = ($z * $z * 0.25);
    $c = $error;
    $c2 = $c * $c;
    $c4 = $c2 * $c2;
    $t = $total;

    $t1 = 4 * $a * $a - 4 * $a * $c2 + $c4 * $t * $t - 2 * $c4 * $t + $c4 + 2 * $a + $c2 * $t - $c2;

    $nmuestra = sqrt($t1) / (2 * $c2);

    return $nmuestra;
}

function getRepresentantes($nmuestra) {
    $res = ejecutar("SELECT usuario.idusuario FROM usuario ORDER BY RAND() LIMIT " . $nmuestra);

    if (!$res->hayerror) {

        $array = toArray($res->resultado);

        $res->resultado = $array;
    }

    return $res;
}

function getRepresentantesDeGrupo($nmuestra, $id_grupo) {

//Seleccionamos representantes de este o de cualquier subgrupo
    $subgrupos = getSubgruposID($id_grupo, 0);

    $consulta = "SELECT miembro.usuario_idusuario FROM miembro"
            . " WHERE miembro.puntos_participacion >= 0"
            . " AND miembro.voluntad = 2"
            . " AND (miembro.grupo_idgrupo = " . escape($id_grupo);

    foreach ($subgrupos as $subgrupo) {
        $consulta.= " OR miembro.grupo_idgrupo = " . $subgrupo;
    }

    $consulta.= ") GROUP BY miembro.usuario_idusuario";

    $consulta .= " ORDER BY RAND()";


    $consulta.= " LIMIT " . escape($nmuestra);



//echo "<br>consulta = " . $consulta;

    $res = ejecutar($consulta);

    $res = toArray($res);

    return $res;
}

function getCensoDeVotacion($id_votacion) {
//Obtenemos el censo de la votación
    $res = ejecutar("SELECT votacionsnd.censo FROM votacionsnd WHERE votacionsnd.idvotacionsnd = " . escape($id_votacion));
    $fila = $res->fetch_assoc();
    return $fila['censo'];
}

/**
 * Obtiene tantos representantes como nmuestra indique para la votación id_votacion
 * @param type $nmuestra
 * @param type $id_votacion
 * @return type
 */
function getNuevosRepresentantesDeVotacion($nmuestra, $id_votacion) {

    $id_grupo = getCensoDeVotacion($id_votacion);

//Seleccionamos representantes de este o de cualquier subgrupo
    $subgrupos = getSubgruposID($id_grupo, 0);

    $consulta = "SELECT miembro.usuario_idusuario as idusuario FROM miembro"
            . " LEFT JOIN votosnd ON votosnd.usuario_idusuario = miembro.usuario_idusuario"
            . " AND votosnd.votacionsnd_idvotacionsnd = " . escape($id_votacion)
            . " WHERE miembro.grupo_idgrupo = " . escape($id_grupo);

    foreach ($subgrupos as $subgrupo) {
        $consulta.= " OR miembro.grupo_idgrupo = " . $subgrupo;
    }

//Siempre que no sean ya representantes
    $consulta.= " AND (votosnd.representante IS NULL OR votosnd.representante = 0) "
            . " AND miembro.puntos_participacion >= 0" //tengan puntos
            . " AND miembro.voluntad = 2"; //y quieran participar

    $consulta .= " ORDER BY RAND() LIMIT " . escape($nmuestra);

    $res = ejecutar($consulta);

    $res = toArray($res);

    return $res;
}

function votarAprobacionObjetivo($id_objetivo, $valor) {

//Obtenemos la votación correspondiente
    $res = getVotacionAprobacionDeObjetivo($id_objetivo);

    if (!$res->hayerror) {

        $id_votacion = $res->resultado;

        $res = emitirVoto($id_votacion, $valor);
    }

    return $res;
}

function getVotacionAprobacionDeObjetivo($id_objetivo) {

    $res = ejecutar("SELECT * FROM objetivo_has_votacionsnd WHERE"
            . " objetivo_idobjetivo='" . escape($id_objetivo) . "'"
            . " AND nombre='Aprobación'");

    if (!$res->hayerror) {
        $resultado = $res->resultado;

        if ($resultado->num_rows == 1) {

            $fila = $resultado->fetch_assoc();

            $res->resultado = $fila['votacionsnd_idvotacionsnd'];
        } else {
            $res->hayerror = true;
            $res->errormsg = "Hay más de una votación de aprobación en el objetivo";
        }
    }

    return $res;
}

function emitirVoto($id_votacion, $valor) {
//Comprobamos que estemos logueados
    if (checkLogin()) {

//Obtenemos el censo de la votación
        $id_censo = getCensoDeVotacion($id_votacion);

//Comprobamos que el usuario sea miembro del censo        
        if (miembrode($id_censo)) {

//Estamos logueados y pertenecemos al censo de la votación
//Utilizamos el id de usuario del usuario en cuestión
            $id_usuario = $_SESSION['idusuario'];

//Insertamos el voto y si existe lo actualizamos
            $res = ejecutar("INSERT INTO `pdbdd`.`votosnd` "
                    . "(`usuario_idusuario`, `votacionsnd_idvotacionsnd`, `valor`)"
                    . " VALUES"
                    . "('" . escape($id_usuario) . "'"
                    . ",'" . escape($id_votacion) . "'"
                    . "," . escape($valor)
                    . ") ON DUPLICATE KEY UPDATE"
                    . " valor=" . escape($valor));

            return $res;
        } else {
            throw new Exception("No puedes participar en esta votación.");
        }
    } else {
        throw new Exception("Debes estar logueado.");
    }
}

/**
 * Crear una decisión nueva en el grupo con id $id_grupo y enunciado $enunciado
 * @param type $id_grupo
 * @param type $enunciado
 */
function crearDecision($id_grupo, $enunciado) {
    if (miembrode($id_grupo)) {
//Creamos la votación
        $id_votacion = crearVotacionDeGrupo($id_grupo, $enunciado);

//Creamos la decisión asociada a la votación
        $consulta = "INSERT INTO `pdbdd`.`decisionsnd` "
                . "(`enunciado`, `votacionsnd_idvotacionsnd`, `grupo_idgrupo`) "
                . "VALUES ('" . escape($enunciado) . "', " . escape($id_votacion) . ", " . escape($id_grupo) . ")";

        ejecutar($consulta);
    }
}

function checkTime() {

    sumarPuntos();

    checkVotaciones();

    checkDecisiones();

//checkObjetivos();
}

function getEnunciados() {
    $consulta = "SELECT * FROM enunciadosnd";

    $res = ejecutar($consulta);

    $enunciados = toArray($res);

    return $enunciados;
}

function checkDecisiones() {
//Repasamos los enunciados para ver qué ha sucedido con sus respectivas votaciones y llevar a cabo las acciones necesarias
//Obtenemos los enunciados cuyo resultado es null porque son los únicos que pueden cambiar
//junto con los resultados de sus votaciones relacionadas que no sean null, es decir que hayan cambiado
    $consulta = "SELECT decisionsnd.*"
            . ", votacionsnd.resultado as resultado_votacion"
            . " FROM decisionsnd"
            . " LEFT JOIN votacionsnd ON decisionsnd.votacionsnd_idvotacionsnd = votacionsnd.idvotacionsnd"
            . " WHERE decisionsnd.resultado IS NULL"
            . " AND votacionsnd.resultado IS NOT NULL";

    $res = ejecutar($consulta);
    $decisiones = toArray($res);

    foreach ($decisiones as $decision) {
//Comprobamos los resultados de las votaciones relacionadas para ver si tenemos que actualizar el resultado de la decisión

        $resultado = $decision['resultado_votacion'];

        $id_decision = $decision['iddecisionsnd'];

        if ($resultado == 1) {
//Si es rechazada no se hace nada, se deja en el histórico de votaciones rechazadas (si a caso)
        } else if ($resultado == 2) {

//Si sale "Depende" se crea un nuevo supergrupo con el nombre: "Discusión sobre" o "Debate:" + enunciado de la votación.    
            $enunciado = $decision['enunciado'];
            $nombre_grupo = "Debate: " . $enunciado;

            $id_supergrupo = addGrupo($nombre_grupo);

            $id_subgrupo = $decision['grupo_idgrupo'];

            hacerSuperGrupo($id_subgrupo, $id_supergrupo);
        } else if ($resultado == 3) {

//Si es aprobada se crea un nuevo supergrupo con el nombre de la votación

            $enunciado = $decision['enunciado'];
            $nombre_grupo = $enunciado;

            $id_supergrupo = addGrupo($nombre_grupo);

            $id_subgrupo = $decision['grupo_idgrupo'];

            hacerSuperGrupo($id_subgrupo, $id_supergrupo);

//Si el enunciado está aprobada y tiene una ejecución asociada se ejecuta
            if (isset($decision['ejecucionsys_idejecucionsys'])) {
                $id_ejecucion = $decision['ejecucionsys_idejecucionsys'];

//Mandamos ejecutar la ejecución
                ejecutarEjecucion($id_ejecucion);
            }

//Luego comprobamos si la aprobación de este enunciado afecta a los enunciados superiores
//¿Cuándo un "Depende" se convierte en un "Sí" o un "No"?
        }

//En cualquier caso actualizamos el valor del resultado de la decisión
        ejecutar("UPDATE decisionsnd SET resultado=" . escape($resultado) . " WHERE iddecisionsnd = " . escape($id_decision));
    }
}

function checkVotaciones() {

    castigarMalosRepresentantes();


//TODO Desactivamos todas las votaciones 
//Comprobar votaciones
//Obtenemos las votaciones que deben ser controladas con toda la información necesaria
    $res = ejecutar("SELECT votacionsnd.*"
            . ", SUM(CASE WHEN votosnd.representante = 1 THEN 1 ELSE 0 END) as representantes"
            . ", SUM(CASE WHEN votosnd.representante = 1 AND votosnd.valor = 1 THEN 1 ELSE 0 END) as votos_negativos_rep"
            . ", SUM(CASE WHEN votosnd.representante = 1 AND votosnd.valor = 2 THEN 1 ELSE 0 END) as votos_depende_rep"
            . ", SUM(CASE WHEN votosnd.representante = 1 AND votosnd.valor = 3 THEN 1 ELSE 0 END) as votos_positivos_rep"
            . ", SUM(CASE WHEN votosnd.valor IS NOT NULL THEN 1 ELSE 0 END) as votos_emitidos"
            . ", SUM(CASE WHEN votosnd.representante = 0 AND votosnd.valor = 1 THEN 1 ELSE 0 END) as votos_negativos_ind"
            . ", SUM(CASE WHEN votosnd.representante = 0 AND votosnd.valor = 2 THEN 1 ELSE 0 END) as votos_depende_ind"
            . ", SUM(CASE WHEN votosnd.representante = 0 AND votosnd.valor = 3 THEN 1 ELSE 0 END) as votos_positivos_ind"
            . " FROM votacionsnd "
            . " LEFT JOIN votosnd ON votosnd.votacionsnd_idvotacionsnd = votacionsnd.idvotacionsnd"
            . " WHERE checktime <= NOW()"
            . " AND finalizada=0"
            . " GROUP BY idvotacionsnd");

    $votaciones = toArray($res);

//Recorremos las votaciones que ya deben ser controladas

    foreach ($votaciones as $votacion) {

        $id_votacion = $votacion['idvotacionsnd'];

//Obtenemos el grupo que representa el censo
        $id_grupo = $votacion['censo'];

//Obtenemos el total de individuos que pueden votar
        $nindividuos = getNTotalMiembrosDeGrupo($id_grupo);

        echo "<hr>";
        echo "<br>Votación: $id_votacion - " . $votacion['enunciado'];

//Si el número de individuos es cero anulamos la votación
        if ($nindividuos > 0) {


//Recogemos datos
            $nrepresentantes = $votacion['representantes'];
            $votos_emitidos = $votacion['votos_emitidos'];
            $vsi_ind = $votacion['votos_positivos_ind'];
            $vno_ind = $votacion['votos_negativos_ind'];
            $vdep_ind = $votacion['votos_depende_ind'];
            $vsi_rep = $votacion['votos_positivos_rep'];
            $vno_rep = $votacion['votos_negativos_rep'];
            $vdep_rep = $votacion['votos_depende_rep'];
            $id_votacion = $votacion['idvotacionsnd'];

//Sumamos a los votos individuales los votos de representantes 
//ya que los representantes también tienen voto individual
            $vsi_ind += $vsi_rep;
            $vno_ind += $vno_rep;
            $vdep_ind += $vdep_rep;


            echo "<br>$nindividuos individuos llamados a votar";
            echo "<br>Para esta votación se han nombrado $nrepresentantes representantes";
            echo "<br>$vsi_ind personas han votado 'Sí' (incluidos representantes), el " . (($vsi_ind / $nindividuos) * 100) . "% de los individuos";
            echo "<br>$vno_ind personas han votado 'No' (incluidos representantes), el " . (($vno_ind / $nindividuos) * 100) . "% de los individuos";
            echo "<br>$vdep_ind personas han votado 'Depende' (incluidos representantes), el " . (($vdep_ind / $nindividuos) * 100) . "% de los individuos";
            $sirep = $nrepresentantes == 0 ? 0 : ($vsi_rep / $nrepresentantes) * 100;
            $norep = $nrepresentantes == 0 ? 0 : ($vno_rep / $nrepresentantes) * 100;
            $deprep = $nrepresentantes == 0 ? 0 : ($vdep_rep / $nrepresentantes) * 100;
            echo "<br>$vsi_rep representantes han votado 'Sí', el " . $sirep . "% de los representantes";
            echo "<br>$vno_rep representantes han votado 'No', el " . $norep . "% de los representantes";
            echo "<br>$vdep_rep representantes han votado 'Depende', el " . $deprep . "% de los representantes";

            $total_ind = $vsi_ind + $vno_ind + $vdep_ind;
            $total_rep = $vsi_rep + $vno_rep + $vdep_rep;

            echo "<br>En total se han emitido $total_ind votos";
            echo " de los cuales $total_rep son de representantes";

//Calculamos la abstención
            $abstencion = $nindividuos - $votos_emitidos;
            $abstencion_rep = $nrepresentantes - $total_rep;

            echo "<br>Se han abstenido $abstencion individuos";

//Calculamos el error_actual en función de la abstención
//ya que los votos de los representantes actúan sobre una población menor que la total
//si ha votado alguien por sí mismo el error disminuye
//TODO esto último no es cierto. Los representantes escogidos en un principio representan al total de la población
//si ahora pasaran a representar sólo la abstención estarían contando más los que han votado
//es decir que votar directamente sólo sirve si se supera el 50%, sino simplemente se estaría confirmando la estimación
//Si hay representantes y abstención mayor que una persona lo podemos calcular, sino no
            /*
              if ($nrepresentantes > 0 && $abstencion > 1) {
              $error_actual = getErrorDeMuestra($abstencion, $nrepresentantes);
              echo "<br>El error que calculamos que pueden cometer $nrepresentantes representantes votando por $abstencion individuos es " . ($error_actual * 100) . "%";
              } else {
              $error_actual = 0;
              echo "<br>No tenemos representantes o ha votado todo el mundo así que el error es $error_actual";
              } */
//Así que el error actual no ha variado pues es el que se calculó la última vez
//salvo que no contemos con los representantes que se han abstenido (que no deberíamos)
//así que volvemos a calcular el error en función de la participación de los representantes
//el error será igual o mayor de lo que inicialmente se calculó.
            $representantes_activos = $nrepresentantes - $abstencion_rep;
            $error_actual = getErrorDeMuestra($nindividuos, $representantes_activos);

            echo "<br>El error que podemos cometer con $representantes_activos representantes activos es del " . ($error_actual * 100) . "%";

            /*
              //Los representantes representan a la abstención si los hay
              if ($nrepresentantes > 0) {
              $representacion = $abstencion / $nrepresentantes;
              echo "<br>Tenemos $nrepresentantes representantes que representan a $abstencion individuos con una cuota de representación de $representacion cada uno";
              } else {
              $representacion = 0;
              echo "<br>No tenemos representantes así que la cuota de representacion es $representacion";
              }

              //Sumamos los votos de cada opción
              //que es lo que vale un representante por los votos de representantes que haya reciobido la opción
              //más los votos individuales
              $vsi_representados = $representacion * $vsi_rep;
              $vno_representados = $representacion * $vno_rep;
              $vdep_representados = $representacion * $vdep_rep;

              echo "<br>Los representantes representan $vsi_representados votos para el 'Sí', $vno_representados votos para el 'No' y $vdep_representados votos para el 'Depende'";

              //Calculamos los votos reales y representados por separado
              //no se divide entre la abstención por que la representatividad de cada voto ya ha sido calculada en función de la abstención
              $porcentaje_vsi_representados = $vsi_representados / $nindividuos;
              $porcentaje_vno_representados = $vno_representados / $nindividuos;
              $porcentaje_vdep_representados = $vdep_representados / $nindividuos;

              echo "<br>Los votos de los representantes suponen:";
              echo "<br>Un " . ($porcentaje_vsi_representados * 100) . "% del 'Sí'";
              echo "<br>Un " . ($porcentaje_vno_representados * 100) . "% del 'No'";
              echo "<br>Un " . ($porcentaje_vdep_representados * 100) . "% del 'Depende'";


              $porcentaje_vsi_independientes = $vsi_ind / $nindividuos;
              $porcentaje_vno_independientes = $vno_ind / $nindividuos;
              $porcentaje_vdep_independientes = $vdep_ind / $nindividuos;

              echo "<br>Los votos individuales suponen:";
              echo "<br>Un " . ($porcentaje_vsi_independientes * 100) . "% del 'Sí'";
              echo "<br>Un " . ($porcentaje_vno_independientes * 100) . "% del 'No'";
              echo "<br>Un " . ($porcentaje_vdep_independientes * 100) . "% del 'Depende'";

              $sumasi = $vsi_representados + $vsi_ind;
              $sumano = $vno_representados + $vno_ind;
              $sumadep = $vdep_representados + $vdep_ind;

              //Calculamos los porcentajes de cada opción sumando los anteriores
              $psi = $sumasi / $nindividuos;
              $pno = $sumano / $nindividuos;
              $pdep = $sumadep / $nindividuos;

              echo "<br>Si sumamos los votos individuales y representados tenemos:";
              echo "<br>$sumasi votos para el 'Sí', el " . ($psi * 100) . "%";
              echo "<br>$sumano votos para el 'No', el " . ($pno * 100) . "%";
              echo "<br>$sumadep votos para el 'Depende', el " . ($pdep * 100) . "%";
              echo "<br>Que juntos suman " . ($sumasi + $sumano + $sumadep) . ", el " . (($psi + $pno + $pdep) * 100) . "%";
             */
//Y ahora ya podemos calcular el apoyo a las diferentes opciones y los errores
//Para cada una comprobamos si ha terminado o la ampliamos
//TODO a ver qué hacemos si se abstiene todo cristo
            $resultado = 0;



//Calculamos ahora el porcentaje de votos que se prevén para cada opción
            $porcentaje_si_rep = $total_rep > 0 ? $vsi_rep / $total_rep : 0;
            $porcentaje_no_rep = $total_rep > 0 ? $vno_rep / $total_rep : 0;
            $porcentaje_dep_rep = $total_rep > 0 ? $vdep_rep / $total_rep : 0;

            echo "<br>Según los votos de los representantes se prevé:";
            echo "<br>Un " . ($porcentaje_si_rep * 100) . "% del 'Sí'";
            echo "<br>Un " . ($porcentaje_no_rep * 100) . "% del 'No'";
            echo "<br>Un " . ($porcentaje_dep_rep * 100) . "% del 'Depende'";


//Calculamos el porcentaje de votos individuales
            //El voto de los representantes también cuenta como individual
            $porcentaje_si_ind = $vsi_ind / $nindividuos;
            $porcentaje_no_ind = $vno_ind / $nindividuos;
            $porcentaje_dep_ind = $vdep_ind / $nindividuos;

            echo "<br>Los votos individuales suponen:";
            echo "<br>Un " . ($porcentaje_si_ind * 100) . "% del 'Sí'";
            echo "<br>Un " . ($porcentaje_no_ind * 100) . "% del 'No'";
            echo "<br>Un " . ($porcentaje_dep_ind * 100) . "% del 'Depende'";

//Calculamos los mínimos de representación
            $minimo_rep_si = ($porcentaje_si_rep - $error_actual);
            $minimo_rep_no = ($porcentaje_no_rep - $error_actual);
            $minimo_rep_dep = ($porcentaje_dep_rep - $error_actual);

//El mínimo es el máximo entre lo real y la previsión mínima
            $minimo_si = max($minimo_rep_si, $porcentaje_si_ind);
            $minimo_no = max($minimo_rep_no, $porcentaje_no_ind);
            $minimo_dep = max($minimo_rep_dep, $porcentaje_dep_ind);

//El máximo es siempre el de la previsión
            $maximo_si = $porcentaje_si_rep + $error_actual;
            $maximo_no = $porcentaje_no_rep + $error_actual;
            $maximo_dep = $porcentaje_dep_rep + $error_actual;

//TODO si el número de votos reales es mayor que el máximo previsto entonces ha fallado la representación
//Truncamos los máximos para que sean más reales
            $invariable = $minimo_si + $minimo_no + $minimo_dep;
            $variable = 1 - $invariable;

            if ($maximo_no > $minimo_no + $variable) {
                $maximo_no = $minimo_no + $variable;
            }

            if ($maximo_si > $minimo_si + $variable) {
                $maximo_si = $minimo_si + $variable;
            }

            echo "<br>Según los datos obtenidos las predicciones son las siguientes:";
            echo "<br>'Sí' obtendrá entre un " . ($minimo_si * 100) . "% y un " . ($maximo_si * 100) . "% del total de votos.";
            echo "<br>'No' obtendrá entre un " . ($minimo_no * 100) . "% y un " . ($maximo_no * 100) . "% del total de votos.";
//echo "<br>'Depende' obtendrá entre un ".$minimo_si."% y un ".$maximo_si."% del total de votos.";

            if ($minimo_si > 0.5) {
//Si el porcentaje de sí menos el error aún es mayor que la mitad entonces es la opción seleccionada
                $resultado = 3;
                echo "<br>Como el mínimo del 'Sí' es mayor que la mitad podemos asegurar que éste será el resultado mayoritario.";
            } else if ($minimo_no > 0.5) {
                $resultado = 1;
                echo "<br>Como el mínimo del 'No' es mayor que la mitad podemos asegurar que éste será el resultado mayoritario.";
            } else if ($maximo_si <= 0.5 && $maximo_no <= 0.5) {
//Si ni "sí" ni "no" tienen opciones ya de alcanzar mayoría es un "depende"
                $resultado = 2;
                echo "<br>Ya que ninguna de las opciones extremas puede ya conseguir la mayoría 'Depende' es la opción seleccionada.";
            }

            if ($resultado == 0) {

                echo "<br>Aún no se han descartado suficientes opciones.";

                if ($abstencion_rep > 0) {

//Aquí sabemos que hay representantes que se han abstenido
                    echo "<br>$abstencion_rep representantes se han abstenido, no se puede continuar hasta que se pronuncien";

//Aquellos representantes que se abstengan perderán X puntos lo que posiblemenre les lleve a ser expulsados del grupo por lo que habrá que obtener otros representantes
//Identificamos a los representantes que se han abstenido en esta votación
                    $consulta = "SELECT usuario_idusuario FROM votosnd WHERE"
                            . " votacionsnd_idvotacionsnd = $id_votacion" //en esta votación
                            . " AND representante = 1" //representantes
                            . " AND valor IS NULL"; //que no hayan votado

                    $malos_representantes = toArray(ejecutar($consulta));

                    echo "<br> Los malos representantes son: $malos_representantes";

//Les restamos los puntos pertinentes
                    //        modificarPuntos($id_grupo, $malos_representantes, 'usuario_idusuario', -100);
//TODO y qué pasa con los miembros natos? no se cuentan? qué puntos se le restan?
//Se restan puntos del grupo en el que se hace la pregunta y de todos los subgrupos
//Necesitamos saber cuántos miembros quedan y cuántos representantes
//Actualizamos nindividuos
                    $nindividuos = getNTotalMiembrosDeGrupo($id_grupo);

//Limpiar votos: eliminar aquellos votos de usuarios que no tienen puntos
                }

//Si seguimos sin tener una respuesta tenemos que ampliar la muestra
//Calculamos las diferencias en el error necesarias para cambiar de escenario
//Nos interesa que el error sea tan pequeño que
//haga que los resultados más/menos el error no traspasen
//el 0.5, es decir, se queden en su lado, ya sea más allá o sin llegar a pasarlo
//así que calcularemos las diferencias entre los porcentajes y el 0.5
//estos son los errores deseados para las opciones y escogeremos de ellos
//el mayor por ser más fácil de conseguir (molestamos a menos personas)

                $dsi = abs($minimo_si - 0.5);
                $dno = abs($minimo_no - 0.5);

                echo "<br>Para decidirnos tendríamos que reducir el error hasta el " . ($dsi * 100) . "% o el " . ($dno * 100) . "%";

//Si alguno de los errores es mayor que el actual se descarta y se coge el otro
                if ($dsi > $error_actual && $dno <= $error_actual) {
                    $error_deseado = $dno;
                } else if ($dno > $error_actual && $dsi <= $error_actual) {
                    $error_deseado = $dsi;
                } else if ($dsi <= $error_actual && $dno <= $error_actual) {
//Si los dos son menores cogemos el mayor de los dos, el que está más cerca
                    $error_deseado = max($dsi, $dno);
                } else {
//Si ninguno de los dos vale lo que hacemos es ignorar la ampliación ya que no podemos mejorar el error
//lo que hace falta es que los representantes voten
                }

                echo "<br>El más cercano es " . ($error_deseado * 100) . "%.";


//Calculamos el tamaño de la muestra en función de la abstención
//porque los que ya han votado no se pueden abstener 
//sin embargo también están siendo representados
//$muestra_necesaria = getTamanioMuestra($abstencion, $error_deseado);
                $muestra_necesaria = getTamanioMuestra($nindividuos, $error_deseado);
                echo "<br>Para poder representar a $nindividuos individuos "
                . " con un error inferior al " . ($error_deseado * 100) . "% necesitamos una muestra de $muestra_necesaria individuos.";

//No contamos con que los representantes que se han abstenido vayan a votar
//pero les seguimos dando la opción por si votan y así ayudan a disminuir el error más aún
//Calculamos la diferencia entre la muestra que tenemos 
//(los representantes que se han pronunciado) y la necesaria
//aunque los representantes que se han abstenido siguen representando a mucha gente
//así que este cálculo no servirá salvo que los que se han abstenido voten o 
//se les revoque la condición de representantes...
                /*
                 * Opción 1: Añadir más representantes y esperar a que todos voten para el siguiente checktime
                 * Opción 2: Revocar la condición de representantes a los que se han abstenido y añadir los necesarios
                 * Opción 3: Añadir representantes sin contar con los abstenidos pero no revocar la condición
                 * Opción 4: Revocar la condición de representantes a todos y volver a nombrar representantes con la nueva muestra
                 */
//De momento opción 2
                $muestra_real_actual = $nrepresentantes - $abstencion_rep;

//TODO penalizar a los representantes que no se han pronunciado
//TODO comprobar cuántos representantes quedan en el grupo después de la penalización
//TODO y entonces nombrar a los representantes necesarios

                echo "<br>Ahora mismo tenemos una muestra de $muestra_real_actual representantes.";

//Suponiendo que descartáramos a los representantes actuales
                $ampliacion_muestra = $muestra_necesaria - $muestra_real_actual;

                echo "<br>Añadimos $ampliacion_muestra representantes más.";

//Ampliamos la muestra

                if ($ampliacion_muestra > 0) {
                    $representantes = getNuevosRepresentantesDeVotacion($ampliacion_muestra, $id_votacion);
                    $res = addRepresentantesAVotacion($representantes, $id_votacion);
                }

//Contamos una ampliación más y definimos el nuevo checktime
                $res = ejecutar("UPDATE votacionsnd SET"
                        . " ampliaciones=ampliaciones+1"
                        . ", timein=NOW()"
                        . ", checktime=NOW() + INTERVAL " . Constantes::checktime_minutos . " MINUTE"
                        . ", activa=1"
                        . " WHERE idvotacionsnd=" . $id_votacion);
            } else {
//Si tenemos resultado, lo guardamos con los datos de votación y se finaliza la votación

                $res = ejecutar("UPDATE votacionsnd SET"
                        . " resultado=" . $resultado
                        . ", fecha_finalizacion=NOW()"
                        . ", finalizada=1"
                        . ", activa=0"
                        . ", error=" . $error_actual
                        . ", votossi=" . $vsi_ind
                        . ", votosno=" . $vno_ind
                        . ", votosdep=" . $vdep_ind
                        . ", nrepresentantes=" . $nrepresentantes
                        . ", votossirep=" . $vsi_rep
                        . ", votosnorep=" . $vno_rep
                        . ", votosdeprep=" . $vdep_rep
                        . ", minimosi=" . $minimo_si
                        . ", minimono=" . $minimo_no
                        . ", minimodep=" . $minimo_dep
                        . ", nindividuos=" . $nindividuos
                        . " WHERE idvotacionsnd=" . $id_votacion);

//TODO podemos sumar aquí los puntos que sabremos los representantes y votantes que han habido al final
//o irlo haciendo conforme se va votando/añadiendo/quitando representantes

                if ($res) {
//Identificamos a los representantes que han votado
                    $consulta = "SELECT usuario_idusuario FROM votosnd"
                            . " WHERE votacionsnd_idvotacionsnd = $id_votacion" //en esta votación
                            . " AND representante = 1" //representantes
                            . " AND valor IS NOT NULL"; //que hayan votado

                    $buenos_representantes = toArray(ejecutar($consulta));

//Sumamos los puntos a los buenos representantes
                    modificarPuntos($id_grupo, $buenos_representantes, 'usuario_idusuario', Constantes::puntos_por_pregunta_importante);


//Identificamos a los votantes normales
                    $consulta = "SELECT usuario_idusuario FROM votosnd"
                            . " WHERE votacionsnd_idvotacionsnd = $id_votacion" //en esta votación
                            . " AND representante = 0" //representantes
                            . " AND valor IS NOT NULL"; //que hayan votado

                    $votantes = toArray(ejecutar($consulta));
//Les sumamos los puntos
                    modificarPuntos($id_grupo, $votantes, 'usuario_idusuario', Constantes::puntos_por_pregunta_normal);
                    /*
                      //Castigamos a los malos representantes
                      $consulta = "SELECT usuario_idusuario FROM votosnd"
                      . " WHERE votacionsnd_idvotacionsnd = $id_votacion" //en esta votación
                      . " AND representante = 1" //representantes
                      . " AND valor IS NULL"; //que no hayan votado

                      $malos_representantes = toArray(ejecutar($consulta));

                      //Restamos los puntos a los malos representantes
                      modificarPuntos($id_grupo, $malos_representantes, 'usuario_idusuario', Constantes::puntos_por_mal_representante);
                     */
                }
            }
        } else {
//No hay votantes, anulamos la votación

            echo "No hay votantes";

            $res = ejecutar("UPDATE votacionsnd SET"
                    . " resultado=4"
                    . ", finalizada=1"
                    . ", activa=0"
                    . " WHERE idvotacionsnd=" . $id_votacion);
        }
    }

    return $res;
}

function checkObjetivos() {

//Comprobamos los objetivos para ver si cambian de estado o qué pasa
//Aquellos objetivos en el estado 1 con sus votaciones de "Aprobación" finalizadas y con resultado 2 pasan a estar rechazados (estado 2)
//Seleccionamos los objetivos con estado 1 (aprobación) y votación finalizada
    $res = ejecutar("SELECT objetivo.*"
            . ", votacionsnd.resultado as resultado"
            . " FROM objetivo "
            . " STRAIGHT_JOIN objetivo_has_votacionsnd ON objetivo_has_votacionsnd.objetivo_idobjetivo=objetivo.idobjetivo"
            . " AND objetivo_has_votacionsnd.nombre='Aprobación'"
            . " STRAIGHT_JOIN votacionsnd ON objetivo_has_votacionsnd.votacionsnd_idvotacionsnd = votacionsnd.idvotacionsnd"
            . " AND votacionsnd.finalizada=1"
            . " WHERE estado_objetivo_idestado_objetivo = 1");

    if (!$res->hayerror) {

        $objetivos_finalizados = toArray($res->resultado);

        foreach ($objetivos_finalizados as $objetivo) {

//En función de su resultado hacemos una u otra cosa

            $resultado_aprobacion = $objetivo['resultado'];

            $id_objetivo = $objetivo['idobjetivo'];

            if ($resultado_aprobacion == 1) {
//Si ha sido rechazada
//Pasa al estado 2 (rechazada)

                $res = ejecutar("UPDATE objetivo SET"
                        . " estado_objetivo_idestado_objetivo=2"
                        . " WHERE idobjetivo=" . escape($id_objetivo));
            } else if ($resultado_aprobacion == 3) {
//Si ha sido aprobada
//Pasa al estado 5 (asignación)

                $res = ejecutar("UPDATE objetivo SET"
                        . " estado_objetivo_idestado_objetivo=5"
                        . " WHERE idobjetivo=" . escape($id_objetivo));
            } else if ($resultado_aprobacion == 2) {
//Si ha ganado el "Depende"
//Pasa al estado 4 (definición)

                $res = ejecutar("UPDATE objetivo SET"
                        . " estado_objetivo_idestado_objetivo=4"
                        . " WHERE idobjetivo=" . escape($id_objetivo));
            }
        }
    }
}

function factorial($n) {
    if ($n == 1 || $n == 0) {
        $r = 1;
    } else {
        $r = 2;
        for ($i = 3; $i <= $n; $i++) {
            $r *= $i;
        }
    }
    return $r;
}

function combinaciones($n, $k) {

    $ninicial = 1;
    $otro = 1;

    $nk = $n - $k;
    if ($k > ($nk)) {
//Si k > (n-k) dividmos n por n-k
        $ninicial = $k;
        $otro = factorial($nk);
    } else {
        $ninicial = $nk;
        $otro = factorial($k);
    }

    $t1 = 1;
    for ($i = $ninicial + 1; $i <= $n; $i++) {
        $t1 *= $i;
    }
    return $t1 / $otro;
}

function getEnunciadosDeGrupo($id_grupo) {
    if (checkLogin()) {
        $consulta = "SELECT * FROM enunciadosnd";
    }
}

function votarDepende($idvotacion, $enunciado) {
//Votar depende en la votación y asociar a la votación el enunciado lógico que se le pasa (hay que traducirlo)
}

//Modifica los puntos de los miembros que se le pasan para el grupo indicado y de todos sus subgrupos
function modificarPuntos($id_grupo, $v_miembros, $nombre_campo, $puntos) {

    $subgrupos = getSubGruposID($id_grupo, 0);

    if (count($v_miembros) > 0) {
        $consulta = "UPDATE miembro SET puntos_participacion = puntos_participacion ";
        if ($puntos >= 0) {
            $consulta.= " + " . escape($puntos);
        } else {
            $consulta.= " - " . escape(abs($puntos));
        }
        $consulta.= " WHERE ( grupo_idgrupo = " . escape($id_grupo);
        foreach ($subgrupos as $subgrupo) {
            $consulta.= " OR grupo_idgrupo = " . escape($subgrupo);
        }
        $consulta.= ") AND ( 0";
        foreach ($v_miembros as $id_miembro) {
            $consulta.= " OR usuario_idusuario = " . escape($id_miembro[$nombre_campo]);
        }
        $consulta.= ")";

        return ejecutar($consulta);
    } else {
        return true;
    }
}

//Devuelve el número de miembros activos del grupo y sus subgrupos
function nMiembrosActivos($id_grupo) {

    $subgrupos = getSubgruposID($id_grupo, 0);


    $consulta = "SELECT COUNT(*) as nmiembros FROM miembros WHERE "
            . "( grupo_idgrupo = " . escape($id_grupo);

    foreach ($subgrupos as $subgrupo) {
        $consulta.= " OR grupo_idgrupo = " . escape($subgrupo);
    }

    $consulta.= ") AND voluntad = 2"
            . " AND puntos_participacion >= 0";

    $array_num = toArray(ejecutar($consulta));

    return $array_num['nmiembros'];
}

function sumarPuntos() {
    $consulta = "UPDATE miembro SET"
            . " puntos_participacion = puntos_participacion + " . Constantes::delta_puntos_tiempo
            . ", ultima_actualizacion = now()"
            . " WHERE ultima_actualizacion <= DATE_SUB(now(), INTERVAL " . Constantes::minutos_actualizacion_puntos . " MINUTE)"
            . " AND puntos_participacion <=0"; //Sólo sumamos puntos a los que no tienen

    return ejecutar($consulta);
}

function castigarMalosRepresentantes() {

//Identificar votaciones terminadas
    $consulta = "SELECT idvotacionsnd, censo FROM votacionsnd WHERE checktime <= NOW()";

    $terminadas = toArray(ejecutar($consulta));

    //Para cada votación terminada comprobamos qué representantes se han abstenido
    foreach ($terminadas as $votacion) {
        $id_votacion = $votacion['idvotacionsnd'];
        $id_grupo = $votacion['censo'];

        //Identificamos a los representantes de las votaciones terminadas que no han votado

        $consulta = "SELECT usuario_idusuario FROM votosnd"
                . " WHERE votacionsnd_idvotacionsnd = $id_votacion" //en esta votación
                . " AND representante = 1" //representantes
                . " AND valor IS NULL"; //que no hayan votado

        $malos_representantes = toArray(ejecutar($consulta));

        //Restamos los puntos a los malos representantes
        modificarPuntos($id_grupo, $malos_representantes, 'usuario_idusuario', Constantes::puntos_por_mal_representante);


        //Limpiamos los votos de los representantes abstenidos
        $consulta = "DELETE FROM votosnd"
                . " WHERE votacionsnd_idvotacionsnd = $id_votacion" //en esta votación
                . " AND representante = 1" //representantes
                . " AND valor IS NULL"; //que no hayan votado

        ejecutar($consulta);
    }

    //TODO Eliminamos los votos de los miembros no activos
}

/**
 * Funciones de chat
 */

/**
 * Devuelve todos los mensajes nuevos del chat del grupo posteriores a la ultima actualización
 * @param type $id_grupo
 * @param type $ultima_actualizacion
 */
function getChatGrupoNuevo($id_grupo, $ultima_actualizacion) {
    //Comprobamos que sea miembro del grupo
    if (miembrode($id_grupo)) {
        $res = ejecutar("SELECT idchatgrupo, mensaje, usuario_idusuario, fecha"
                . ", usuario.nombre as nombre_usuario"
                . " FROM chatgrupo"
                . " LEFT JOIN usuario ON chatgrupo.usuario_idusuario = usuario.idusuario"
                . " WHERE"
                . " grupo_idgrupo = " . escape($id_grupo)
                . " AND fecha > TIMESTAMP('" . escape($ultima_actualizacion)."')");

        return toArray($res);
    }
}

function getChatGrupoNuevoID($id_grupo, $id_ultimo_mensaje_visto){
        //Comprobamos que sea miembro del grupo
    if (miembrode($id_grupo)) {
        $res = ejecutar("SELECT idchatgrupo, mensaje, usuario_idusuario, fecha"
                . ", usuario.nombre as nombre_usuario"
                . " FROM chatgrupo"
                . " LEFT JOIN usuario ON chatgrupo.usuario_idusuario = usuario.idusuario"
                . " WHERE"
                . " grupo_idgrupo = " . escape($id_grupo)
                . " AND idchatgrupo > ".  escape($id_ultimo_mensaje_visto));

        return toArray($res);
    }
}

function nuevoMensajeChatGrupo($id_grupo, $mensaje) {
    //Comprobamos que el usuario actual esté logado y sea miembro del grupo en cuestión
    if (miembrode($id_grupo)) {
        $id_usuario = $_SESSION['idusuario'];
        ejecutar("INSERT INTO `pdbdd`.`chatgrupo` (`grupo_idgrupo`, `usuario_idusuario`, `mensaje`)"
                . " VALUES (" . escape($id_grupo) . "," . escape($id_usuario) . ", '" . escape($mensaje) . "')");
    }
}
