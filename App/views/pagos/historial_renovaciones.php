<?php
include('../../config.php');
include('../layout/parte1.php');

$id_miembro = isset($_GET['id_miembro']) ? intval($_GET['id_miembro']) : 0;
if ($id_miembro <= 0) {
    echo "<div class='alert alert-danger'>Usuario no válido.</div>";
    include('../layout/parte2.php');
    exit;
}

// Consulta renovaciones del usuario
$stmt = $pdo->prepare("
    SELECT r.*, mp.nombre AS metodo_pago, t.nombre AS tipo_membresia, t.precio
    FROM renovaciones r
    JOIN metodos_pago mp ON r.id_metodo_pago = mp.id_metodo
    JOIN membresias m ON r.id_membresia = m.id_membresia
    JOIN tiposmembresia t ON m.id_tipo_membresia = t.id_tipo_membresia
    WHERE r.id_miembro = ?
    ORDER BY r.fecha DESC
");
$stmt->execute([$id_miembro]);
$renovaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-list"></i> Historial de Renovaciones
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card card-info">
                        <div class="card-header">
                            <a href="index.php" class="btn btn-secondary btn-sm float-right"><i
                                    class="fas fa-arrow-left"></i> Volver</a>
                            <h3 class="card-title">Renovaciones realizadas</h3>
                        </div>
                        <div class="card-body">
                            <table id="renovacionesTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>N° Factura</th>
                                        <th>Membresía</th>
                                        <th>Precio</th>
                                        <th>Método de Pago</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($renovaciones as $i => $r): ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($r['fecha'])); ?></td>
                                        <td><?php echo htmlspecialchars($r['numero_factura']); ?></td>
                                        <td><?php echo htmlspecialchars($r['tipo_membresia']); ?></td>
                                        <td>$<?php echo number_format($r['precio'], 2, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($r['metodo_pago']); ?></td>
                                        <td><?php echo nl2br(htmlspecialchars($r['observaciones'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
    $('#renovacionesTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

<?php include('../layout/parte2.php'); ?>