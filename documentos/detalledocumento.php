<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
<head><?php include dirname(__FILE__) . "/../include/head.php"?>
        </head>
    <body><?php include dirname(__FILE__) . "/../include/header.php" ?>
        <?php
        include_once dirname(__FILE__). "/controlador.php";


        //Comprobamos el tipo de petición
        $tipo = $_REQUEST['tipo'];

        if ($tipo == "nuevo") {
            //Campos vacíos
            $documento = new Documento();
            //Acción añadir
            $accion = "add";
        } else if ($tipo == "editar") {
            //Recuperamos el documento por su id
            $id = $_REQUEST['id'];

            $documento = getDocumentoPorID($id);

            //Acción editar
            $accion = "edit";
        }

        //Preparamos los valores del formulario
        //Llamamos a los campos igual que a los de bdd para poder convertirlo directamente
        ?>       


        <form method ="post" action="opdocumento.php">

            <input type="hidden" name="id_documento" value="<?php echo $documento->id ?>">
            ruta<input type="text" name="ruta" value="<?php echo $documento->ruta ?>">

            <input type="hidden" name="accion" value="<?php echo $accion ?>">
            <input type="submit" value="Guardar">
        </form>





    </body>
</html>
