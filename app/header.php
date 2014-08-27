
<?php
include_once dirname(__FILE__) . "./nav_functions.php";
include dirname(__FILE__) . "./head.php";
?>

<div class="navmenu navmenu-default navmenu-fixed-left offcanvas">
    <a class="navmenu-brand" href="#">Pure Democracy</a>
    <ul class="nav navmenu-nav">
        <li><a title="Notificaciones" href="<?php echo direcciones::index ?>"><span class="glyphicon glyphicon-bell"> Notificaciones</span></a></li>
        <li><a title="Grupos" href="<?php echo direcciones::grupos ?>"><span class="glyphicon glyphicon-record"> Grupos</span></a></li>
        <li><a title="Mi Perfil" href="<?php echo direcciones::perfil ?>"><span class="glyphicon glyphicon-user"> Perfil</span></a></li>
        <li><a title="Salir" href="<?php echo direcciones::logout ?>"><span class="glyphicon glyphicon-log-out"> Salir</span></a></li>
    </ul>
    <ul class="nav navmenu-nav">
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
            <ul class="dropdown-menu navmenu-nav">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
            </ul>
        </li>
    </ul>
</div>

<div class="navbar navbar-default navbar-fixed-top">
    <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".navmenu" data-canvas="body">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <div class="nav navbar-nav">
        <ol class="breadcrumb list-inline mio">
            <li><a href="#">Home</a></li>
            <li><a href="#">Library</a></li>
            <li class="active">Data</li>
        </ol>
    </div>
</div>
<!--
<nav class="navbar navbar-default navbar-fixed-bottom">
  <div class="navbar-inner">
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Home</a></li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
    </ul>
  </div>
</nav>
-->



