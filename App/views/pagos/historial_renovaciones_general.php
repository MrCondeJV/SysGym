<?php
include('../../config.php');
include('../layout/parte1.php');

// Consulta todas las renovaciones con datos del miembro
$stmt = $pdo->query("
    SELECT r.*, 
           me.nombres AS nombre_miembro, 
           me.apellidos AS apellido_miembro,
           me.numero_documento,
           mp.nombre AS metodo_pago, 
           t.nombre AS tipo_membresia, 
           t.precio,
           CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario_renovo
    FROM renovaciones r
    JOIN miembros me ON r.id_miembro = me.id_miembro
    JOIN metodos_pago mp ON r.id_metodo_pago = mp.id_metodo
    JOIN membresias m ON r.id_membresia = m.id_membresia
    JOIN tiposmembresia t ON m.id_tipo_membresia = t.id_tipo_membresia
    LEFT JOIN usuariossistema u ON r.renovado_por = u.id_usuario
    ORDER BY r.fecha DESC
");
$renovaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-list"></i> Historial General de Renovaciones
                    </h1>
                    <p class="text-muted mt-2">Consulta todas las renovaciones realizadas por los miembros.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-history"></i> Todas las renovaciones</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="renovacionesGeneralTable"
                                    class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Miembro</th>
                                            <th>Documento</th>
                                            <th>Fecha</th>
                                            <th>N° Factura</th>
                                            <th>Membresía</th>
                                            <th>Precio</th>
                                            <th>Método de Pago</th>
                                            <th>Renovado por</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($renovaciones as $i => $r): ?>
                                        <tr>
                                            <td><?php echo $i + 1; ?></td>
                                            <td>
                                                <i class="fas fa-user text-info"></i>
                                                <?php echo htmlspecialchars($r['nombre_miembro'] . ' ' . $r['apellido_miembro']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($r['numero_documento']); ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?php echo date('d/m/Y H:i', strtotime($r['fecha'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($r['numero_factura']); ?></td>
                                            <td><?php echo htmlspecialchars($r['tipo_membresia']); ?></td>
                                            <td>
                                                <span class="badge badge-success">
                                                    $<?php echo number_format($r['precio'], 2, ',', '.'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($r['metodo_pago']); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($r['nombre_usuario_renovo'] ?? ''); ?>
                                            </td>
                                            <td><?php echo nl2br(htmlspecialchars($r['observaciones'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="../pagos/index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<script>
$(function() {
    $('#renovacionesGeneralTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

<?php include('../layout/parte2.php'); ?>