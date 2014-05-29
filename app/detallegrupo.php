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
                <div ng-hide="grupo.es_miembro == 1">
                    <label>Aún no eres miembro</label>
                    <!-- Si no somos miembros mostramos el botón de solicitar admisión -->
                    <button class="btn btn-success" ng-click="solicitarIngreso()">Ingresar</button>
                </div>
                <div ng-hide="grupo.es_miembro == 0">
                    <!-- Si somos miembros mostramos el botón de abandonar grupo -->
                    <label>Ya eres miembro</label>
                    <button class="btn btn-danger" ng-click="solicitarBaja()">Darme de baja</button>    
                </div>
            </div>
        </div>


        <!-- Mostramos todos los supergrupos -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Supergrupos
            </div>
            <!-- Añadir subgrupo -->
            <input type="text" class="form-control" placeholder="filtrar/añadir...">
            <div class="list-group">
                <a class="list-group-item"
                   ng-repeat="supergrupo in supergrupos"
                   href="detallegrupo.php?id={{supergrupo.idgrupo}}">
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
                   class="form-control" 
                   placeholder="filtrar/añadir..."
                   ng-model="filtro.nombre"
                   ng-enter="addSubGrupo(filtro.nombre)">
            <div class="list-group">
                <a class="list-group-item" 
                   ng-repeat="subgrupo in subgrupos | filter: filtro"
                   href="detallegrupo.php?id={{subgrupo.idgrupo}}">
                    {{subgrupo.nombre}} 
                </a>
            </div>
        </div>


        <!-- Proponer unión a supergrupo -->


    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>