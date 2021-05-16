<?php include dirname(__FILE__) . "/header.php"; ?>
<div ng-controller="controladorprincipal">
    <div class="container principal" ng-cloak>
        <div class="jumbotron">
            <h1>¡Bienvenido a la democracia!</h1>
            <p><strong>Pure Democracy</strong> 
                es una aplicación para fomentar la 
                <strong>autogestión</strong> de 
                grupos de personas de una forma 
                <strong>transparente</strong>, 
                <strong>democrática</strong> y 
                <strong>libre de corrupción</strong>.</p>
        </div>
        <h2>Todo comienza con un grupo</h2>
        <a class="btn btn-success" href="nuevogrupo.php">Crear nuevo grupo</a>
        <a class="btn btn-primary" href="todosgrupos.php">Explorar grupos existentes</a>
    </div>
</div>
<?php include dirname(__FILE__) . "/footer.php"; ?>
