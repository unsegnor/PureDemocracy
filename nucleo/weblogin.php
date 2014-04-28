<!DOCTYPE html>

<link rel='stylesheet' type='text/css' href='../media/css/loginb.css'>
<html lang="es" ng-app>
    <head><?php include dirname(__FILE__) . "/../include/head.php" ?>
        <script src="controlador.js"></script>
    </head>
    <body ng-controller="inicio" ng-init="init()">
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Pure Democracy</a>
                </div>
                <form class="navbar-form navbar-right" role="form">
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
                            <label class="sr-only">Nuevo usuario</label>
                            <input type="text" class="form-control" placeholder="Nuevo usuario">
                        </div>
                        <div class="form-group">
                            <label class="sr-only">Tu correo electrónico</label>
                            <input type="email" class="form-control" placeholder="Tu correo electrónico">
                        </div>
                        <div class="form-group">
                            <label class="sr-only">Vuelve a escribir tu correo</label>
                            <input type="email" class="form-control" placeholder="Vuelve a escribir tu correo">
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
    </body>
</html>