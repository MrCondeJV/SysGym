<?php
include('../../config.php');
include('../layout/parte1.php');

// Filtro de fechas
$where = [];
$params = [];

if (!empty($_GET['fecha_inicio'])) {
    $where[] = "DATE(r.fecha) >= :fecha_inicio";
    $params[':fecha_inicio'] = $_GET['fecha_inicio'];
}
if (!empty($_GET['fecha_fin'])) {
    $where[] = "DATE(r.fecha) <= :fecha_fin";
    $params[':fecha_fin'] = $_GET['fecha_fin'];
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $pdo->prepare("
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
    $where_sql
    ORDER BY r.fecha DESC
");
$stmt->execute($params);
$renovaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($renovaciones as $r) {
    $total += floatval($r['precio']);
}
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
                    <form method="get" class="mb-3">
                        <div class="form-row align-items-end">
                            <div class="col-auto">
                                <label for="fecha_inicio" class="mb-0">Desde</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                    value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? '') ?>">
                            </div>
                            <div class="col-auto">
                                <label for="fecha_fin" class="mb-0">Hasta</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                    value="<?= htmlspecialchars($_GET['fecha_fin'] ?? '') ?>">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                                <a href="" class="btn btn-outline-secondary">Limpiar</a>
                            </div>
                        </div>
                    </form>
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
                                <div class="mt-3 text-right">
                                    <span class="h5">
                                        <strong>Total:</strong>
                                        <span class="badge badge-primary">
                                            $<?= number_format($total, 2, ',', '.') ?>
                                        </span>
                                    </span>
                                </div>
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