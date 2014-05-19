<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladorobjetivos">
    <div class="container principal">
        <div class="row">

            <!-- Proponer un objetivo -->
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Objetivos
                    </div>
                    <input class="form-control" type="text" 
                           ng-model="buscaobjetivo.descripcion"
                           ng-enter="addObjetivo(buscaobjetivo)"
                           placeholder="filtrar / añadir nuevo ...">
                    <div class="list-group">
                        <a class="list-group-item"
                           ng-repeat="objetivo in objetivos| filter:buscaobjetivo">
                            <div class="row">
                                <!-- Descripción del objetivo -->
                                <div class="col-sm-7"
                                     href="detalleobjetivo.php?id={{objetivo.idobjetivo}}">
                                    {{objetivo.descripcion}}
                                </div>
                                <!-- Acciones -->
                                <div class="col-sm-3">
                                    <div  ng-show="objetivo.estado_objetivo_idestado_objetivo == 1">
                                        <div class="btn-group">
                                            <button class="btn btn-success"
                                                    ng-click="votar(objetivo, 3)"
                                                    ng-disabled="objetivo.voto == 3"><span class="glyphicon glyphicon-ok-sign"></span></button>
                                            <button class="btn btn-warning"
                                                    ng-click="votar(objetivo, 2)"
                                                    ng-disabled="objetivo.voto == 2"><span class="glyphicon glyphicon-question-sign"></span></button>
                                            <button class="btn btn-danger"
                                                    ng-click="votar(objetivo, 1)"
                                                    ng-disabled="objetivo.voto == 1"><span class="glyphicon glyphicon-remove-sign"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Estado -->
                                <div class="col-sm-2">
                                    <label>{{objetivo.nombre_estado}} {{objetivo.nombre_proceso}}</label>
                                    <progressbar class="progress-striped active" max="objetivo.progreso_maximo" value="objetivo.progreso_actual" type="success">
                                        <i>{{objetivo.progreso_actual}}/{{objetivo.progreso_maximo}}</i>
                                    </progressbar>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div> <!-- row -->
    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>