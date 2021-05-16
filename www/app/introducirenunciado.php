<div class="modal-header">
    <h3 class="modal-title">Apoyaré esta decisión si se aprueban las siguientes:</h3>
</div>
<div class="modal-body">
    <div class="list-group" ng-repeat="grupo_y in grupos_y">
        <div class="list-group-item"
             ng-repeat="enunciado in grupo_y.enunciados">
            <div class="input-group">
                <input type="text" 
                       class="form-control" 
                       ng-model="enunciado.nombre">
                <span class="input-group-btn">
                    <button class="btn btn-default"
                            ng-click="addEnunciado(grupo_y)"
                            > Y </button>
                </span>
            </div>
        </div>
        <a class="btn btn-default list-group-item"
           ng-click="addGrupoY()"
           > Ó </a>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
    <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
</div>
