<?php include dirname(__FILE__) . "/header.php"; ?>
<div ng-controller="controladornuevogrupo">
    <div class="container nocentrado">
        <div class="form-group">
            <label>Nombre del grupo</label>
            <input type="text" 
                   class="form-control" 
                   placeholder="escribe aquí el nombre del grupo..." 
                   ng-model="nuevogrupo.nombre"/>
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea class="form-control" placeholder="describe brevemente el tema del grupo..." ng-model="nuevogrupo.descripcion"></textarea>
        </div>
    </div>

    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="navbar-inner">
            <ul class="nav navbar-nav">
                <!-- un enlace por cada objeto del menu inferior -->
                <li ><a href="#" title="aceptar" ng-click="creargrupo()"><span class="glyphicon glyphicon-ok"></span></a></li>
                <li ><a href="todosgrupos.php" title="cancelar"><span class="glyphicon glyphicon-remove"></span></a></li>
            </ul>
        </div>
    </nav>
</div>
<?php include dirname(__FILE__) . "/footer.php"; ?>