<?php

include_once dirname(__FILE__) . "/clases.php";

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
        $this->mysqli = new mysqli($this->host, $this->user_name, $this->pass, $this->bdd_name) or die("No se pudo conectar a la BDD.");
        $this->mysqli->set_charset("UTF-8");
        $this->mysqli->query("SET NAMES utf8");
    }

    function checkConnection() {
        //Si no estamos conectados a la BDD lo hacemos
        if ($this->mysqli == null) {
            $this->conectar();
        }
    }

    function ejecutar($consulta) {
        //Necesitamos estar conectados a la bdd
        $this->checkConnection();

        $resultado = $this->mysqli->query($consulta);

        $respuesta = new Res();

        if (!$resultado) {
            $respuesta->hayerror = true;
            $respuesta->errormsg = "Falló la consulta $consulta (" . $this->mysqli->errno . ") " . $this->mysqli->error;
            //trigger_error("Falló la consulta $consulta (" . $mysqli->errno . ") " . $mysqli->error, E_USER_ERROR);
        } else {
            $respuesta->resultado = $resultado;
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
        
        $res = new Res();

        $resultado = $this->mysqli->query($consulta);

        if (!$resultado) {
            $res->hayerror = true;
            $res->errormsg = "Falló la consulta $consulta (" . $this->mysqli->errno . ") " . $this->mysqli->error;
        } else {
            $res->resultado = $this->mysqli->insert_id;
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

}
