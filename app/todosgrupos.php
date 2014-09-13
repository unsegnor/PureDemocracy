<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladortodosgrupos">
    <div class="container principal" ng-cloak>
        <!-- Campo para filtrar -->
        <input type="text" class="form-control" ng-model="filtro.nombre"/>

        <div class="list-group">
            <a ng-repeat="grupo in grupos| filter:filtro" 
               class="list-group-item" 
               href="infogrupo.php?id={{grupo.idgrupo}}">{{grupo.nombre}}</a>
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