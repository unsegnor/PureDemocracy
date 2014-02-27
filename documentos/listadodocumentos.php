<!DOCTYPE html>
<!--
Mostramos el listado de documentos
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

        echo linkTo("Nuevo", "detalledocumento.php?tipo=nuevo");

        echo "</th></tr></table>";

        //Obtenemos documentos
        $documentos = getDocumentos();

        //Mostramos documentos
        echo "<table border='1'>";

        //Encabezado
        echo "<tr>"
        . "<th>Id</th>"
        . "<th>ruta</th>"
        . "<th>nombre</th>"
        . "<th>tipo</th>"
        . "<th>Acciones</th>"
        . "</tr>";

        //Filas
        foreach ($documentos as $documento) {

            echo "<tr><td>"
            . $documento->id
            . "</td><td>"
            . $documento->ruta
            . "</td><td>"
            . $documento->nombre
            . "</td><td>"
            . $documento->tipo
            . "</td><td>"
            . "<a href='detalledocumento.php?tipo=editar&id=" . $documento->id . "'>Editar</a>"
            . "</td></tr>";
        }

        //Cerramos la tabla
        echo "</table>";

        //Botones para las acciones posibles
        ?>
    </body>
</html>
