<?php include dirname(__FILE__) . "./header.php"; ?>

<?php
//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');

//Pasamos el parámetro al controlador
?>
<div ng-controller="controladordetallegrupo" ng-init="init(<?php echo $id ?>)">
    <div class="container principal">

        <!--Mostramos el nombre del grupo en grande -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-10">
                    <h2>{{grupo.nombre}}</h2>
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
        </div>


    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>