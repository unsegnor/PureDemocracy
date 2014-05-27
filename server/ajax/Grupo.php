<?php

include_once dirname(__FILE__) . "./conexionbdd.php";

/**
 * Description of Grupo
 *
 * @author Víctor Calatayud Asensio <vcalatayud@kalati.es>
 */
class Grupo {
    //put your code here
    var $nombre;
    
    
    public function __construct() {
        
    }
    
    public function cargarPorID($id){
        $res = ejecutar("SELECT * FROM grupo WHERE idgrupo=".escape($id));
        $fila = $res->fetch_assoc();
        $this->nombre = $fila['nombre'];
    }
    
    public function save(){
        
    }
    
}
