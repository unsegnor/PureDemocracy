<!DOCTYPE html>
<!--
Mostramos el listado de usuarios
-->
<html>
    <head><?php include dirname(__FILE__) . "/../include/head.php" ?>
        </head>
    <body><?php include dirname(__FILE__) . "/../include/header.php" ?>
        <?php
        include_once dirname(__FILE__) . "/controlador.php";

        //Menú
        echo "<table border='1'><tr><th>";

        echo linkTo("Inicio", "../index.php");

        echo "</th><th>";

        echo linkTo("Nuevo", "detalleusuario.php?tipo=nuevo");

        echo "</th></tr></table>";

        //Obtenemos usuarios
        $usuarios = getUsuarios();

        //Mostramos usuarios
        echo "<table border='1'>";

        //Encabezado
        echo "<tr>"
        . "<th>Id</th>"
        . "<th>Nombre</th>"
        . "<th>Login</th>"
        . "</tr>";

        //Filas
        foreach ($usuarios as $usuario) {

            echo "<tr><td>"
            . $usuario->id
            . "</td><td>"
            . $usuario->nombre
            . "</td><td>"
            . $usuario->login
            . "</td><td>"
            . "<a href='detalleusuario.php?tipo=editar&id=" . $usuario->id . "'>Editar</a>"
            . "</td></tr>";
        }

        //Cerramos la tabla
        echo "</table>";

        //Botones para las acciones posibles
        ?>
    </body>
</html>
