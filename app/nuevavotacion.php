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
        <div class="form-group">
            <label>Acciones asociadas</label>
            <select class="form-control" ng-model="nuevavotacion.acciones">
                <option>Formar parte de grupo</option>
                <option>Dejar de formar parte de grupo</option>
                <option>Cambiar descripción del grupo</option>
                <option>Crear variable de grupo</option>
                <option>Modificar valor de variable de grupo</option>
                <option>Invalidar votación anterior</option>
                <option>Establecer regla</option>
            </select>
        </div>

        <!-- Formulario para establecer reglas -->
        <!-- Evento disparador: valor por encima de X, publicar comentario -->
        <!-- Acción: modificar valor -->

    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>