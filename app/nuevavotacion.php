<?php
include dirname(__FILE__) . "./header.php";
//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');
?>
<div ng-controller="controladornuevavotacion" ng-init="init(<?php echo $id ?>)">
    <div class="container principal">
        <div class="form-group">
            <label>Nombre de la votación</label>
            <input type="text" class="form-control" ng-model="nuevavotacion.nombre" placeholder="Nombre">
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea class="form-control" ng-model="nuevavotacion.descripcion" placeholder="Descripción"></textarea>
        </div>
        <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" ng-model="nuevavotacion.tipo">
                <option value="0" selected="selected">Sí/No/Depende</option>
                <option value="1">Priorización de opciones</option>
                <option value="2">Estimación</option>
                <option value="3">Respuesta libre</option>
            </select>
        </div>

        <div class="form-group" ng-show="nuevavotacion.tipo == 0">
            <label>Acciones asociadas</label>
            <ul class="list-group">
                <li class="list-group-item list-group-item-success" ng-repeat="accion in nuevavotacion.acciones">{{accion.descripcion}}</li>
            </ul>
        </div>

        <div ng-show="nuevavotacion.tipo == 0" class="form-group">
            <label>Nueva acción</label>
            <select class="form-control" ng-model="nuevaaccion.tipo" ng-options="a as a.descripcion for a in tiposacciones">
            </select>

            <!-- Para cada tipo de acción añadimos campos para los parámetros -->
            <div ng-show="nuevaaccion.tipo.idaccionsys == 1" class="form-group">
                <input type="text"
                       class="form-control"
                       ng-model="nuevaaccion.grupo_in" 
                       typeahead="gi as gi.nombre for gi in grupos | filter:{nombre:$viewValue}"
                       typeahead-editable="false"
                       placeholder="buscar grupo..."/>
            </div>

            <!-- Elegir grupo para dejar de pertenecer -->
            <div ng-show="nuevaaccion.tipo.idaccionsys == 2" class="form-group">
                <input type="text"
                       class="form-control"
                       ng-model="nuevaaccion.grupo_out" 
                       typeahead="go as go.nombre for go in supergrupos | filter:{nombre:$viewValue}"
                       typeahead-editable="false"
                       placeholder="buscar supergrupo..."/>
            </div>

            <!-- Determinar texto de la nueva descripción -->
            <div ng-show="nuevaaccion.tipo.idaccionsys == 3" class="form-group">
                <textarea class="form-control"
                          ng-model="nuevaaccion.nuevadescripcion"></textarea>
            </div>

            <!-- Determinar nombre e inicialización de la nueva variable -->
            <div ng-show="nuevaaccion.tipo.idaccionsys == 4" class="form-group">
                <input type="text" 
                       class="form-control"
                       ng-model="nuevaaccion.nombrevariable"
                       placeholder="Nombre de la variable"/>
                <input
                    type="text"
                    class="form-control"
                    ng-model="nuevaaccion.valorinicialvariable"
                    placeholder="Valor inicial"/>
            </div>

            <!-- Determinar nombre y nuevo valor de la variable -->
            <div ng-show="nuevaaccion.tipo.idaccionsys == 5" class="form-group">
                <input type="text" 
                       class="form-control"
                       ng-model="nuevaaccion.nombrevariableamodificar"
                       placeholder="Nombre de la variable"/>
                <input
                    type="text"
                    class="form-control"
                    ng-model="nuevaaccion.valorvariable"
                    placeholder="Valor inicial"/>
            </div>

            <!-- Determinar votación a invalidar -->
            <div ng-show="nuevaaccion.tipo.idaccionsys == 7" class="form-group">
                <input
                    type="text"
                    class="form-control"
                    ng-model="nuevaaccion.votacionainvalidar"
                    typeahead="vi as vi.enunciado for vi in votaciones | filter:{enunciado:$viewValue}"
                    typeahead-editable="false"
                    placeholder="buscar votación..."/>
            </div>
            
            <!-- Añadir acción -->
            <button class="btn btn-primary" ng-click="addAccion()">Añadir acción</button>
        </div>

        <!-- Formulario para establecer reglas -->
        <!-- Evento disparador: valor por encima de X, publicar comentario -->
        <!-- Acción: modificar valor -->

    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>