<?php
session_start();
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/membresias/list_membership.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg"
                        style="background: linear-gradient(90deg, #0e94a0, #0bbffb); color: #fff;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem;">
                            <i class="fas fa-id-card-alt"></i> Listado de Renovaciones
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-11 col-md-12">
                    <div class="card card-info">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Membresías Actuales</h3>
                        </div>
                        <div class="card-body">

                        <!-- Botón Renovar Membresia -->
                                <div class="mb-3 text-right">
                                    <a href="create.php" class="btn btn-primary">
                                        <i class="fas fa-gem"></i> Renovar Membresia
                                    </a>
                                </div>

                            <div class="table-responsive">
                                <table id="membresiasTable"
                                    class="table table-bordered table-striped table-sm text-center">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;">Nro</th>
                                            <th style="width:25%;">Miembro</th>
                                            <th style="width:25%;">Tipo de Membresía</th>
                                            <th style="width:20%;">Periodo</th>
                                            <th style="width:15%;">Días restantes</th>
                                            <th style="width:10%;">Estado</th> <!-- Nueva columna -->
                                            <th style="width:15%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $contador = 0;
                                        $hoy = new DateTime();
                                        foreach ($membresias_datos as $m) {
                                            $fechaFinObj = new DateTime($m['fecha_fin']);
                                            $estado = ($fechaFinObj >= $hoy)
                                                ? '<span class="badge bg-success">Activa</span>'
                                                : '<span class="badge bg-danger">Vencida</span>';
                                        ?>
                                        <tr id="row-<?php echo $m['id_membresia']; ?>">
                                            <td><?php echo ++$contador; ?></td>
                                            <td><?php echo htmlspecialchars($m['nombre_miembro']); ?></td>
                                            <td><?php echo htmlspecialchars($m['nombre_membresia']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($m['fecha_inicio'])) . ' - ' . date('d/m/Y', strtotime($m['fecha_fin'])); ?>
                                            </td>
                                            <td><?php echo max(0, (int)$m['dias_restantes']); ?></td>
                                            <td><?php echo $estado; ?></td> <!-- Estado -->
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Acciones">
                                                    <!-- Botón para Renovar -->
                                                    <a href="renovar_form.php?id_membresia=<?php echo $m['id_membresia']; ?>"
                                                        class="btn btn-success btn-sm" title="Renovar membresía">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables y lógica de eliminación -->
<script>
$(function() {
    $('#membresiasTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>