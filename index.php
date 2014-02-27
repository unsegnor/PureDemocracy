<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body><?php include dirname(__FILE__) . "/../include/header.php" ?>
        <?php
        //echo sha1("12345");
        include_once dirname(__FILE__)."/nucleo/controlador.php";
        
        //Si está logueado pasa al menu
        if(isset($_SESSION['identificado']) && $_SESSION['identificado']){
            redirect("nucleo/webprincipal.php");
        }else{
            redirect("nucleo/weblogin.php");
        }
        ?>
    </body>
</html>
