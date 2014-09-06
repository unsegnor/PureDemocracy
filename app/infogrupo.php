<?php include dirname(__FILE__) . "./header.php"; ?>

<?php
//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');

//Pasamos el parámetro al controlador
?>
<div ng-controller="controladorinfogrupo" ng-init="init(<?php echo $id ?>)">
    <div class="container principal">

        <!--Mostramos el nombre del grupo y el botón de ingresar / dar de baja -->
        <div class="row">
            <div class="col-sm-8">
                <h2>{{infogrupo.descripcion}}</h2>
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

    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="navbar-inner">
            <ul class="nav navbar-nav">
                <!-- un enlace por cada objeto del menu inferior -->
                <li ><a href="chatgrupo.php?id=<?php echo $id ?>" title="chat"><span class="glyphicon glyphicon-comment"></span></a></li>
                <li ><a href="votaciones.php?id=<?php echo $id ?>" title="ver votaciones"><span class="glyphicon glyphicon-search"></span></a></li>
                <li ><a href="nuevavotacion.php?id=<?php echo $id ?>" title="nueva votación"><span class="glyphicon glyphicon-plus"></span></a></li>
                <li ><a href="infogrupo.php?id=<?php echo $id ?>" title="información de grupo"><span class="glyphicon glyphicon-info-sign"></span></a></li>
            </ul>
        </div>
    </nav>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>