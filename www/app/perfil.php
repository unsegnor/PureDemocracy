<?php include dirname(__FILE__) . "/header.php"; ?>
<div ng-controller="controladorperfil">
    <div class="container principal">

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="sr-only">Nombre</label>
                    <input type="text" class="form-control" placeholder="Nombre"
                           ng-model="user.nombre"
                           ng-enter="setUsuarioActual()">
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <label class="sr-only">Apellidos</label>
                    <input type="text" class="form-control" placeholder="Apellidos"
                           ng-model="user.apellidos"
                           ng-enter="setUsuarioActual()">
                </div>
            </div>
        </div><!-- row -->
        <!--
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-default" ng-click="setUsuarioActual()">Guardar</button>
            </div>
        </div>-->
    </div>
</div>
<?php include dirname(__FILE__) . "/footer.php"; ?>