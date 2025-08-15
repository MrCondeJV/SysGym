<?php
include('../../config.php');
include('../layout/parte1.php');
//include('../layout/sesion.php');
require_once __DIR__ . '/../../controllers/historialController.php';
$historialController = new HistorialController();
$historial = $historialController->listar();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg"
                        style="background: linear-gradient(90deg,rgb(21, 41, 124),rgb(21, 41, 150)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-user-circle fa-lg"></i> Historial
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 mx-auto">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Historial de solicitudes</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-sm text-center" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Solicitud</th>
                                            <th>Reserva</th>
                                            <th>Estado anterior</th>
                                            <th>Estado nuevo</th>
                                            <th>Usuario</th>
                                            <th>Fecha</th>
                                            <th>Comentario</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1; foreach ($historial as $h): ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo htmlspecialchars($h['solicitud_titulo'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($h['codigo_reserva'] ?? ''); ?></td>
                                                <td>
                                                    <span class="badge 
                                                        <?php
                                                            $estadoAnt = strtolower($h['estado_anterior_nombre'] ?? '');
                                                            if ($estadoAnt === 'aprobada') echo 'badge-success';
                                                            elseif ($estadoAnt === 'rechazada') echo 'badge-danger';
                                                            else echo 'badge-secondary';
                                                        ?>">
                                                        <?php echo htmlspecialchars($h['estado_anterior_nombre'] ?? ''); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        <?php
                                                            $estadoNvo = strtolower($h['estado_nuevo_nombre'] ?? '');
                                                            if ($estadoNvo === 'aprobada') echo 'badge-success';
                                                            elseif ($estadoNvo === 'rechazada') echo 'badge-danger';
                                                            else echo 'badge-primary';
                                                        ?>">
                                                        <?php echo htmlspecialchars($h['estado_nuevo_nombre'] ?? ''); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($h['usuario_nombre'] ?? ''); ?></td>
                                                <td><?php echo !empty($h['cambiado_en']) ? date('d/m/Y H:i', strtotime($h['cambiado_en'])) : ''; ?></td>
                                                <td><?php echo htmlspecialchars($h['comentario'] ?? ''); ?></td>
                                                <td>
                                                    <a href="show.php?id=<?php echo $h['id']; ?>" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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

<!-- /.content-wrapper -->


<?php include('../layout/mensajes.php'); ?>
<script src="datatable.js"></script>
<?php include('../layout/parte2.php'); ?>