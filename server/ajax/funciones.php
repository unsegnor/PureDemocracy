<?php

include_once dirname(__FILE__) . "./../core/clases.php";
include_once dirname(__FILE__) . "./conexionbdd.php";

function checkLogin() {
    $res = new Res();

    //Comprobamos en la sesión si el usuario actual está identificado
    if (isset($_SESSION['pd_identificado']) && $_SESSION['pd_identificado']) {
        $res->resultado = true;
    } else {
        $res->resultado = false;
    }

    return $res;
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

    if (!$res->hayerror) {

        //Si receibimos algún resultado lo logueamos
        if ($res->resultado->num_rows > 0) {
            $fila = $res->resultado->fetch_assoc();

            $_SESSION['pd_identificado'] = true;
            $_SESSION['idusuario'] = $fila['idusuario'];
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['apellidos'] = $fila['apellidos'];
            $_SESSION['email'] = $fila['email'];
            $_SESSION['verificado'] = $fila['verificado'];

            $res->resultado = true;
        } else {
            $res->hayerror = true;
            $res->errormsg = "No existe una cuenta con ese correo y contraseña.";
        }
    }

    return $res;
}

function doLogout() {
    unset($_SESSION);
    session_destroy();

    $res = new Res();
    $res->resultado = true;

    return $res;
}

function registrarUsuario($nombre, $apellidos, $email, $pass) {

    //Comprobamos si ya existe la dirección de email
    $res = existeEmail($email);

    if (!$res->hayerror) {

        if (!$res->resultado) {

            $pass_sha = sha1($pass);

            $res = ejecutar("INSERT INTO `pdbdd`.`usuario` "
                    . "(`nombre`, `apellidos`, `email`, `pass`) "
                    . " VALUES ('" . escape($nombre) . "'"
                    . ",'" . escape($apellidos) . "'"
                    . ",'" . escape($email) . "'"
                    . ",'" . escape($pass_sha) . "')");
        } else {
            $res->hayerror = true;
            $res->errormsg = "La dirección de email ya existe. "
                    . "Confirma que es tu dirección. "
                    . "Comprueba si ya tienes una cuenta abierta "
                    . "o inténtalo de nuevo más tarde.";
        }
    }

    return $res;
}

function existeEmail($email) {

    $res = ejecutar("SELECT COUNT(*) as existe FROM usuario WHERE email='" . escape($email) . "'");

    if (!$res->hayerror) {
        //Recogemos el resultado
        $fila = $res->resultado->fetch_assoc();

        $existe = $fila['existe'];

        if ($existe > 0) {
            $res->resultado = true;
        } else {
            $res->resultado = false;
        }
    }

    return $res;
}

function getSession() {
    //Comprobamos que estemos logueados
    $res = checkLogin();

    if (!$res->hayerror) {

        if ($res->resultado) {
            //Estamos logueados
            //Devolvemos los datos de la sesión
            $res->resultado = $_SESSION;
        } else {
            //No estamos logueados
            $res->hayerror = true;
            $res->errormsg = "No hay sesión iniciada.";
        }
    }

    return $res;
}

function getUsuarioActual() {

    //Comprobamos que estemos logueados
    $res = checkLogin();

    if (!$res->hayerror) {

        if ($res->resultado) {
            //Estamos logueados
            //Cargamos los datos de nuestro usuario
            $id_usuario_actual = $_SESSION['idusuario'];

            //$res = ejecutar("SELECT usuario.nombre");
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

            //Redondeamos para abajo y sumamos uno al tamaño para asegurarnos de que el error está por debajo del 0.5
            $nmuestra = floor($nmuestra) + 1;

            //Ahora calculamos el error en el que incurrimos al utilizar la muestra
            $error = getErrorDeMuestra($total, $nmuestra);

            //Iniciamos la transacción
            iniciar_transaccion();

            //Añadimos el objetivo
            $res = insert_id("INSERT INTO `pdbdd`.`objetivo` (`descripcion`) "
                    . "VALUES ('" . escape($descripcion) . "')");


            if (!$res->hayerror) {

                $id_objetivo = $res->resultado;

                //Añadimos la votación 
                $res = insert_id("INSERT INTO `pdbdd`.`votacionsinodep` "
                        . "(`error`, `timein`, `checktime`, `timeout`, `ampliaciones`, `activa`, `finalizada`, `resultado`) "
                        . "VALUES "
                        . "(" . $error . "" //El error que tiene de momento
                        . ", NOW()" //Tiempo de creación
                        . ", NOW() + INTERVAL 2 DAY" //El tiempo de checkeo son 2 días, 48 horas
                        . ", NOW() + INTERVAL 8 DAY" //El tiempo máximo de vida de la votación son 8 días
                        . ", 0" //Comienza con 0 ampliaciones
                        . ", 1" //La creamos como activa
                        . ", 0" //No finalizada
                        . ", NULL" //No tiene resultado asignado aún
                        . ")");

                if (!$res->hayerror) {

                    //Obtenemos el id de la votación
                    $id_votacion = $res->resultado;

                    //Asociamos la votación con el objetivo
                    $res = ejecutar("INSERT INTO `pdbdd`.`objetivo_has_votacionsinodep` "
                            . "(`objetivo_idobjetivo`, `votacionsinodep_idvotacionsinodep`, `nombre`) "
                            . "VALUES "
                            . "(" . $id_objetivo . ""
                            . "," . $id_votacion . ""
                            . ", 'Aprobación'"
                            . ")");

                    if (!$res->hayerror) {

                        //Seleccionamos y añadimos a los representantes
                        $res = ejecutar("INSERT INTO `pdbdd`.`votosinodep` "
                                . "(`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`"
                                . ", `representante`)"
                                . " SELECT usuario.idusuario"
                                . "," . $id_votacion . "" //Añadimos el id de la votación
                                . ", 1" //Son representantes, por eso comienza en 1 (True)
                                . " FROM usuario ORDER BY RAND() LIMIT " . $nmuestra);

                        if (!$res->hayerror) {

                            //Anotamos notificaciones para los representantes
                            
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
    $z = 3.7;

    $t1 = ($total - $muestra) / ($muestra * ($total - 1));
    $result = ($z / 2) * sqrt($t1);

    return $result;
}

function getTamanioMuestra($total, $error) {
    //$z = 2.58; //Para el 99%
    $z = 3.7; //Para el 99.99%

    $r = ($z * $z * 0.25) / ($error * $error);

    $muestra = $r / (1 + (($r - 1) / $total));

    return $muestra;
}
