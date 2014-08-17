
<?php
include_once dirname(__FILE__) . "./nav_functions.php";
include dirname(__FILE__) . "./head.php";
?>

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Pure Democracy</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <!--<li><a href="<?php echo direcciones::index ?>">Inicio</a></li>-->
                <li><a title="Notificaciones" href="<?php echo direcciones::index ?>"><span class="glyphicon glyphicon-bell"> Notificaciones</span></a></li>
                <!--<li><a href="<?php echo direcciones::objetivos ?>">Objetivos</a></li>-->
                <li><a title="Grupos" href="<?php echo direcciones::grupos ?>"><span class="glyphicon glyphicon-record"> Grupos</span></a></li>
                <li><a title="Mi Perfil" href="<?php echo direcciones::perfil ?>"><span class="glyphicon glyphicon-user"> Perfil</span></a></li>
                <!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cosas <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo direcciones::index ?>">Coso</a></li>
                        <li><a href="<?php echo direcciones::index ?>">Cosocoso</a></li>
                    </ul>
                </li> -->
                <li><a title="Salir" href="<?php echo direcciones::logout ?>"><span class="glyphicon glyphicon-log-out"> Salir</span></a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>





