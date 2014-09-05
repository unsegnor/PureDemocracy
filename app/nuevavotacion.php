<?php
include dirname(__FILE__) . "./header.php";
//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');
?>
<div ng-controller="controladornuevavotacion" ng-init="init(<?php echo $id ?>)">
    <div class="container principal">
        Añadir votación
    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>