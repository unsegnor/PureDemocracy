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
            $permiso = new Permiso();
            //Acción añadir
            $accion = "add";
        } else if ($tipo == "editar") {
            //Recuperamos el permiso por su id
            $id = $_REQUEST['id'];

            $permiso = getPermisoPorID($id);

            //Acción editar
            $accion = "edit";
        }

        //Preparamos los valores del formulario
        //Llamamos a los campos igual que a los de bdd para poder convertirlo directamente
        ?>       


        <form method ="post" action="oppermiso.php">

            <input type="hidden" name="id_permiso" value="<?php echo $permiso->id ?>">
            NOMBRE<input type="text" name="nombre" value="<?php echo $permiso->nombre ?>">

            <input type="hidden" name="accion" value="<?php echo $accion ?>">
            <input type="submit" value="Guardar">
        </form>





    </body>
</html>
