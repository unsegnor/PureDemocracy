<?php

include_once dirname(__FILE__). "/controlador.php";

session_destroy();
redirect(direcciones::index);

?>
