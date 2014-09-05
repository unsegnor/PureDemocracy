<?php

/**
 * Description of BDD
 *
 * @author Víctor Calatayud Asensio <vcalatayud@kalati.es>
 */
class BDD {

    var $mysqli = null;
    var $host;
    var $user_name;
    var $pass;
    var $bdd_name;

    function __construct($host, $bdd_name, $user_name, $pass) {
        $this->host = $host;
        $this->user_name = $user_name;
        $this->pass = $pass;
        $this->bdd_name = $bdd_name;
    }

    function conectar() {
        $this->mysqli = new mysqli($this->host, $this->user_name, $this->pass, $this->bdd_name);
        if($this->mysqli->connect_errno){
            throw new Exception("No se pudo establecer la conexión con BDD.");
        }
        $this->mysqli->set_charset("UTF-8");
        $this->mysqli->query("SET NAMES utf8");
        $this->mysqli->query("SET @@session.time_zone='+00:00'");
    }

    function checkConnection() {
        //Si no estamos conectados a la BDD lo hacemos
        if (!$this->mysqli) {
            $this->conectar();
        }
    }

    function ejecutar($consulta) {
        //Necesitamos estar conectados a la bdd
        $this->checkConnection();

        $resultado = $this->mysqli->query($consulta);


        if (!$resultado) {
            throw new Exception("Falló la consulta $consulta (" . $this->mysqli->errno . ") " . $this->mysqli->error);
        } else {
            $respuesta = $resultado;
        }

        return $respuesta;
    }

    /**
     * 
     * @global mysqli $mysqli
     * @param type $consulta
     * @return type Realiza el insert y devuelve el vector de ids asignadas
     */
    function insert_id($consulta) {

        $this->checkConnection();

        $res = $this->mysqli->query($consulta);

        if (!$res) {
            throw new Exception("Falló la consulta $consulta (" . $this->mysqli->errno . ") " . $this->mysqli->error);
        } else {
            $res = $this->mysqli->insert_id;
        }

        return $res;
    }

    function escape($string) {
        $this->checkConnection();
        return $this->mysqli->real_escape_string($string);
    }

    function toArray($resultado) {
        $respuesta = array();

        while ($fila = $resultado->fetch_assoc()) {
            $respuesta[] = $fila;
        }

        return $respuesta;
    }

    function toArrayID($resultado, $id) {
        $respuesta = array();

        while ($fila = $resultado->fetch_assoc()) {
            $respuesta[(int) ($fila[$id])] = $fila;
        }

        return $respuesta;
    }
    
    function iniciar_transaccion(){
        $this->checkConnection();
        return $this->mysqli->autocommit(false);
    }

    function validar_transaccion(){
        $this->checkConnection();
        $this->mysqli->commit();
        return $this->mysqli->autocommit(true);
    }
    
    function cancelar_transaccion(){
        $this->checkConnection();
        $this->mysqli->rollback();
        return $this->mysqli->autocommit(true);
    }
}
