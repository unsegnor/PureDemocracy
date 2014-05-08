<?php include dirname(__FILE__) . "./head.php"; ?>

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Pure Democracy</a>
        </div>
        <form class="navbar-form navbar-right hidden-xs" role="form">
            <div class="form-group">
                <label class="sr-only">Usuario</label>
                <input type="text" class="form-control" placeholder="usuario">
            </div>
            <div class="form-group">
                <label class="sr-only">Contraseña</label>
                <input type="password" class="form-control" placeholder="contraseña">
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
</div>

<div class="container principal">
    <form class="form-inline visible-xs" role="form">
        <div class="form-group">
            <label class="sr-only">Usuario</label>
            <input type="text" class="form-control" placeholder="usuario">
        </div>
        <div class="form-group">
            <label class="sr-only">Contraseña</label>
            <input type="password" class="form-control" placeholder="contraseña">
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
    <div class="row">
        <div class="col-sm-6">
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
        </div>
        <div class="col-sm-6">
            <h3>Regístrate</h3>
            <form>
                <div class="form-group">
                    <label class="sr-only">Nombre</label>
                    <input type="text" class="form-control" placeholder="Nombre">
                </div>
                <div class="form-group">
                    <label class="sr-only">Tu correo electrónico</label>
                    <input type="email" class="form-control" placeholder="Tu correo electrónico">
                </div>
                <div class="form-group">
                    <label class="sr-only">Contraseña</label>
                    <input type="password" class="form-control" placeholder="Contraseña">
                </div>
                <button type="submit" class="btn btn-success">Registrar nuevo usuario</button>
            </form>
        </div>
    </div><!-- row -->
</div> <!-- /container -->

<?php include dirname(__FILE__) . "./footer.php"; ?>