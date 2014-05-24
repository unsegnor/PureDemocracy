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
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosnd` "
                . " (`usuario_idusuario`, `votacionsnd_idvotacionsnd`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 1"
                . " FROM usuario"
                . " LEFT JOIN votosnd"
                . " ON votosnd.usuario_idusuario = usuario.idusuario"
                . " AND votosnd.votacionsnd_idvotacionsnd = " . escape($id_votacion)
                . " WHERE (votosnd.valor IS NULL OR votosnd.valor = 0)" //Que no hayann votado
                . " AND (votosnd.representante IS NULL OR votosnd.representante =0)"
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vno)));

echo "<br>";
//VDEP
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosnd` "
                . " (`usuario_idusuario`, `votacionsnd_idvotacionsnd`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 2"
                . " FROM usuario"
                . " LEFT JOIN votosnd"
                . " ON votosnd.usuario_idusuario = usuario.idusuario"
                . " AND votosnd.votacionsnd_idvotacionsnd = " . escape($id_votacion)
                . " WHERE (votosnd.valor IS NULL OR votosnd.valor = 0)" //Que no hayann votado
                . " AND (votosnd.representante IS NULL OR votosnd.representante =0)"
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vdep)));

echo "<br>";
//VSI
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosnd` "
                . " (`usuario_idusuario`, `votacionsnd_idvotacionsnd`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 3"
                . " FROM usuario"
                . " LEFT JOIN votosnd"
                . " ON votosnd.usuario_idusuario = usuario.idusuario"
                . " AND votosnd.votacionsnd_idvotacionsnd = " . escape($id_votacion)
                . " WHERE (votosnd.valor IS NULL OR votosnd.valor = 0)" //Que no hayann votado
                . " AND (votosnd.representante IS NULL OR votosnd.representante =0)"
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vsi)));

echo "<br>";
//VNOREP
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosnd` "
                . " (`usuario_idusuario`, `votacionsnd_idvotacionsnd`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 1"
                . " FROM usuario"
                . " LEFT JOIN votosnd"
                . " ON votosnd.usuario_idusuario = usuario.idusuario"
                . " AND votosnd.votacionsnd_idvotacionsnd = " . escape($id_votacion)
                . " WHERE (votosnd.valor IS NULL OR votosnd.valor = 0)" //Que no hayan votado
                . " AND (votosnd.representante =1)" //Que sean representantes
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vnorep)
                . " ON DUPLICATE KEY UPDATE votosnd.valor=1"));

echo "<br>";
//VDEPREP
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosnd` "
                . " (`usuario_idusuario`, `votacionsnd_idvotacionsnd`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 2"
                . " FROM usuario"
                . " LEFT JOIN votosnd"
                . " ON votosnd.usuario_idusuario = usuario.idusuario"
                . " AND votosnd.votacionsnd_idvotacionsnd = " . escape($id_votacion)
                . " WHERE (votosnd.valor IS NULL OR votosnd.valor = 0)" //Que no hayan votado
                . " AND (votosnd.representante =1)" //Que sean representantes
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vdeprep)
                . " ON DUPLICATE KEY UPDATE votosnd.valor=2"));

echo "<br>";
//VSIREP
var_dump(ejecutar("INSERT INTO `pdbdd`.`votosnd` "
                . " (`usuario_idusuario`, `votacionsnd_idvotacionsnd`,`valor`) "
                . " SELECT usuario.idusuario"
                . ", " . escape($id_votacion)
                . ", 3"
                . " FROM usuario"
                . " LEFT JOIN votosnd"
                . " ON votosnd.usuario_idusuario = usuario.idusuario"
                . " AND votosnd.votacionsnd_idvotacionsnd = " . escape($id_votacion)
                . " WHERE (votosnd.valor IS NULL OR votosnd.valor = 0)" //Que no hayan votado
                . " AND (votosnd.representante =1)" //Que sean representantes
                . " ORDER BY RAND()"
                . " LIMIT " . escape($vsirep)
                . " ON DUPLICATE KEY UPDATE votosnd.valor=3"));
