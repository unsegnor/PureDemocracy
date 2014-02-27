<?php

/* 
 * Vamos a intentar hacer unas funciones genéricas pero seguras 
 * para acceder prácticamente desde la web a la BDD 
 * para los casos en los que sea útil/necesario
 */
include_once dirname(__FILE__) . "/../nucleo/controlador.php";


function getAll($tabla){
        $res = new Res();
    if(tiene_permiso("ver_".$tabla)){
        
        $consulta = "SELECT * FROM ".escape($tabla);
        
        $res = ejecutar($consulta);
        
        if(!$res->hayerror){
            $resultado = $res->resultado;
            
            $array = toArray($resultado);
            
            $res->resultado = $array;
        }
    }else{
                //Si no hay permisos devolvemos error
        $res->hayerror = true;
        $res->errormsg[] = "No dispone de permisos suficientes para $tabla";
    }
    
    return $res;
}


function getArray($tabla, $campo_condicion, $valor_condicion){
    $res = new Res();
    if(tiene_permiso("ver_".$tabla)){
        
        $consulta = "SELECT * FROM ".escape($tabla)
                ." WHERE ".escape($campo_condicion)." = '".escape($valor_condicion)."'";
        
        $res = ejecutar($consulta);
        
        if(!$res->hayerror){
            $resultado = $res->resultado;
            
            $array = toArray($resultado);
            
            $res->resultado = $array;
        }
    }else{
                //Si no hay permisos devolvemos error
        $res->hayerror = true;
        $res->errormsg[] = "No dispone de permisos suficientes para $tabla";
    }
    
    return $res;
}

function set($tabla, $campo_condicion, $valor_condicion, $campo, $valor){
    
    $res = new Res();
    
    //Comprobamos permisos
    if(tiene_permiso("edit_".$tabla)){
        
        $consulta = "UPDATE ".escape($tabla)
                ." SET ". escape($campo) . " = '". escape($valor)."'"
                ." WHERE ". escape($campo_condicion). " = '".escape($valor_condicion)."'";
        
        $res = ejecutar($consulta);
    }else{
        //Si no hay permisos devolvemos error
        $res->hayerror = true;
        $res->errormsg[] = "No dispone de permisos suficientes para $tabla";
    }
    
    return $res;
}
