<?php
include('../../config.php');
include('../layout/parte1.php');

// Consulta usuarios que han hecho renovaciones
$stmt = $pdo->query("
    SELECT me.id_miembro, me.nombres, me.apellidos, COUNT(r.id_renovacion) AS total_renovaciones
    FROM miembros me
    JOIN renovaciones r ON me.id_miembro = r.id_miembro
    GROUP BY me.id_miembro, me.nombres, me.apellidos
    ORDER BY me.nombres
");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-history"></i> Historial de Pagos/Renovaciones
                    </h1>
                    <p class="text-muted mt-2">Consulta el historial de renovaciones de cada usuario.</p>
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
                            <h3 class="card-title">Usuarios con renovaciones</h3>
                        </div>
                        <div class="card-body">
                            <table id="usuariosTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Total Renovaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $i => $u): ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']); ?></td>
                                        <td><?php echo $u['total_renovaciones']; ?></td>
                                        <td>
                                            <a href="historial_renovaciones.php?id_miembro=<?php echo $u['id_miembro']; ?>"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-list"></i> Ver historial
                                            </a>
                                        </td>
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
    $('#usuariosTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

<?php include('../layout/parte2.php'); ?>