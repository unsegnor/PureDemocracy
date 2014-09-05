<?php
include dirname(__FILE__) . "./header.php";
//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');
?>
<div ng-controller="controladorvotaciones" ng-init="init(<?php echo $id ?>)">
    <div class="container principal" ng-cloak="ng-cloack">   
        <div class="panel panel-default">
            <div class="panel-heading">
                Votaciones en curso
            </div>
            <div class="list-group">
                <!-- Listamos los enunciados pendientes del grupo -->
                <div class="list-group-item"
                     ng-repeat="votacion in votaciones| filter:{'finalizada':0}">
                    <div class="row">
                        <div class="col-sm-2">
                            <a href="detallegrupo.php?id={{votacion.censo}}">{{votacion.nombregrupo}}</a>  
                        </div>
                        <div class="col-sm-5">
                            {{votacion.enunciado}}     
                        </div>
                        <div class="col-sm-2">
                            <progressbar class="progress-striped active" max="1" value="votacion.transcurrido" type="primary"></progressbar>
                        </div>
                        <div class="col-sm-2">
                            <div class="btn-group">
                                <button class="btn btn-success"
                                        ng-click="votar(votacion.idvotacionsnd, 3)"
                                        ng-disabled="votacion.valor == 3"><span class="glyphicon glyphicon-ok-sign"></span></button>
                                <button class="btn btn-warning"
                                        ng-click="votar(votacion.idvotacionsnd, 2)"
                                        ng-disabled="votacion.valor == 2"><span class="glyphicon glyphicon-question-sign"></span></button>
                                <button class="btn btn-danger"
                                        ng-click="votar(votacion.idvotacionsnd, 1)"
                                        ng-disabled="votacion.valor == 1"><span class="glyphicon glyphicon-remove-sign"></span></button>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div ng-show="votacion.valor == null">
                                <span class="badge alert-info" ng-show="votacion.representante == null || votacion.representante == 0">+5</span>
                                <span class="badge alert-info" ng-show="votacion.representante == 1">+100</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Votaciones finalizadas
            </div>

            <div class="list-group">
                <!-- Listamos los enunciados pendientes del grupo -->
                <div class="list-group-item"
                     ng-repeat="votacion in votaciones| filter:{'finalizada':1}">
                    <div class="row">
                        <div class="col-sm-8">
                            <span ng-show="votacion.resultado == 1"
                                  class="glyphicon glyphicon-remove pull-left"></span>
                            <span ng-show="votacion.resultado == 2"
                                  class="glyphicon glyphicon-question-sign pull-left"></span>
                            <span ng-show="votacion.resultado == 3"
                                  class="glyphicon glyphicon-ok pull-left"></span>
                            <span ng-show="votacion.resultado == 4"
                                  class="glyphicon glyphicon-exclamation-sign pull-left"></span>
                            {{votacion.enunciado}}     
                        </div>
                        <div class="col-sm-4">
                            <progress max="1">
                                <bar value="votacion.minimosi" type="success"><span>{{(votacion.minimosi * 100).toFixed(2)}}%</span></bar>
                                <bar value="votacion.minimodep" type="warning"><span>{{(votacion.minimodep * 100).toFixed(2)}}%</span></bar>
                                <bar value="votacion.minimono" type="danger"><span>{{(votacion.minimono * 100).toFixed(2)}}%</span></bar>
                                <bar value="votacion.error" type="default"><span>{{(votacion.error * 100).toFixed(2)}}%</span></bar>
                            </progress>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
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