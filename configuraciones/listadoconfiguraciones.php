<!DOCTYPE html>
<!--
Mostramos el listado de configuraciones
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

        echo linkTo("Nuevo", "detalleconfiguracion.php?tipo=nuevo");

        echo "</th></tr></table>";

        //Obtenemos configuraciones
        $configuraciones = getConfiguraciones();

        //Mostramos configuraciones
        echo "<table border='1'>";

        //Encabezado
        echo "<tr>"
        . "<th>Id</th>"
        . "<th>Clave</th>"
        . "<th>Valor</th>"
        . "<th>Acciones</th>"
        . "</tr>";

        //Filas
        foreach ($configuraciones as $configuracion) {

            echo "<tr><td>"
            . $configuracion->id
            . "</td><td>"
            . $configuracion->clave
            . "</td><td>"
            . $configuracion->valor
            . "</td><td>"
            . "<a href='detalleconfiguracion.php?tipo=editar&id=" . $configuracion->id . "'>Editar</a>"
            . "</td></tr>";
        }

        //Cerramos la tabla
        echo "</table>";

        //Botones para las acciones posibles
        ?>
    </body>
</html>
