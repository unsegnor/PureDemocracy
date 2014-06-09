<div class="modal-header">
    <h3 class="modal-title">Apoyaré esta decisión si se aprueban las siguientes:</h3>
</div>
<div class="modal-body">
    <table class="table table-condensed">
        <tr ng-repeat="grupo_y in grupos_y">
            <td>
                <span ng-repeat="enunciado in grupo_y.enunciados">

                    <input type="text" 
                           class="form-control" 
                           ng-model="enunciado.nombre">

                </span>
                <button class="btn btn-default"
                        ng-click="addEnunciado(grupo_y)"
                        > Y </button>
            </td>
        </tr>
        <tr>
            <td>
                <button class="btn btn-default"
                        ng-click="addGrupoY()"
                        > Ó </button>
            </td>
        </tr>
    </table>
    {{grupos_y}}
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
    <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
</div>
