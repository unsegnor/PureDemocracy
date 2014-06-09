<?php include dirname(__FILE__) . "./header.php"; ?>

<?php
//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');

//Pasamos el parámetro al controlador
?>
<div ng-controller="controladordetallegrupo" ng-init="init(<?php echo $id ?>)">
    <div class="container principal">

        <!--Mostramos el nombre del grupo y el botón de ingresar / dar de baja -->
        <div class="row">
            <div class="col-sm-8">
                <h2>{{grupo.nombre}}</h2>
            </div>
            <div class="col-sm-2">
                <span class="glyphicon glyphicon-user">{{grupo.nmiembros}}</span>
                <div ng-hide="grupo.nmiembros >= 3">
                    El grupo no tiene suficientes miembros
                </div>
            </div>
            <div class="col-sm-2">
                <div ng-hide="grupo.es_miembro || grupo.es_nato">
                    <label ng-hide="grupo.es_nato">Aún no eres miembro</label>
                    <label ng-show="grupo.es_nato">Eres miembro nato</label>
                    <!-- Si no somos miembros mostramos el botón de solicitar admisión -->
                    <button class="btn btn-success" ng-click="solicitarIngreso()">Ingresar</button>
                </div>
                <div ng-hide="grupo.es_miembro == 0">
                    <!-- Si somos miembros mostramos el botón de abandonar grupo -->
                    <label>Ya eres miembro</label>
                    <button class="btn btn-danger" ng-click="solicitarBaja()">Solicitar baja</button>    
                </div>
            </div>
        </div>


        <!-- Mostramos todos los supergrupos -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Forma parte de
            </div>
            <!-- Añadir subgrupo -->
            <input type="text"
                   ng-show="activo()"
                   class="form-control"
                   ng-model="nuevosupergrupo.nombre" 
                   typeahead="g as (g.nombre + '_' +  g.idgrupo) for g in grupos | filter:{nombre:$viewValue}"
                   typeahead-editable="true"
                   ng-enter="addSuperGrupo(nuevosupergrupo.nombre, $item)"
                   placeholder="filtrar/añadir..."/>
            <div class="list-group">
                <a class="list-group-item"
                   ng-repeat="supergrupo in supergrupos| filter:nuevosupergrupo"
                   href="detallegrupo.php?id={{supergrupo.idgrupo}}">
                    {{supergrupo.nombre}} 
                </a>
            </div>
        </div>

        <!-- Mostramos todos los subgrupos -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Compuesto por
            </div>
            <!-- Añadir subgrupo -->
            <input type="text"
                   ng-show="activo()"
                   class="form-control" 
                   placeholder="filtrar/añadir..."
                   ng-model="filtro.nombre"
                   ng-enter="addSubGrupo(filtro.nombre)">
            <div class="list-group">
                <a class="list-group-item" 
                   ng-repeat="subgrupo in subgrupos| filter: filtro"
                   href="detallegrupo.php?id={{subgrupo.idgrupo}}">
                    {{subgrupo.nombre}} 
                </a>
            </div>
        </div>


        <!-- Proponer unión a supergrupo -->

        <!-- Existente -->

        <!-- Crear votación, hacer pregunta -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Votaciones en curso
            </div>

            <!-- Generar decisión -->
            <input type="text"
                   ng-show="activo()"
                   class="form-control" 
                   ng-model="nuevoenunciado.enunciado"
                   placeholder="Pregunta al grupo"
                   ng-enter="addPregunta(nuevoenunciado.enunciado)">

            <div class="list-group">
                <!-- Listamos los enunciados pendientes del grupo -->
                <div class="list-group-item"
                     ng-repeat="votacion in votaciones| filter:{'finalizada':0}">
                    <div class="row">
                        <div class="col-sm-8">
                            {{votacion.enunciado}}     
                        </div>
                        <div class="col-sm-2">
                            <progressbar class="progress-striped active" max="1" value="votacion.transcurrido" type="primary"></progressbar>
                        </div>
                        <div class="col-sm-2">
                            <div class="btn-group">
                                <button class="btn btn-success"
                                        ng-click="votar(votacion.idvotacionsnd, 3)"
                                        ng-disabled="objetivo.voto == 3"><span class="glyphicon glyphicon-ok-sign"></span></button>
                                <button class="btn btn-warning"
                                        ng-click="votar(votacion.idvotacionsnd, 2)"
                                        ng-disabled="objetivo.voto == 2"><span class="glyphicon glyphicon-question-sign"></span></button>
                                <button class="btn btn-danger"
                                        ng-click="votar(votacion.idvotacionsnd, 1)"
                                        ng-disabled="objetivo.voto == 1"><span class="glyphicon glyphicon-remove-sign"></span></button>
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
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>