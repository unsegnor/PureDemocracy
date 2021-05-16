<?php include dirname(__FILE__) . "/header.php"; ?>

<?php
//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');

//Pasamos el parámetro al controlador
?>
<div ng-controller="controladorinfogrupo" ng-init="init(<?php echo $id ?>)">
    <div class="container principal" ng-cloak="ng-cloak">

        <!--Mostramos el nombre del grupo y el botón de ingresar / dar de baja -->
        <div class="row">
            <div class="col-sm-8">
                <h2>{{infogrupo.descripcion}}</h2>
                <!-- Mostramos todos los supergrupos -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Supergrupos
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
                           href="infogrupo.php?id={{supergrupo.idgrupo}}">
                            {{supergrupo.nombre}} 
                        </a>
                    </div>
                </div>

                <!-- Mostramos todos los subgrupos -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Subgrupos
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
                           href="infogrupo.php?id={{subgrupo.idgrupo}}">
                            {{subgrupo.nombre}} 
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Miembros</h3>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item" ng-repeat="miembro in miembros">
                            <span class="badge alert-info" ng-show="miembro.puntos_participacion > 0">{{miembro.puntos_participacion}}</span>
                            <span class="badge" ng-show="miembro.puntos_participacion == 0">{{miembro.puntos_participacion}}</span>
                            <span class="badge alert-danger" ng-show="miembro.puntos_participacion < 0">{{miembro.puntos_participacion}}</span>
                            {{miembro.nombre}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-default navbar-fixed-bottom" ng-cloak="ng-cloak">
            <div class="navbar-inner">
                <ul class="nav navbar-nav">
                    <!-- un enlace por cada objeto del menu inferior -->

                    <!--mostramos algunos botones en función de si somos o no miembros -->
                    <li ><a href="chatgrupo.php?id=<?php echo $id ?>" title="chat"><span class="glyphicon glyphicon-comment"></span> Discusión</a></li>
                    <li ><a href="votaciones.php?id=<?php echo $id ?>" title="ver votaciones"><span class="glyphicon glyphicon-envelope"></span> Votaciones</a></li>

                    <!-- si somos miembros mostramos las opciones de crear votación y solicitar baja -->
                    <!-- si somos seguidores mostramos las opciones de ingresar y solicitar baja -->
                    <!-- si no somos miembros ni seguidores mostramos las opciones de ingresar y seguir -->

                    <li ng-hide="miembroactual.voluntad >= 2"><a href="#" title="ingresar" ng-click="ingresarengrupo()"><span class="glyphicon glyphicon-log-in"></span> Ingresar</a></li>
                    <li ng-hide="miembroactual.voluntad >= 1"><a href="#" title="seguir" ng-click="seguirgrupo()"><span class="glyphicon glyphicon-bullhorn"></span> Seguir</a></li>
                    <li ng-show="miembroactual.voluntad >= 2 && miembroactual.puntos_participacion > 0"><a href="nuevavotacion.php?id=<?php echo $id ?>" title="nueva votación"><span class="glyphicon glyphicon-plus"><span class="glyphicon glyphicon-envelope"></span></span> Votación</a></li>
                    <li ng-show="miembroactual.voluntad >= 2 && miembroactual.puntos_participacion > 0"><a href="nuevosubgrupo.php?id=<?php echo $id ?>" title="nuevo subgrupo"><span class="glyphicon glyphicon-plus"></span> Subgrupo</a></li>
                    <li ng-show="miembroactual.voluntad >= 1"><a href="#" title="baja" ng-click="solicitarbaja()"><span class="glyphicon glyphicon-remove"></span> Baja</a></li>

                </ul>
            </div>
        </nav>
    </div>
    <?php include dirname(__FILE__) . "/footer.php"; ?>