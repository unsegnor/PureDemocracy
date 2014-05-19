<?php

include_once dirname(__FILE__) . "./../ajax/funciones.php";

$id_votacion = $_REQUEST['id'];
$vsi = $_REQUEST['vsi'];
$vno = $_REQUEST['vno'];
$vdep = $_REQUEST['vdep'];
$vsirep = $_REQUEST['vsirep'];
$vnorep = $_REQUEST['vnorep'];
$vdeprep = $_REQUEST['vdeprep'];

//Simular los resultados de la votación
//Anotar votos de representantes
//Seleccionamos representantes que no hayan votado


echo "<br>";
//VNO
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosinodep` "
                . " (`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 1"
                . " FROM usuario"
                . " LEFT JOIN votosinodep"
                . " ON votosinodep.usuario_idusuario = usuario.idusuario"
                . " AND votosinodep.votacionsinodep_idvotacionsinodep = " . escape($id_votacion)
                . " WHERE (votosinodep.valor IS NULL OR votosinodep.valor = 0)" //Que no hayann votado
                . " AND (votosinodep.representante IS NULL OR votosinodep.representante =0)"
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vno)));

echo "<br>";
//VDEP
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosinodep` "
                . " (`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 2"
                . " FROM usuario"
                . " LEFT JOIN votosinodep"
                . " ON votosinodep.usuario_idusuario = usuario.idusuario"
                . " AND votosinodep.votacionsinodep_idvotacionsinodep = " . escape($id_votacion)
                . " WHERE (votosinodep.valor IS NULL OR votosinodep.valor = 0)" //Que no hayann votado
                . " AND (votosinodep.representante IS NULL OR votosinodep.representante =0)"
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vdep)));

echo "<br>";
//VSI
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosinodep` "
                . " (`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 3"
                . " FROM usuario"
                . " LEFT JOIN votosinodep"
                . " ON votosinodep.usuario_idusuario = usuario.idusuario"
                . " AND votosinodep.votacionsinodep_idvotacionsinodep = " . escape($id_votacion)
                . " WHERE (votosinodep.valor IS NULL OR votosinodep.valor = 0)" //Que no hayann votado
                . " AND (votosinodep.representante IS NULL OR votosinodep.representante =0)"
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vsi)));

echo "<br>";
//VNOREP
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosinodep` "
                . " (`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 1"
                . " FROM usuario"
                . " LEFT JOIN votosinodep"
                . " ON votosinodep.usuario_idusuario = usuario.idusuario"
                . " AND votosinodep.votacionsinodep_idvotacionsinodep = " . escape($id_votacion)
                . " WHERE (votosinodep.valor IS NULL OR votosinodep.valor = 0)" //Que no hayan votado
                . " AND (votosinodep.representante =1)" //Que sean representantes
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vnorep)
                . " ON DUPLICATE KEY UPDATE votosinodep.valor=1"));

echo "<br>";
//VDEPREP
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosinodep` "
                . " (`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 2"
                . " FROM usuario"
                . " LEFT JOIN votosinodep"
                . " ON votosinodep.usuario_idusuario = usuario.idusuario"
                . " AND votosinodep.votacionsinodep_idvotacionsinodep = " . escape($id_votacion)
                . " WHERE (votosinodep.valor IS NULL OR votosinodep.valor = 0)" //Que no hayan votado
                . " AND (votosinodep.representante =1)" //Que sean representantes
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vdeprep)
                . " ON DUPLICATE KEY UPDATE votosinodep.valor=2"));

echo "<br>";
//VSIREP
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosinodep` "
                . " (`usuario_idusuario`, `votacionsinodep_idvotacionsinodep`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 3"
                . " FROM usuario"
                . " LEFT JOIN votosinodep"
                . " ON votosinodep.usuario_idusuario = usuario.idusuario"
                . " AND votosinodep.votacionsinodep_idvotacionsinodep = " . escape($id_votacion)
                . " WHERE (votosinodep.valor IS NULL OR votosinodep.valor = 0)" //Que no hayan votado
                . " AND (votosinodep.representante =1)" //Que sean representantes
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vsirep)
                . " ON DUPLICATE KEY UPDATE votosinodep.valor=3"));
