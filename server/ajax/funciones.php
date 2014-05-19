<?php

include_once dirname(__FILE__) . "./constantes.php";
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

function getObjetivosConInfo() {
    //Comprobamos que estemos logueados
    $res = checkLogin();

    if (!$res->hayerror) {

        if ($res->resultado) {
            //Estamos logueados

            $id_usuario = $_SESSION['idusuario'];

            $res = ejecutar("SELECT objetivo.*"
                    . ", votosinodep.valor as voto"
                    . ", votosinodep.representante as representante"
                    . ", votacionsinodep.checktime as checktime"
                    . ", estado_objetivo.nombre as nombre_estado"
                    . ", estado_objetivo.proceso as nombre_proceso"
                    . " FROM objetivo "
                    . " LEFT JOIN objetivo_has_votacionsinodep ON objetivo_has_votacionsinodep.objetivo_idobjetivo = objetivo.idobjetivo"
                    . " AND objetivo_has_votacionsinodep.nombre = 'Aprobación'"
                    . " LEFT JOIN votacionsinodep ON objetivo_has_votacionsinodep.votacionsinodep_idvotacionsinodep = votacionsinodep.idvotacionsinodep"
                    . " LEFT JOIN votosinodep ON votosinodep.votacionsinodep_idvotacionsinodep = votacionsinodep.idvotacionsinodep"
                    . " AND votosinodep.usuario_idusuario =" . escape($id_usuario)
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

            //Redondeamos para abajo y sumamos uno al tamaño para asegurarnos de que el error está por debajo del 0.5
            $nmuestra = floor($nmuestra) + 1;

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
                $res = insert_id("INSERT INTO `pdbdd`.`votacionsinodep` "
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
                    $res = ejecutar("INSERT INTO `pdbdd`.`objetivo_has_votacionsinodep` "
                            . "(`objetivo_idobjetivo`, `votacionsinodep_idvotacionsinodep`, `nombre`) "
                            . "VALUES "
                            . "(" . $id_objetivo . ""
                            . "," . $id_votacion . ""
                            . ", 'Aprobación'"
                            . ")");

                    if (!$res->hayerror) {


                        //Añadimos los representantes a la votación
                        //Seleccionamos y añadimos a los representantes
                        $consulta = "INSERT INTO `pdbdd`.`votosinodep` "
                                . "(`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`"
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
    $consulta = "INSERT INTO `pdbdd`.`votosinodep` "
            . "(`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`"
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

    //Si la muestra es mayor que el total el error es 0
    if ($muestra > $total) {
        $result = 0;
    } else {

        $z = Constantes::z;

        $t1 = ($total - $muestra) / ($muestra * ($total - 1));
        $result = ($z / 2) * sqrt($t1);
    }
    return $result;
}

function getTamanioMuestra($total, $error) {
    $z = Constantes::z;

    $r = ($z * $z * 0.25) / ($error * $error);

    $muestra = $r / (1 + (($r - 1) / $total));

    return $muestra;
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

function getNuevosRepresentantesParaVotacion($nmuestra, $id_votacion) {
    $res = ejecutar("SELECT usuario.idusuario FROM usuario "
            . " LEFT JOIN votosinodep ON votosinodep.usuario_idusuario = usuario.idusuario"
            . " AND votosinodep.votacionsinodep_idvotacionsinodep =" . escape($id_votacion)
            . " WHERE votosinodep.representante IS NULL"
            . " OR votosinodep.representante = 0"
            . " ORDER BY RAND() LIMIT " . $nmuestra);

    if (!$res->hayerror) {

        $array = toArray($res->resultado);

        $res->resultado = $array;
    }

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

    $res = ejecutar("SELECT * FROM objetivo_has_votacionsinodep WHERE"
            . " objetivo_idobjetivo='" . escape($id_objetivo) . "'"
            . " AND nombre='Aprobación'");

    if (!$res->hayerror) {
        $resultado = $res->resultado;

        if ($resultado->num_rows == 1) {

            $fila = $resultado->fetch_assoc();

            $res->resultado = $fila['votacionsinodep_idvotacionsinodep'];
        } else {
            $res->hayerror = true;
            $res->errormsg = "Hay más de una votación de aprobación en el objetivo";
        }
    }

    return $res;
}

function emitirVoto($id_votacion, $valor) {
    //Comprobamos que estemos logueados
    $res = checkLogin();

    if (!$res->hayerror) {

        if ($res->resultado) {
            //Estamos logueados
            //Utilizamos el id de usuario del usuario en cuestión
            $id_usuario = $_SESSION['idusuario'];

            //Insertamos el voto y si existe lo actualizamos
            $res = ejecutar("INSERT INTO `pdbdd`.`votosinodep` "
                    . "(`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`, `valor`)"
                    . " VALUES"
                    . "('" . escape($id_usuario) . "'"
                    . ",'" . escape($id_votacion) . "'"
                    . "," . escape($valor)
                    . ") ON DUPLICATE KEY UPDATE"
                    . " valor=" . escape($valor));
        }
    }

    return $res;
}

function checkTime() {

    checkVotaciones();

    checkObjetivos();
}

function checkVotaciones() {

    //Obtenemos el total de individuos
    $nindividuos = getTotalIndividuos()->resultado;

    //TODO Desactivamos todas las votaciones 
    //Comprobar votaciones
    //Obtenemos las votaciones que deben ser controladas con toda la información necesaria
    $res = ejecutar("SELECT votacionsinodep.*"
            . ", SUM(CASE WHEN votosinodep.representante = 1 THEN 1 ELSE 0 END) as representantes"
            . ", SUM(CASE WHEN votosinodep.representante = 1 AND votosinodep.valor = 1 THEN 1 ELSE 0 END) as votos_negativos_rep"
            . ", SUM(CASE WHEN votosinodep.representante = 1 AND votosinodep.valor = 2 THEN 1 ELSE 0 END) as votos_depende_rep"
            . ", SUM(CASE WHEN votosinodep.representante = 1 AND votosinodep.valor = 3 THEN 1 ELSE 0 END) as votos_positivos_rep"
            . ", SUM(CASE WHEN votosinodep.valor IS NOT NULL THEN 1 ELSE 0 END) as votos_emitidos"
            . ", SUM(CASE WHEN votosinodep.representante = 0 AND votosinodep.valor = 1 THEN 1 ELSE 0 END) as votos_negativos_ind"
            . ", SUM(CASE WHEN votosinodep.representante = 0 AND votosinodep.valor = 2 THEN 1 ELSE 0 END) as votos_depende_ind"
            . ", SUM(CASE WHEN votosinodep.representante = 0 AND votosinodep.valor = 3 THEN 1 ELSE 0 END) as votos_positivos_ind"
            . " FROM votacionsinodep "
            . " LEFT JOIN votosinodep ON votosinodep.votacionsinodep_idvotacionsinodep = votacionsinodep.idvotacionsinodep"
            . " WHERE checktime <= NOW()"
            . " AND finalizada=0"
            . " GROUP BY idvotacionsinodep");

    if (!$res->hayerror) {

        $votaciones = toArray($res->resultado);

        //Recorremos las votaciones que ya deben ser controladas

        foreach ($votaciones as $votacion) {

            //Recogemos datos
            $nrepresentantes = $votacion['representantes'];
            $votos_emitidos = $votacion['votos_emitidos'];
            $vsi_ind = $votacion['votos_positivos_ind'];
            $vno_ind = $votacion['votos_negativos_ind'];
            $vdep_ind = $votacion['votos_depende_ind'];
            $vsi_rep = $votacion['votos_positivos_rep'];
            $vno_rep = $votacion['votos_negativos_rep'];
            $vdep_rep = $votacion['votos_depende_rep'];
            $id_votacion = $votacion['idvotacionsinodep'];

            //Sumamos a los votos individuales los votos de representantes 
            //ya que los representantes también tienen voto individual
            $vsi_ind += $vsi_rep;
            $vno_ind += $vno_rep;
            $vdep_ind += $vdep_rep;

            echo "<br>Votacion: $id_votacion";
            echo "<br>$nindividuos individuos llamados a votar";
            echo "<br>para esta votacion se han nombrado $nrepresentantes representantes";
            echo "<br>$vsi_ind personas han votado 'Si' (incluidos representantes), el " . (($vsi_ind / $nindividuos) * 100) . "% de los individuos";
            echo "<br>$vno_ind personas han votado 'No' (incluidos representantes), el " . (($vno_ind / $nindividuos) * 100) . "% de los individuos";
            echo "<br>$vdep_ind personas han votado 'Depende' (incluidos representantes), el " . (($vdep_ind / $nindividuos) * 100) . "% de los individuos";
            echo "<br>$vsi_rep representantes han votado 'Si', el " . (($vsi_rep / $nrepresentantes) * 100) . "% de los representantes";
            echo "<br>$vno_rep representantes han votado 'No', el " . (($vno_rep / $nrepresentantes) * 100) . "% de los representantes";
            echo "<br>$vdep_rep representantes han votado 'Depende', el " . (($vdep_rep / $nrepresentantes) * 100) . "% de los representantes";

            $total_ind = $vsi_ind + $vno_ind + $vdep_ind;
            $total_rep = $vsi_rep + $vno_rep + $vdep_rep;

            echo "<br>En total se han emitido $total_ind votos";
            echo "<br>de los cuales $total_rep son de representantes";

            //Calculamos la abstención
            $abstencion = $nindividuos - $votos_emitidos;
            $abstencion_rep = $nrepresentantes - $total_rep;

            echo "<br>Se han abstenido $abstencion individuos";

            //Calculamos el error_actual en función de la abstención
            //ya que los votos de los representantes actúan sobre una población menor que la total
            //si ha votado alguien por sí mismo el error disminuye
            //Si hay representantes y abstención mayor que una persona lo podemos calcular, sino no
            if ($nrepresentantes > 0 && $abstencion > 1) {
                $error_actual = getErrorDeMuestra($abstencion, $nrepresentantes);
                echo "<br>El error que calculamos que pueden cometer $nrepresentantes representantes votando por $abstencion individuos es " . $error_actual * 100;
            } else {
                $error_actual = 0;
                echo "<br>No tenemos representantes o ha votado todo el mundo asi que el error es $error_actual";
            }

            //Los representantes representan a la abstención si los hay
            if ($nrepresentantes > 0) {
                $representacion = $abstencion / $nrepresentantes;
                echo "<br>Tenemos $nrepresentantes representantes que representan a $abstencion individuos con una cuota de representacion de $representacion cada uno";
            } else {
                $representacion = 0;
                echo "<br>No tenemos representantes asi que la cuota de representacion es $representacion";
            }

            //Sumamos los votos de cada opción
            //que es lo que vale un representante por los votos de representantes que haya reciobido la opción
            //más los votos individuales
            $vsi_representados = $representacion * $vsi_rep;
            $vno_representados = $representacion * $vno_rep;
            $vdep_representados = $representacion * $vdep_rep;

            echo "<br>Los representantes representan $vsi_representados votos para el 'Si', $vno_representados votos para el 'No' y $vdep_representados votos para el 'Depende'";

            //Calculamos los votos reales y representados por separado
            $porcentaje_vsi_representados = $vsi_representados/$abstencion;
            $porcentaje_vno_representados = $vno_representados/$abstencion;
            $porcentaje_vdep_representados = $vdep_representados/$abstencion;
            
            echo "<br>Los votos de los representantes suponen:";
            echo "<br>Un ".($porcentaje_vsi_representados*100)."% del 'Si'";
            echo "<br>Un ".($porcentaje_vno_representados*100)."% del 'No'";
            echo "<br>Un ".($porcentaje_vdep_representados*100)."% del 'Depende'";
            
            
            $porcentaje_vsi_independientes = $vsi_ind/$nindividuos;
            $porcentaje_vno_independientes = $vno_ind/$nindividuos;
            $porcentaje_vdep_independientes = $vdep_ind/$nindividuos;
            
            echo "<br>Los votos individuales suponen:";
            echo "<br>Un ".($porcentaje_vsi_independientes*100)."% del 'Si'";
            echo "<br>Un ".($porcentaje_vno_independientes*100)."% del 'No'";
            echo "<br>Un ".($porcentaje_vdep_independientes*100)."% del 'Depende'";
            
            $sumasi = $vsi_representados + $vsi_ind;
            $sumano = $vno_representados + $vno_ind;
            $sumadep = $vdep_representados + $vdep_ind;

            //Calculamos los porcentajes de cada opción sumando los anteriores
            $psi = $sumasi / $nindividuos;
            $pno = $sumano / $nindividuos;
            $pdep = $sumadep / $nindividuos;

            echo "<br>Si sumamos los votos individuales y representados tenemos:";
            echo "<br>$sumasi votos para el 'Si', el " . ($psi * 100) . "%";
            echo "<br>$sumano votos para el 'No', el " . ($pno * 100) . "%";
            echo "<br>$sumadep votos para el 'Depende', el " . ($pdep * 100) . "%";
            echo "<br>Que juntos suman " . ($sumasi + $sumano + $sumadep) . ", el " . (($psi + $pno + $pdep) * 100) . "%";
            
            
            
            //Y ahora ya podemos calcular el apoyo a las diferentes opciones y los errores
            //Para cada una comprobamos si ha terminado o la ampliamos
            //TODO a ver qué hacemos si se abstiene todo cristo
            $resultado = 0;
            
            //TODO calcular mejor los mínimos que no reste la segunda parte a la primera cuando el error sea mayor que el porcentaje representado
            $minimo_si = $porcentaje_vsi_independientes + ($porcentaje_vsi_representados - $error_actual);
            $minimo_no = $porcentaje_vno_independientes + ($porcentaje_vno_representados - $error_actual);
            $maximo_si = $porcentaje_vsi_independientes + ($porcentaje_vsi_representados + $error_actual);
            $maximo_no = $porcentaje_vno_independientes + ($porcentaje_vno_representados + $error_actual);
            
            echo "<br>Segun los datos obtenidos las predicciones son las siguientes:";
            echo "<br>'Si' obtendra entre un ".($minimo_si*100)."% y un ".($maximo_si*100)."% del total de votos.";
            echo "<br>'No' obtendra entre un ".($minimo_no*100)."% y un ".($maximo_no*100)."% del total de votos.";
            //echo "<br>'Depende' obtendrá entre un ".$minimo_si."% y un ".$maximo_si."% del total de votos.";
            
            if ($minimo_si > 0.5) {
                //Si el porcentaje de sí menos el error aún es mayor que la mitad entonces es la opción seleccionada
                $resultado = 3;
                echo "<br>Como el minimo del 'Si' es mayor que la mitad podemos asegurar que este sera el resultado mayoritario.";
            } else if ($minimo_no > 0.5) {
                $resultado = 1;
                echo "<br>Como el minimo del 'No' es mayor que la mitad podemos asegurar que este sera el resultado mayoritario.";
            } else if ($maximo_si <= 0.5 && $maximo_no <= 0.5) {
                //Si ni "sí" ni "no" tienen opciones ya de alcanzar mayoría es un "depende"
                $resultado = 2;
                echo "<br>Ya que ninguna de las opciones extremas puede ya conseguir la mayoria 'Depende' es la opcion seleccionada.";
            }

            if ($resultado == 0) {
                
                echo "<br>Aun no se han descartado suficientes opciones.";
                
                //Si seguimos sin tener una respuesta tenemos que ampliar la muestra
                //Calculamos las diferencias en el error necesarias para cambiar de escenario
                //Nos interesa que el error sea tan pequeño que
                //haga que los resultados más/menos el error no traspasen
                //el 0.5, es decir, se queden en su lado, ya sea más allá o sin llegar a pasarlo
                //así que calcularemos las diferencias entre los porcentajes y el 0.5
                //estos son los errores deseados para las opciones y escogeremos de ellos
                //el mayor por ser más fácil de conseguir (molestamos a menos personas)

                $dsi = abs($psi - 0.5);
                $dno = abs($pno - 0.5);
                
                echo "<br>Para decidirnos tendriamos que reducir el error hasta el ".($dsi*100)."% o el ".($dno*100)."%";

                $error_deseado = max($dsi, $dno);
                
                echo "<br>El mas cercano es ".($error_deseado*100)."%.";


                //Calculamos el tamaño de la muestra en función de la abstención
                //porque los que ya han votado no se pueden abstener
                $muestra_necesaria = getTamanioMuestra($abstencion, $error_deseado);

                //Nos aseguramos de que se coge el mínimo más de lo necesario
                $muestra_necesaria = floor($muestra_necesaria) + 1;

                echo "<br>Para poder representar a $abstencion individuos (la abstencion)"
                        . " con un error inferior al ".($error_deseado*100)."% necesitamos una muestra de $muestra_necesaria individuos.";

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
                //De momento opción 1
                $muestra_real_actual = $nrepresentantes;

                $ampliacion_muestra = $muestra_necesaria - $muestra_real_actual;

                echo "<br>ampliacion_muestra: $ampliacion_muestra";

                //Ampliamos la muestra

                if ($ampliacion_muestra > 0) {
                    $representantes = getNuevosRepresentantesParaVotacion($ampliacion_muestra, $id_votacion)->resultado;
                    $res = addRepresentantesAVotacion($representantes, $id_votacion);
                }

                //Contamos una ampliación más y definimos el nuevo checktime
                $res = ejecutar("UPDATE votacionsinodep SET"
                        . " ampliaciones=ampliaciones+1" . $resultado
                        . ", checktime=NOW() + INTERVAL " . Constantes::checktime_minutos . " MINUTE"
                        . ", activa=1"
                        . " WHERE idvotacionsinodep=" . $id_votacion);
            } else {
                //Si tenemos resultado, lo guardamos con los datos de votación y se finaliza la votación
                //TODO guardar todos los resultados

                $res = ejecutar("UPDATE votacionsinodep SET"
                        . " resultado=" . $resultado
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
                        . " WHERE idvotacionsinodep=" . $id_votacion);
            }
        }
    }
    return $res;
}

function checkObjetivos() {

    //Comprobamos los objetivos para ver si cambian de estado o qué pasa
    //Aquellos objetivos en el estado 1 con sus votaciones de "Aprobación" finalizadas y con resultado 2 pasan a estar rechazados (estado 2)
    //Seleccionamos los objetivos con estado 1 (aprobación) y votación finalizada
    $res = ejecutar("SELECT objetivo.*"
            . ", votacionsinodep.resultado as resultado"
            . " FROM objetivo "
            . " STRAIGHT_JOIN objetivo_has_votacionsinodep ON objetivo_has_votacionsinodep.objetivo_idobjetivo=objetivo.idobjetivo"
            . " AND objetivo_has_votacionsinodep.nombre='Aprobación'"
            . " STRAIGHT_JOIN votacionsinodep ON objetivo_has_votacionsinodep.votacionsinodep_idvotacionsinodep = votacionsinodep.idvotacionsinodep"
            . " AND votacionsinodep.finalizada=1"
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
