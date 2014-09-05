<?php
include dirname(__FILE__) . "./header.php";

//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');
?>
<div ng-controller="controladorchatgrupo" ng-init="init(<?php echo $id ?>)">
    <div class="container nocentrado">
        {{ultima_actualizacion}}

        <ul class="media-list">
            <li class="media" ng-repeat="mensaje in mensajes">
                <!--<a class="pull-left" href="#">
                    <img class="media-object peque" src="./img/user.svg" alt="...">
                </a>-->
                <div class="media-body">
                    <h4 class="media-heading">{{mensaje.nombre_usuario}}</h4>
                    {{mensaje.mensaje}}
                </div>
            </li>
        </ul>
<!--
        <ul class="list-group">
            <li class="list-group-item" ng-repeat="mensaje in mensajes"><b>{{mensaje.usuario_idusuario}}:</b> {{mensaje.mensaje}} {{mensaje.fecha}}</li>
        </ul>
-->
        <input type="text" class="form-control" ng-model="nuevo_mensaje.texto" ng-enter="sendmsg(nuevo_mensaje.texto)" placeholder="escribir..."/>
    </div>

    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="navbar-inner">
            <ul class="nav navbar-nav">
                <!-- un enlace por cada objeto del menu inferior -->
                <li ><a href="chatgrupo.php?id=<?php echo $id ?>" title="chat"><span class="glyphicon glyphicon-comment"></span></a></li>
                <li ><a href="votaciones.php?id=<?php echo $id ?>" title="ver votaciones"><span class="glyphicon glyphicon-search"></span></a></li>
                <li ><a href="nuevavotacion.php?id=<?php echo $id ?>" title="nueva votación"><span class="glyphicon glyphicon-plus"></span></a></li>
                <li ><a href="arbolgrupo.php?id=<?php echo $id ?>" title="grupos relacionados"><span class="glyphicon glyphicon-sort"></span></a></li>
            </ul>
        </div>
    </nav>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>