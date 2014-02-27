<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<!--<link rel='stylesheet' type='text/css' href='../media/css/login.css'>-->
<link rel='stylesheet' type='text/css' href='../media/css/loginb.css'>
<html>
    <head><?php include dirname(__FILE__) . "/../include/head.php" ?>
        </head>
    <body>
       <!-- <form method="post" action="login.php">
            <input type="text" name="login" placeholder="usuario">
            <input type="password" name="pass" placeholder="contraseña">
            <input type="submit" value="Entrar">
        </form>
    </body>
</html>-->

<!--<form id="login" method="post" action="login.php">
    <h1>Log In</h1>
    <fieldset id="inputs">
        <input id="username" name="login" type="text" placeholder="usuario" autofocus required>   
        <input id="password" name="pass" type="password" placeholder="contraseña" required>
    </fieldset>
    <fieldset id="actions">
        <input type="submit" id="submit" value="Entrar">
    </fieldset>
</form>
       -->
       <div class="container">

           <form class="form-signin" role="form" method="post" action="login.php">
        <h2 class="form-signin-heading">Identificación</h2>
        <input name="login" type="text" class="form-control" placeholder="usuario" required autofocus>
        <input name="pass" type="password" class="form-control" placeholder="contraseña" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Acceder</button>
      </form>

    </div> <!-- /container -->
