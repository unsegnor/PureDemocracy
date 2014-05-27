<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladorgrupos">
    <div class="container principal">
        
        <div class="panel panel-success">
            
            <div class="panel-heading">
                Mis grupos
            </div>
            
            <!-- Listar mis grupos -->
            <div class="list-group">
                
                <a class="list-group-item" ng-repeat="migrupo in misGrupos"
                   href="detallegrupo.php?id={{migrupo.idgrupo}}">
                    {{migrupo.nombre}}
                </a>
                
            </div>
            
        </div>
        
        <div class="panel panel-primary">
            
            <div class="panel-heading">
                Todos los grupos
            </div>
            
            <!-- Añadir / Filtrar grupos -->
            <input type="text" 
                   class="form-control" 
                   ng-model="filtro.nombre" 
                   ng-enter="addGrupo(filtro.nombre)"
                   placeholder="filtrar/añadir ...">
            <!-- Listar grupos -->
            <div class="list-group">
                
                <a class="list-group-item" ng-repeat="grupo in grupos | filter:filtro"
                   href="detallegrupo.php?id={{grupo.idgrupo}}">
                    {{grupo.nombre}}
                </a>
                
            </div>
            
        </div>
        
    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>