<?php

include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');

$resultado = null;
$busqueda = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $busqueda = trim($_POST['busqueda']);
    if ($busqueda !== '') {
        $sql = "SELECT * FROM miembros WHERE numero_documento LIKE :busqueda OR CONCAT(nombres, ' ', apellidos) LIKE :busqueda";
        $stmt = $pdo->prepare($sql);
        $like = "%$busqueda%";
        $stmt->bindParam(':busqueda', $like);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid py-4">
            <div class="card card-info mx-auto" style="max-width:520px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-search"></i> Verificar Existencia de Miembro</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="busqueda">Número de cédula o nombre del miembro</label>
                            <input type="text" class="form-control" id="busqueda" name="busqueda" placeholder="Ingrese cédula o nombre" value="<?= htmlspecialchars($busqueda) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </form>
                    <?php if ($resultado !== null): ?>
                        <?php if (count($resultado) > 0): ?>
                            <?php foreach ($resultado as $row): ?>
                                <div class="card mt-4 border-info">
                                    <div class="card-body">
                                        <h5 class="card-title mb-1">
                                            <i class="fas fa-user-circle text-info"></i>
                                            <?= htmlspecialchars($row['nombres'] . ' ' . $row['apellidos']) ?>
                                        </h5>
                                        <p class="mb-1">
                                            <i class="fas fa-id-card"></i>
                                            <b>Documento:</b> <?= htmlspecialchars($row['numero_documento']) ?>
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-phone"></i>
                                            <b>Teléfono:</b> <?= htmlspecialchars($row['telefono'] ?? 'No registrado') ?>
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-envelope"></i>
                                            <b>Email:</b> <?= htmlspecialchars($row['email'] ?? 'No registrado') ?>
                                        </p>
                                        <?php
                                        // Buscar membresía activa
                                        $sqlM = "SELECT * FROM membresias WHERE id_miembro = :id AND fecha_fin >= CURDATE() ORDER BY fecha_fin DESC LIMIT 1";
                                        $stmtM = $pdo->prepare($sqlM);
                                        $stmtM->bindParam(':id', $row['id_miembro']);
                                        $stmtM->execute();
                                        $membresia = $stmtM->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                        <?php if ($membresia): ?>
                                            <div class="alert alert-success mb-1 mt-3 p-2">
                                                <i class="fas fa-check-circle"></i>
                                                <b>Membresía activa</b><br>
                                                <span>
                                                    <b>Tipo:</b> <?= htmlspecialchars($membresia['tipo_membresia'] ?? 'N/A') ?><br>
                                                    <b>Vence:</b> <?= date('d/m/Y', strtotime($membresia['fecha_fin'])) ?>
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-danger mb-1 mt-3 p-2">
                                                <i class="fas fa-times-circle"></i>
                                                <b>¡Membresía vencida!</b><br>
                                                Este miembro no tiene una membresía activa.<br>
                                                <span style="font-size:0.95em;">Invítalo a renovar y seguir entrenando 💪</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning mt-4 text-center">
                                <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                                <b>No se encontró ningún miembro con ese dato.</b>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include('../layout/parte2.php'); ?>