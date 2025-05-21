<?php
// filepath: c:\xampp\htdocs\SysGym\App\views\marcacion\history.php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/marcacion/list_historial.php');
include('../layout/sesion.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(14, 148, 160),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-history fa-lg"></i> Historial de Entradas y Salidas
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-10 col-sm-12 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Registros de Acceso</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-sm text-center" style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">Nro</th>
                                            <th style="width: 10%;">ID Miembro</th>
                                            <th style="width: 18%;">Nombre</th>
                                            <th style="width: 16%;">Fecha y Hora Entrada</th>
                                            <th style="width: 16%;">Fecha y Hora Salida</th>
                                            <th style="width: 10%;">Método</th>
                                            <th style="width: 15%;">Registrado por</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        if ($registros):
                                            foreach ($registros as $r):
                                        ?>
                                            <tr>
                                                <td><?= ++$contador ?></td>
                                                <td><span class="badge badge-info"><?= htmlspecialchars($r['id_miembro']) ?></span></td>
                                                <td><?= htmlspecialchars($r['nombres'] . ' ' . $r['apellidos']) ?></td>
                                                <td><?= htmlspecialchars($r['fecha_entrada']) ?></td>
                                                <td><?= $r['fecha_salida'] ? htmlspecialchars($r['fecha_salida']) : '<span class="text-muted">-</span>' ?></td>
                                                <td>
                                                    <?php
                                                        if ($r['metodo_acceso'] === 'huella') {
                                                            echo '<span class="badge badge-success"><i class="fas fa-fingerprint"></i> Huella</span>';
                                                        } else {
                                                            echo '<span class="badge badge-secondary"><i class="fas fa-keyboard"></i> Manual</span>';
                                                        }
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($r['usuario'] ?? '-') ?></td>
                                            </tr>
                                        <?php
                                            endforeach;
                                        else:
                                        ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">No hay registros para mostrar.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
                    </div>
                </div>
            </div> <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

<script src="datatable.js"></script>

<?php include('../layout/parte2.php'); ?>