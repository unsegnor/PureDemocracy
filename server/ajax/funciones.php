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

function getRepresentantes($nmuestra) {
    $res = ejecutar("SELECT usuario.idusuario FROM usuario ORDER BY RAND() LIMIT " . $nmuestra);

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
            . "FROM votacionsinodep "
            . "LEFT JOIN votosinodep ON votosinodep.votacionsinodep_idvotacionsinodep = votacionsinodep.idvotacionsinodep"
            . "WHERE checktime <= NOW()"
            . "GROUP BY idvotacionsinodep");

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
            
            $total_ind = $vsi_ind + $vno_ind + $vdep_ind;
            $total_rep = $vsi_rep + $vno_rep + $vdep_rep;
            
            //Calculamos la abstención
            $abstencion = $nindividuos - $total_ind;
            $abstencion_rep = $nrepresentantes - $total_rep;
            
            //Calculamos el error_actual en función de la abstención
            //ya que los votos de los representantes actúan sobre una población menor que la total
            //si ha votado alguien por sí mismo el error disminuye
            $error_actual = getErrorDeMuestra($abstencion, $nrepresentantes);
            
            //Los representantes representan a la abstención
            $representacion = $abstencion / $nrepresentantes;
            
            //Sumamos los votos de cada opción
            //que es lo que vale un representante por los votos de representantes que haya reciobido la opción
            //más los votos individuales
            $sumasi = $representacion*$vsi_rep + $vsi_ind;
            $sumano = $representacion*$vno_rep + $vno_ind;
            $sumadep = $representacion*$vdep_rep + $vdep_ind;
            
            //Calculamos los porcentajes de cada opción
            $psi = $sumasi/$nindividuos;
            $pno = $sumano/$nindividuos;
            $pdep = $sumadep/$nindividuos;
            
            
            //Y ahora ya podemos calcular el apoyo a las diferentes opciones y los errores
            //Para cada una comprobamos si ha terminado o la ampliamos
            $resultado = 0;
            if($psi - $error_actual > 0.5){
                //Si el porcentaje de sí menos el error aún es mayor que la mitad entonces es la opción seleccionada
                $resultado = 3;
            }else if($pno - $error_actual > 0.5){
                $resultado = 1;
            }else if($psi + $error_actual < 0.5 && $pno + $error_actual < 0.5){
                //Si ni "sí" ni "no" tienen opciones ya de alcanzar mayoría es un "depende"
                $resultado = 2;
            }
            
            if($resultado == 0){
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
                
                $error_deseado = max($dsi,$dno);
                
                //Calculamos el tamaño de la muestra en función de la abstención
                //porque los que ya han votado no se pueden abstener
                $muestra_necesaria = getTamanioMuestra($abstencion, $error_deseado);
                
                //Nos aseguramos de que se coge el mínimo más de lo necesario
                $muestra_necesaria = floor($muestra_necesaria)+1;
                
                //No contamos con que los representantes que se han abstenido vayan a votar
                //pero les seguimos dando la opción por si votan y así ayudan a disminuir el error más aún
                //Calculamos la diferencia entre la muestra que tenemos 
                //(los representantes que se han pronunciado) y la necesaria
                //aunque los representantes que se han abstenido siguen representando a mucha gente
                $muestra_real_actual = $total_rep;
                
                $ampliacion_muestra = $muestra_necesaria - $muestra_real_actual;
                
                //Calculamos el error que estaremos cometiendo, no hace falta guardarlo porque se
                //calcula cada vez que se comprueban los datos de la votación
                $error_nuevo = getErrorDeMuestra($abstencion, $muestra_necesaria);
                
            }
            
        }
    }
}
