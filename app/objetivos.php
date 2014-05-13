<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladorobjetivos">
    <div class="container principal">
        <div class="row">

            <!-- Proponer un objetivo -->
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Objetivos
                    </div>
                    <input class="form-control" type="text" 
                           ng-model="buscaobjetivo.descripcion"
                           ng-enter="addObjetivo(buscaobjetivo)"
                           placeholder="filtrar / añadir nuevo ...">
                    <div class="list-group">
                        <a class="list-group-item" 
                           ng-repeat="objetivo in objetivos| filter:buscaobjetivo"
                           href="detalleobjetivo.php?id={{objetivo.idobjetivo}}"> 
                            {{objetivo.descripcion}}</a>
                    </div>
                </div>
            </div>
        </div> <!-- row -->
    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>