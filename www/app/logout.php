<?php include dirname(__FILE__) . "/head.php"; 
include_once dirname(__FILE__) . "/nav_functions.php";
?>
<div ng-controller="controladorlogout">
    <div class="container principal">
        <h1>¡HASTA PRONTO!</h1>
        <a href=<?php echo direcciones::index ?>>Volver al inicio</a>
    </div>
</div>
<?php include dirname(__FILE__) . "/footer.php"; ?>
