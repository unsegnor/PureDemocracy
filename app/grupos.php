<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladorgrupos">
    <div class="container principal">

        <div class="panel panel-success">

            <div class="panel-heading">
                Mis grupos
            </div>

            <!-- Listar mis grupos -->
            <!--
            <div class="list-group">
                
                <a class="list-group-item" ng-repeat="migrupo in misGrupos"
                   href="detallegrupo.php?id={{migrupo.idgrupo}}">
                    {{migrupo.nombre}}
                </a>
                
                
            </div>
            -->

        </div>

        <div class="row" ng-show="misGrupos.length > 0">
            <div class="col-sm-4">
                <a class="thumbnail">
                    <img class="peque" src="img/add.jpg" alt="...">
                    <div class="caption">
                        <h3>Añadir nuevo grupo</h3>
                        <!--<p></p>
                        <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                        -->
                    </div>
                </a>
                <a href="detallegrupo.php?id={{migrupo.idgrupo}}"
                   class="thumbnail" ng-repeat="migrupo in misGrupos" ng-if="($index - 2) % 3 == 0">
                    <img class="peque" src="img/grupo.jpg" alt="...">
                    <div class="caption">
                        <h3>{{migrupo.nombre}}</h3>
                    </div>
                </a>
            </div>
            <div class="col-sm-4">
                <a ng-show="misGrupos.length > 0" href="detallegrupo.php?id={{misGrupos[0].idgrupo}}"
                   class="thumbnail">
                    <img class="peque" src="img/grupo.jpg" alt="img/grupo.jpg">
                    <div class="caption">
                        <h3>{{misGrupos[0].nombre}}</h3>
                    </div>
                </a>
                <a href="detallegrupo.php?id={{migrupo.idgrupo}}"
                   class="thumbnail" ng-repeat="migrupo in misGrupos" ng-if="($index - 2) % 3 == 1">
                    <img class="peque" src="img/grupo.jpg" alt="...">
                    <div class="caption">
                        <h3>{{migrupo.nombre}}</h3>
                    </div>
                </a>
            </div>
            <div class="col-sm-4">
                <a ng-show="misGrupos.length > 1" href="detallegrupo.php?id={{misGrupos[1].idgrupo}}"
                   class="thumbnail">
                    <img class="peque" src="img/grupo.jpg" alt="img/grupo.jpg">
                    <div class="caption">
                        <h3>{{misGrupos[1].nombre}}</h3>
                    </div>
                </a>
                <a href="detallegrupo.php?id={{migrupo.idgrupo}}"
                   class="thumbnail" ng-repeat="migrupo in misGrupos" ng-if="($index - 2) % 3 == 2">
                    <img class="peque" src="img/grupo.jpg" alt="...">
                    <div class="caption">
                        <h3>{{migrupo.nombre}}</h3>
                    </div>
                </a>
            </div>
        </div>

        <div class="panel panel-primary">

            <div class="panel-heading">
                Todos los grupos
            </div>

            <!-- Añadir / Filtrar grupos -->
            <input type="text" 
                   class="form-control" 
                   ng-model="filtro.nombre" 
                   ng-enter="addGrupo(filtro.nombre)"
                   placeholder="filtrar/añadir ...">
            <!-- Listar grupos -->
            <div class="list-group">

                <a class="list-group-item" ng-repeat="grupo in grupos| filter:filtro"
                   href="detallegrupo.php?id={{grupo.idgrupo}}">
                    {{grupo.nombre}}
                </a>

            </div>

        </div>

    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>