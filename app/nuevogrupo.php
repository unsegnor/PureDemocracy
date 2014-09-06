<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladornuevogrupo">
    <div class="container principal">
        
        <input type="text" class="form-control" placeholder="nombre" ng-model="nuevogrupo.nombre"/>
        <textarea class="form-control" placeholder="descripción" ng-model="nuevogrupo.descripcion"></textarea>

    </div>
    
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="navbar-inner">
            <ul class="nav navbar-nav">
                <!-- un enlace por cada objeto del menu inferior -->
                <li ><a href="#" title="aceptar" ng-click="creargrupo()"><span class="glyphicon glyphicon-ok"></span></a></li>
                <li ><a href="todosgrupos.php?id=<?php echo $id ?>" title="cancelar"><span class="glyphicon glyphicon-remove"></span></a></li>
            </ul>
        </div>
    </nav>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>