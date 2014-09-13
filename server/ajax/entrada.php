<?php

include_once dirname(__FILE__) . "./funciones.php";


try {

    $peticion = json_decode($_REQUEST['p']);

    $funcionesPermitidas = array(
        'checkLogin'
        , 'registrarUsuario'
        , 'doLogin'
        , 'doLogout'
        , 'getSession'
        , 'getObjetivosConInfo'
        , 'addObjetivo'
        , 'votarAprobacionObjetivo'
        , 'getGrupos'
        , 'addGrupo'
        , 'getGruposDeUsuarioActual'
        , 'addMiembro'
        , 'getGrupoPorID'
        , 'getDetalleDeGrupo'
        , 'solicitarIngresoEnGrupo'
        , 'solicitarBaja'
        , 'getSubGrupos'
        , 'addSubGrupo'
        , 'getSuperGrupos'
        , 'addNuevoSuperGrupo'
        , 'hacerSuperGrupo'
        , 'getVotacionesSNDDeGrupo'
        , 'getVotacionesSNDDeGrupoParaUsuarioActual'
        , 'getVotacionesSNDPendientesDeUsuarioActualComoRepresentante'
        , 'crearDecision'
        , 'emitirVoto'
        , 'getEnunciadosDeGrupo'
        , 'votarDepende'
        , 'getUsuarioActual'
        , 'setUsuarioActual'
        , 'loginfacebook'
        , 'nuevoMensajeChatGrupo'
        , 'getChatGrupoNuevo'
        , 'getChatGrupoNuevoID'
        , 'getInfoDeGrupo'
        , 'getInfoMiembro'
        , 'getInfoMiembros'
        , 'ingresarEnGrupo'
        , 'seguirGrupo'
        , 'getTiposAcciones'
        , 'crearDecisionConAcciones'
    );
    
    $funcionesProhibidas = array(
        'addMiembro'
        , 'hacerSubGrupo'
    );


//Si está permitida la ejecutamos y delvolvemos el resultado
    if (in_array($peticion->id_funcion, $funcionesPermitidas)
            && !in_array($peticion->id_funcion, $funcionesProhibidas)) {

        //Si tenemos parámetros los enviamos
        if (isset($peticion->parametros)) {
            $resultado = call_user_func_array($peticion->id_funcion, $peticion->parametros);
        } else {
            $resultado = call_user_func($peticion->id_funcion);
        }
        //var_dump($resultado);
        /*
        if ($resultado === null) {
            throw new Exception("La función $peticion->id_funcion no existe, no es accesible o sufre de excepciones no controladas.");
        }*/
    } else {
        throw new Exception("Función no permitida $peticion->id_funcion.");
    }

    $respuesta = new stdClass();
    $respuesta->hayerror = false;
    $respuesta->resultado = $resultado;
    
} catch (Exception $e) {
    $respuesta = new stdClass();
    $respuesta->hayerror = true;
    $respuesta->errormsg = $e->getMessage();
}

echo json_encode($respuesta);

