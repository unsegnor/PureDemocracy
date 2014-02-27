<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head><?php include dirname(__FILE__) . "/../include/head.php" ?>
        </head>
    <body><?php include dirname(__FILE__) . "/../include/header.php" ?>
        <?php
        include_once dirname(__FILE__) . "/controlador.php";
        include_once dirname(__FILE__) . "/../proveedores/controlador.php";
        include_once dirname(__FILE__) . "/../permisos/controlador.php";


        //Comprobamos el tipo de petición
        $tipo = $_REQUEST['tipo'];

        if ($tipo == "nuevo") {
            //Campos vacíos
            $usuario = new Usuario();
            //Acción añadir
            $accion = "add";
        } else if ($tipo == "editar") {
            //Recuperamos el usuario por su id
            $id = $_REQUEST['id'];

            $usuario = getUsuarioPorID($id);

            //Acción editar
            $accion = "edit";
        }

        //Preparamos los valores del formulario
        //Llamamos a los campos igual que a los de bdd para poder convertirlo directamente
        ?>       


        <form method ="post" action="opusuario.php">

            <input type="hidden" name="id_usuario" value="<?php echo $usuario->id ?>">
            NOMBRE<input type="text" name="nombre" value="<?php echo $usuario->nombre ?>">
            LOGIN<input type="text" name="login" value="<?php echo $usuario->login ?>">

            <input type="hidden" name="accion" value="<?php echo $accion ?>">
            <input type="submit" value="Guardar">
        </form>

        <?php if ($tipo == "editar") { ?>

            <form method="post" action="cambiarpass.php">
                <input type="hidden" name="id_usuario" value="<?php echo $usuario->id ?>">
                <input type="text" name="pass" placeholder="nueva contraseña">
                <input type="submit" value="Cambiar contraseña">
            </form>
        <?php } ?>

        <!-- Listar proveedores asociados a esta cuenta -->
        <h2> Proveedores asociados a este usuario </h2>
        <table border="1">
            <?php
            $proveedores = getProveedores();
            $proveedores_asociados = getProveedoresDeUsuario($usuario->id);

            foreach ($proveedores_asociados as $proveedor_asociado) {
                ?>

                <tr>
                <form method="post" action="linkproveedorusuario.php">
                    <input type="hidden" name="id_usuario" value="<?php echo $usuario->id ?>">
                    <input type="hidden" name="id_old_proveedor" value="<?php echo $proveedor_asociado->id ?>">

                    <td>
                        <select name="id_proveedor">
                            <?php
                            foreach ($proveedores as $proveedor) {

                                echo "<option value='" . $proveedor->id . "'";

                                if ($proveedor->id == $proveedor_asociado->id) {
                                    echo " selected='selected' ";
                                }

                                echo ">" . $proveedor->nombre . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="accion" value="edit">
                        <input type="hidden" name="retorno" value="../usuarios/detalleusuario.php?tipo=editar&id=<?php echo $usuario->id ?>">
                        <input type="submit" value="Guardar">
                    </td>
                </form>
            </tr>

            <?php
        }
        ?>

        <tr>
        <form method="post" action="linkproveedorusuario.php">
            <input type="hidden" name="id_usuario" value="<?php echo $usuario->id ?>">

            <td>
                <select name="id_proveedor">
                    <?php
                    foreach ($proveedores as $proveedor) {

                        echo "<option value='" . $proveedor->id . "'";

                        echo ">" . $proveedor->nombre . "</option>";
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="hidden" name="accion" value="add">
                <input type="hidden" name="retorno" value="../usuarios/detalleusuario.php?tipo=editar&id=<?php echo $usuario->id ?>">
                <input type="submit" value="Nuevo">
            </td>
        </form>


    </tr>
</table>
<!-- Asignación de permisos -->
<h2>Permisos</h2>
<table border="1">
    <tr>
        <th>Permiso</th>
        <th>Otorgado</th>
    </tr>

    <?php
    //Obtener todos los permisos
    $permisos = getPermisos();

    //Obtener los permisos del usuario
    $permisos_usuario = getPermisosDeUsuario($usuario->id);
    ?>
    <form method="post" action="oppermisosusuario.php">
        <input type="hidden" name="id_usuario" value="<?php echo $usuario->id ?>">
        <?php
        //Listamos todos los permisos con un checkbox
        foreach ($permisos as $permiso) {
            ?>
            <tr>

                <td><?php echo $permiso->nombre ?></td>
                <td>
                    <input type="checkbox" name="concedidos[]" value="<?php echo $permiso->id ?>"
                           
                           <?php 

                           //Si existe este permiso entre los permisos del usuario
                           if(isset($permisos_usuario[$permiso->id])){
                               //Lo marcamos como seleccionado
                           ?>
                           checked="checked"
                           <?php }?>
                           >
                </td>

            </tr>
            <?php
        }
        ?>
        
        <input type="hidden" name="retorno" value="../usuarios/detalleusuario.php?tipo=editar&id=<?php echo $usuario->id ?>">
        <input type="submit" value="Guardar">
    </form>
</table>



</body>
</html>
