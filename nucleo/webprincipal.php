<!DOCTYPE html>

<html>
    <head><?php include dirname(__FILE__) . "/../include/head.php" ?>
    </head>
    <body>
        <?php
        include_once dirname(__FILE__) . "/controlador.php";
        include_once dirname(__FILE__) . "/../configuraciones/controlador.php";
        include_once dirname(__FILE__) . "/../general/data.php";
        include_once dirname(__FILE__) . "/../objetivos/controlador.php";
        ?>
        <?php include dirname(__FILE__) . "/../include/header.php" ?>

        <div class="container principal">
            <div class="row">
                <div class="col-sm-4">
                    <?php
                        $propuestas = getAll('objetivos')->resultado;
                        ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading"  data-toggle="collapse" data-parent="#accordion" data-target="#collapseOne">
                                <h3 class="panel-title">Propuestas</h3>
                            </div>
                            <div class="panel-body">
                                Propuestas pendientes de aprobación
                            </div>
                            <div id="collapseOne" class="list-group collapse in">
                                <?php
                                foreach ($propuestas as $propuesta) {
                                    ?>
                                    <a href="" class="list-group-item">
                                        <?php echo $propuesta['descripcion'] ?>
                                        <!--<span class="badge"><?php echo $info_reparacion['n_reparaciones'] ?></span>-->
                                    </a>

                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    <!--Formulario para añadir propuestas -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <span class="glyphicon glyphicon-plus"></span>nueva propuesta
                        </div>
                            
                        <div class="panel-body">
                            <input type="text" class="form-control" onchange="llama('nuevaPropuesta',[$(this).val()])" placeholder="descripción">
                        </div>
                    </div>
                </div><!-- /.col-sm-4 -->
                <div class="col-sm-4">
                    <div class="panel panel-primary">
                        <?php
                        //Mostramos los pagadores que necesitan información hoy
                        $aprobadas = getPagadoresAInformar(new DateTime())->resultado;
                        ?>
                        <div class="panel-heading">
                            <h3 class="panel-title">Informar a Compañías</h3>
                        </div>
                        <div class="panel-body">
                            Clientes con expedientes pendientes de informe
                        </div>
                        <div class="list-group">
                            <?php
                            foreach ($pagadores_a_informar as $pagador_a_informar) {
                                ?>
                                <a href="../informe_pagadores/informe_pagadores.php?id_pagador=<?php echo $pagador_a_informar['id_entidad'] ?>" class="list-group-item">
                                    <?php echo $pagador_a_informar['nombre'] ?>
                                    <span class="badge"><?php echo $pagador_a_informar['n_expedientes'] ?></span>
                                </a>

                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div><!-- /.col-sm-4 -->
                <div class="col-sm-4">
                    <?php
                    $vida_expediente = getConfiguracion("vida_maxima_expediente_dias")->resultado;
                    $alarma_expedientes = getExpedientesAntiguosAbiertos($vida_expediente);
                    ?>
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">Alarma Expedientes</h3>

                        </div>
                        <div class="panel-body">
                            Expedientes abiertos desde hace <?php echo $vida_expediente ?> días
                        </div>
                        <div class="list-group">

                            <?php foreach ($alarma_expedientes as $expediente) { ?>

                                <a href="../expedientes/detalleexpediente.php?tipo=editar&id=<?php echo $expediente->id ?>" class="list-group-item">
                                    <?php echo $expediente->numero_expediente ?>

                                </a>

                            <?php } ?>

                        </div>
                    </div>
                    <?php
                    $vida_factura = getConfiguracion("vida_maxima_factura_dias")->resultado;
                    $alarma_facturas = getFacturasAntiguasPendientesDeCobro($vida_factura);
                    ?>
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">Alarma Facturas</h3>
                        </div>
                        <div class="panel-body">
                            Facturas por cobrar desde hace <?php echo $vida_factura ?> días
                        </div>
                        <div class="list-group">
                            <?php foreach ($alarma_facturas as $factura) { ?>

                                <a href="../facturas/detallefactura.php?tipo=editar&id=<?php echo $factura->id ?>" class="list-group-item">
                                    <?php echo $factura->numero_factura ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                    $vida_albaran = getConfiguracion("vida_maxima_albaran_dias")->resultado;
                    $alarma_albaranes = getAlbaranesAntiguosPendientesDeFacturar($vida_albaran);
                    ?>
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">Alarma Albaranes</h3>
                        </div>
                        <div class="panel-body">
                            Albaranes por facturar desde hace <?php echo $vida_albaran ?> días
                        </div>
                        <div class="list-group">
                            <?php foreach ($alarma_albaranes as $grupo_albaran) { ?>

                                <a href="../expedientes/detalleexpediente.php?tipo=editar&id=<?php echo $grupo_albaran->id_expediente ?>" class="list-group-item">
                                    <?php echo $grupo_albaran->nombre ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div><!-- /.col-sm-4 -->
            </div>
        </div> <!-- container -->

    </body>
</html>
