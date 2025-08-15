<?php
include('../../config.php');
include('../layout/parte1.php');
require_once __DIR__ . '/../../controllers/solicitudesController.php';
$solicitudesController = new SolicitudesController();
$solicitudes = $solicitudesController->listar(['estado' => 'solicitada']);
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
                            <i class="fas fa-user-circle fa-lg"></i> Solicitudes de Salas
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
                            <h3 class="card-title">Listado de solicitudes</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-sm text-center"
                                    style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Título</th>
                                            <th>Solicitante</th>
                                            <th>Aula</th>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($solicitudes as $solicitud) {
                                            $contador++;
                                            ?>
                                            <tr>
                                                <td><?php echo $contador; ?></td>
                                                <td><?php echo htmlspecialchars($solicitud['motivo'] ?? $solicitud['titulo'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($solicitud['usuario_nombre'] ?? $solicitud['nombre_solicitante'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($solicitud['aula_nombre'] ?? ''); ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_inicio'])); ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_fin'])); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo ($solicitud['estado_nombre'] == 'aprobada') ? 'success' : (($solicitud['estado_nombre'] == 'rechazada') ? 'danger' : 'secondary'); ?>">
                                                        <?php echo ucfirst($solicitud['estado_nombre']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="show.php?id=<?php echo $solicitud['id']; ?>" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                                                    <button class="btn btn-success btn-sm btn-aceptar-solicitud" data-id="<?php echo $solicitud['id']; ?>" title="Aceptar"><i class="fas fa-check"></i></button>
                                                    <button class="btn btn-danger btn-sm btn-rechazar-solicitud" data-id="<?php echo $solicitud['id']; ?>" title="Rechazar"><i class="fas fa-times"></i></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
<script src="solicitudes.js"></script>
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

<script src="datatable.js"></script>
<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>