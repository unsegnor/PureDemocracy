<?php
include dirname(__FILE__) . "./header.php";

//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');
?>
<div ng-controller="controladorchatgrupo" ng-init="init(<?php echo $id ?>)">
    <div class="container principal">
        {{ultima_actualizacion}}
        <ul class="list-group">
            <li class="list-group-item" ng-repeat="mensaje in mensajes"><b>{{mensaje.usuario_idusuario}}:</b> {{mensaje.mensaje}} {{mensaje.fecha}}</li>
        </ul>
        
        <input type="text" class="form-control" ng-model="nuevo_mensaje.texto" ng-enter="sendmsg(nuevo_mensaje.texto)" placeholder="escribir..."/>
    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>