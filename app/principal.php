<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladorprincipal">
    <div class="container principal">
        <div class="page-header"><h3>Hola {{session.nombre}}!</h3></div>

        <div class="row">
            <!-- Mostrar las notificaciones/sugerencias remuneradas del sistema en la repartición del trabajo -->


            <!-- Mostrar actividad reciente? -->


            <!-- Mostrar situaciones nuevas y destacadas -->


            <!-- Permitir introducir una nueva situación -->


            <!-- Gestión de grupos -->


            <!-- Proponer un objetivo -->
            <div class="col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Propuestas
                    </div>
                    <div class="panel-body">
                        Propuestas pendientes de aprobación
                    </div>
                    <div class="list-group">
                        <div class="list-group-item-success">
                                        <input type="text"
                                               class="form-control"
                                               ng-model="nuevapropuesta.descripcion"
                                               typeahead-on-select="addPropuesta($item)" 
                                               typeahead="p.id_proveedor as (p.nombre + ' (' + p.n_reparaciones + ' asignadas hoy)') for p in proveedores | filter:$viewValue"
                                               typeahead-editable="true"/>
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- row -->

    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>
