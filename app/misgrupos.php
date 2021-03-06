<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladormisgrupos">
    <div class="container principal" ng-cloak>
        <div class="row" ng-show="misGrupos.length > 0">
            <div class="col-sm-4">
                <a href="infogrupo.php?id={{migrupo.idgrupo}}"
                   class="thumbnail" ng-repeat="migrupo in misGrupos" ng-if="($index) % 3 == 0">
                    <img class="peque" src="img/grupo.jpg" alt="...">
                    <div class="caption">
                        <h3>{{migrupo.nombre}}</h3>
                    </div>
                </a>
            </div>
            <div class="col-sm-4">
                <a href="infogrupo.php?id={{migrupo.idgrupo}}"
                   class="thumbnail" ng-repeat="migrupo in misGrupos" ng-if="($index) % 3 == 1">
                    <img class="peque" src="img/grupo.jpg" alt="...">
                    <div class="caption">
                        <h3>{{migrupo.nombre}}</h3>
                    </div>
                </a>
            </div>
            <div class="col-sm-4">
                <a href="infogrupo.php?id={{migrupo.idgrupo}}"
                   class="thumbnail" ng-repeat="migrupo in misGrupos" ng-if="($index) % 3 == 2">
                    <img class="peque" src="img/grupo.jpg" alt="...">
                    <div class="caption">
                        <h3>{{migrupo.nombre}}</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="navbar-inner">
            <ul class="nav navbar-nav">
                <li ><a href="misgrupos.php" title="mis grupos"><span class="glyphicon glyphicon-user"></span></a></li>
                <li ><a href="todosgrupos.php" title="todos"><span class="glyphicon glyphicon-search"></span></a></li>
                <li ><a href="nuevogrupo.php" title="nuevo grupo"><span class="glyphicon glyphicon-plus"></span></a></li>
            </ul>
        </div>
    </nav>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>