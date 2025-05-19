<?php
include('../../config.php');
include('../layout/parte1.php');

// Obtener tipos de membresía para el select
$stmt = $pdo->query("SELECT id_tipo_membresia, nombre FROM tiposmembresia ORDER BY nombre ASC");
$tiposMembresia = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-id-card-alt"></i> Registrar <span class="font-weight-bold">Membresía</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos para registrar una nueva membresía.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Formulario de Registro</h3>
                        </div>

                        <form action="../../controllers/membresias/create_membership.php" method="POST">
                            <div class="card-body">
                                <div class="form-row">
                                    <!-- Campo Miembro con autocomplete (se mantiene igual) -->
                                    <div class="form-group col-md-6">
                                        <label for="miembro_autocomplete">Miembro</label>
                                        <input type="text" class="form-control" id="miembro_autocomplete"
                                            autocomplete="off" name="miembro_autocomplete"
                                            placeholder="Escribe y selecciona un miembro" required>
                                        <input type="hidden" name="id_miembro" id="id_miembro">
                                    </div>

                                    <!-- Campo Tipo de Membresía cambiado a select -->
                                    <div class="form-group col-md-6">
                                        <label for="id_tipo_membresia">Tipo de Membresía</label>
                                        <select class="form-control" name="id_tipo_membresia" id="id_tipo_membresia"
                                            required>
                                            <option value="" disabled selected>Seleccione un tipo de membresía</option>
                                            <?php foreach ($tiposMembresia as $tipo): ?>
                                            <option value="<?= htmlspecialchars($tipo['id_tipo_membresia']) ?>">
                                                <?= htmlspecialchars($tipo['nombre']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="index.php" class="btn btn-secondary mr-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Registrar Membresía</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mantén solo el autocomplete para el campo miembro -->
<script>
$(function() {
    $("#miembro_autocomplete").autocomplete({
        source: "../../controllers/miembros/search_miembros.php",
        minLength: 2,
        select: function(event, ui) {
            $("#id_miembro").val(ui.item.id);
        },
        change: function(event, ui) {
            if (!ui.item) {
                $("#id_miembro").val('');
                $(this).val('');
            }
        }
    });
});
</script>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>