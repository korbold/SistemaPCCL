<div class="container-fluid">      
    <div class="panel panel-default shadow-gris">
        <div class="panel-heading"> <span class="glyphicon glyphicon-check"></span>HOJA DE VIDA  </div>
        <div class="panel-body">

            <!-- +++++++++++++CODIGO INTERNO+++++++++++++++ -->



            <table id="mi_tablita" class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>

                        <th style="width: 160px">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($PERSONAS as $per) {
                        ?>
                        <tr>
                            <td><?= $per->CEDULA ?></td>
                            <td><?= $per->NOMBRES ?></td>
                            <td><?= $per->APELLIDO_PATERNO . ' ' . $per->APELLIDO_MATERNO ?> </td>

                            <td>
                                <a href="<?= site_url() ?>/reportes/reporte_f035/<?= $per->ID_PERSONA ?>" 
                                   class="btn btn-default btn-xs"
                                   target="popup" onClick="window.open(this.href, this.target, 'width=800,height=600');
                                           return false;"><span class="glyphicon glyphicon-print"></span> Imprimir</a>


                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <!-- +++++++++++FIN CODIGO INTERNO+++++++++++++ -->
        </div>
    </div>
</div>