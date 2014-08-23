<?php include dirname(__FILE__) . "./head.php"; ?>
<div ng-controller="controladorlogin">
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Pure Democracy</a>
            </div>
            <form class="navbar-form navbar-right hidden-xs" role="form">
                <div class="form-group">
                    <label class="sr-only">Email</label>
                    <input type="text" class="form-control" placeholder="correo electrónico"
                           ng-model="login.email" required>
                </div>
                <div class="form-group">
                    <label class="sr-only">Contraseña</label>
                    <input type="password" class="form-control" placeholder="contraseña"
                           ng-model="login.pass" required>
                </div>
                <button type="submit" class="btn btn-primary"
                        ng-click="dologin(login.email, login.pass)">Entrar</button>
                <div class="btn btn-default">
                    <fb:login-button scope="public_profile,email" onlogin="redirect('principal.php');" class>
                    </fb:login-button>
                </div>
            </form>

        </div>
    </div>

    <div class="container principal">
        <form class="navbar-form navbar-right visible-xs" role="form">
            <div class="form-group">
                <label class="sr-only">Email</label>
                <input type="text" class="form-control" placeholder="correo electrónico"
                       ng-model="login.email" required>
            </div>
            <div class="form-group">
                <label class="sr-only">Contraseña</label>
                <input type="password" class="form-control" placeholder="contraseña"
                       ng-model="login.pass" required>
            </div>
            <button type="submit" class="btn btn-primary"
                    ng-click="dologin(login.email, login.pass)">Entrar</button>
            <div class="btn btn-default">
                <fb:login-button scope="public_profile,email" onlogin="redirect('principal.php');" class>
                </fb:login-button>
            </div>
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
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="sr-only">Nombre</label>
                                <input type="text" class="form-control" placeholder="Nombre"
                                       ng-model="newuser.name" required>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="sr-only">Apellidos</label>
                                <input type="text" class="form-control" placeholder="Apellidos"
                                       ng-model="newuser.surname" required>
                            </div>
                        </div>
                    </div><!-- row -->
                    <div class="form-group">
                        <label class="sr-only">Tu correo electrónico</label>
                        <input type="email" class="form-control" placeholder="Tu correo electrónico"
                               ng-model="newuser.email" required>
                    </div>
                    <div class="form-group">
                        <label class="sr-only">Contraseña</label>
                        <input type="password" class="form-control" placeholder="Contraseña"
                               ng-model="newuser.pass" required>
                    </div>
                    <button type="submit" class="btn btn-success"
                            ng-click="registernew(newuser.name, newuser.surname, newuser.email, newuser.pass)"
                            >Registrar nuevo usuario</button>
                    <div class="btn btn-default">
                        <fb:login-button scope="public_profile,email" onlogin="redirect('principal.php');" class>
                        </fb:login-button>
                    </div>
                </form>
            </div>
        </div><!-- row -->
    </div> <!-- /container -->
</div>
<?php include dirname(__FILE__) . "./footer.php"; ?>