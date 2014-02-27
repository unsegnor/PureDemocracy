<!DOCTYPE html>
<!--
Mostramos el listado de permisos
-->
<html>
<head><?php include dirname(__FILE__) . "/../include/head.php"?>
        </head>
    <body><?php include dirname(__FILE__) . "/../include/header.php" ?>
        <?php
        include_once dirname(__FILE__) . "/controlador.php";

        //Menú
        echo "<table border='1'><tr><th>";

        echo linkTo("Inicio", "../index.php");

        echo "</th><th>";

        echo linkTo("Nuevo", "detallepermiso.php?tipo=nuevo");

        echo "</th></tr></table>";

        //Obtenemos permisos
        $permisos = getPermisos();

        //Mostramos permisos
        echo "<table border='1'>";

        //Encabezado
        echo "<tr>"
        . "<th>Id</th>"
        . "<th>Nombre</th>"
        . "<th>Acciones</th>"
        . "</tr>";

        //Filas
        foreach ($permisos as $permiso) {

            echo "<tr><td>"
            . $permiso->id
            . "</td><td>"
            . $permiso->nombre
            . "</td><td>"
            . "<a href='detallepermiso.php?tipo=editar&id=".$permiso->id."'>Editar</a>"
            . "</td></tr>";
        }

        //Cerramos la tabla
        echo "</table>";

        //Botones para las acciones posibles
        ?>
    </body>
</html>
