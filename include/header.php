<?php
include_once dirname(__FILE__) . "/../nucleo/controlador.php";

//Mostrar errores si los hay
if (isset($_SESSION['errores'])) {
    echo $_SESSION['errores'];
    unset($_SESSION['errores']);
}

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
                <li><a href="<?php echo direcciones::index ?>">Inicio</a></li>                            
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuario <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo direcciones::logout ?>">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>





<!-- Antiguo menú guay

<nav>
    <ul>
        <li>
            <a href="<?php echo direcciones::index ?>">Inicio</a>
        </li>
        <li>
            <a href="#">Expedientes</a>
            <ul>
                <li><a href="<?php echo direcciones::nuevo_expediente ?>">Nuevo</a></li>
                <li><a href="<?php echo direcciones::buscar_expedientes ?>">Buscar</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Entidades</a>
            <ul>
                <li><a href="<?php echo direcciones::entidades ?>">Clientes</a></li>
                <li><a href="<?php echo direcciones::aseguradoras ?>">Compañías</a></li>
                <li><a href="<?php echo direcciones::proveedores ?>">Proveedores</a></li>
                <li><a href="<?php echo direcciones::peritos ?>">Peritos</a></li>
                <li><a href="<?php echo direcciones::index ?>">Agentes</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Configuración</a>
            <ul>
                <li><a href="<?php echo direcciones::usuarios ?>">Gestión de Usuarios</a></li>
                <li><a href="<?php echo direcciones::grupos ?>">Gestión de Grupos</a></li>
                <li><a href="<?php echo direcciones::tipos_siniestro ?>">Tipos de siniestro</a></li>
                <li><a href="<?php echo direcciones::configuracion ?>">Parámetros</a></li>
                <li><a href="<?php echo direcciones::poblaciones ?>">Poblaciones</a></li>

            </ul>
        </li>
        <li>
            <a href="#">Listados</a>
            <ul>
                <li><a href="<?php echo direcciones::expedientes ?>">Expedientes</a></li>
                <li><a href="<?php echo direcciones::facturas ?>">Facturas</a></li>
                <li><a href="<?php echo direcciones::reparaciones ?>">Reparaciones</a></li>
            </ul>
        </li>
        <li><a href="<?php echo direcciones::facturas ?>">Factura</a></li>
        <li>
            <a href="#">Estadísticas</a>
            <ul>
                <li><a href="<?php echo direcciones::index ?>">Estadística1</a></li>
                <li><a href="<?php echo direcciones::index ?>">Estadística2</a></li>
                <li><a href="<?php echo direcciones::index ?>">Estadística3</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Usuario</a>
            <ul>
                <li><a href="<?php echo direcciones::logout ?>">Cerrar sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>
<br><br><br><br><br><br>-->




