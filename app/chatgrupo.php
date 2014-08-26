<?php include dirname(__FILE__) . "./header.php"; 

//Leemos los parámetros
$id = filter_input(INPUT_GET, 'id');

?>
<div ng-controller="controladorchatgrupo">
    <div class="container principal">
        Bienvenido al chat del grupo <?php echo $id ?>
    </div>
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>