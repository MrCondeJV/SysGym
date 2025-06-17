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
            <div class="card card-info mx-auto" style="max-width:500px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-search"></i> Verificar Existencia de Miembro</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="busqueda">Número de cédula o nombre del miembro</label>
                            <input type="text" class="form-control" id="busqueda" name="busqueda" placeholder="Ingrese cédula o nombre" value="<?= htmlspecialchars($busqueda) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                    </form>
                    <?php if ($resultado !== null): ?>
                        <?php if (count($resultado) > 0): ?>
                            <div class="alert alert-success mt-3">Miembro encontrado:</div>
                            <ul class="list-group">
                                <?php foreach ($resultado as $row): ?>
                                    <li class="list-group-item">
                                        <b><?= htmlspecialchars($row['nombres'] . ' ' . $row['apellidos']) ?></b>
                                        - Documento: <b><?= htmlspecialchars($row['numero_documento']) ?></b>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-danger mt-3">No se encontró ningún miembro con ese dato.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include('../layout/parte2.php'); ?>