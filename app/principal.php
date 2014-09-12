<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladorprincipal">
    <div class="container principal" ng-cloak>
        <!--<div class="page-header"><h3>Hola {{session.nombre}}!</h3></div>-->

        <div class="row">
            <!-- Mostrar las notificaciones/sugerencias remuneradas del sistema en la repartición del trabajo -->

            <div class="panel panel-default">

                <div class="panel-heading">
                    Votaciones pendientes importantes
                </div>

                <div class="list-group">
                    <div class="list-group-item"
                         ng-repeat="votacion in votaciones| filter:{'finalizada':0}">
                        <div class="row">
                            <div class="col-sm-2" >
                                <a href="infogrupo.php?id={{votacion.censo}}">{{votacion.nombregrupo}}</a>
                            </div>
                            <div class="col-sm-5">
                                {{votacion.enunciado}}     
                            </div>
                            <div class="col-sm-2">
                                <!--<progressbar class="progress-striped active" max="1" value="votacion.transcurrido" type="primary"></progressbar>-->
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


            <!-- Mostrar actividad reciente? -->


            <!-- Mostrar situaciones nuevas y destacadas -->


            <!-- Permitir introducir una nueva situación -->


            <!-- Gestión de grupos -->


            <!-- Proponer un objetivo -->

        </div> <!-- row -->

    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>
