<?php include dirname(__FILE__) . "./header.php"; ?>
<div ng-controller="controladorgrupos">
    <div class="container principal">
        
        <div class="panel panel-success">
            
            <div class="panel-heading">
                Mis grupos
            </div>
            
            <!-- Listar mis grupos -->
            
            
        </div>
        
        <div class="panel panel-primary">
            
            <div class="panel-heading">
                Grupos
            </div>
            
            <!-- Añadir / Filtrar grupos -->
            <input type="text" class="form-control">
            <!-- Listar mis grupos -->
            <div class="list-group">
                
                <a class="list-group-item" ng-repeat="grupo in grupos"
                   href="detallegrupo.php?id={{grupo.idgrupo}}">
                    {{grupo.nombre}}
                </a>
                
            </div>
            
        </div>
        
    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>