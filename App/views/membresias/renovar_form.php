<?php
include('../../config.php');
include('../layout/parte1.php');

$id_membresia = isset($_GET['id_membresia']) ? intval($_GET['id_membresia']) : 0;
if ($id_membresia <= 0) {
    echo "<div class='alert alert-danger'>ID de membresía inválido.</div>";
    include('../layout/parte2.php');
    exit;
}

// Obtener métodos de pago activos
$stmt = $pdo->query("SELECT id_metodo, nombre FROM metodos_pago WHERE activo = 1 ORDER BY nombre ASC");
$metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-sync-alt"></i> Renovación de <span class="font-weight-bold">Membresía</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para renovar la membresía
                        seleccionada.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos con cuidado</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="renovar.php" method="post">
                                <input type="hidden" name="id_membresia" value="<?php echo $id_membresia; ?>">
                                <div class="mb-3">
                                    <label for="numero_factura" class="form-label">Número de Factura</label>
                                    <input type="text" name="numero_factura" id="numero_factura" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="metodo_pago" class="form-label">Método de Pago</label>
                                    <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                                        <option value="">Seleccione un método de pago</option>
                                        <?php foreach ($metodos as $metodo): ?>
                                        <option value="<?php echo $metodo['id_metodo']; ?>">
                                            <?php echo htmlspecialchars($metodo['nombre']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" class="form-control"
                                        rows="3"></textarea>
                                </div>
                                <div class="text-end">
                                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Renovar</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>