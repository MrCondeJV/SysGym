<?php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/membresias_tipo/list_tipomembership.php');
include('../layout/sesion.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg"
                        style="background: linear-gradient(90deg,rgb(14, 148, 160),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-tags fa-lg"></i> Tipos de Membresía
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
                <div class="col-lg-11 col-md-12 col-sm-12 mx-auto">
                    <div class="card card-info">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Membresías Registradas</h3>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tiposTable" class="table table-bordered table-striped table-sm text-center"
                                    style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;">Nro</th>
                                            <th style="width:20%;">Nombre</th>
                                            <th style="width:10%;">Duración (días)</th>
                                            <th style="width:10%;">Precio (COP)</th>
                                            <th style="width:20%;">Descripción</th>
                                            <th style="width:20%;">Beneficios</th>
                                            <th style="width:15%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($tiposmembresia_datos as $tipo) {
                                            $id = $tipo['id_tipo_membresia'];
                                            // Recortar texto largo para descripción y beneficios
                                            $descripcion_corta = strlen($tipo['descripcion']) > 50 ? substr($tipo['descripcion'], 0, 47) . '...' : $tipo['descripcion'];
                                            $beneficios_corto = strlen($tipo['beneficios']) > 50 ? substr($tipo['beneficios'], 0, 47) . '...' : $tipo['beneficios'];
                                        ?>
                                        <tr id="row-<?php echo $id; ?>">
                                            <td><?php echo ++$contador; ?></td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                <?php echo htmlspecialchars($tipo['nombre']); ?></td>
                                            <td><?php echo $tipo['duracion_dias']; ?></td>
                                            <td><?php echo number_format($tipo['precio'], 2); ?></td>
                                            <td class="text-truncate" style="max-width: 250px;"
                                                title="<?php echo htmlspecialchars($tipo['descripcion']); ?>">
                                                <?php echo htmlspecialchars($descripcion_corta); ?></td>
                                            <td class="text-truncate" style="max-width: 250px;"
                                                title="<?php echo htmlspecialchars($tipo['beneficios']); ?>">
                                                <?php echo htmlspecialchars($beneficios_corto); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="show.php?id=<?php echo $id; ?>" class="btn btn-info btn-sm"
                                                        title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="update.php?id=<?php echo $id; ?>"
                                                        class="btn btn-warning btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm btn-eliminar"
                                                        data-id="<?php echo $id; ?>"
                                                        data-row-id="row-<?php echo $id; ?>" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
            </div> <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->

<!-- DataTables -->
<script>
$(function() {
    $('#tiposTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

<script src="delete.js"></script> <!-- Botón eliminar con SweetAlert, ejemplo -->

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>