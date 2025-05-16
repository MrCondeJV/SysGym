<?php
include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');

?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-tags"></i> Registro de una <span class="font-weight-bold">Nueva
                            Membresia</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para registrar una membresia para
                        miembros.
                    </p>
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

                            <form action="../../controllers/membresias/create_membership.php" method="post">
                                <div class="form-group">
                                    <label for="id_miembro">Miembro</label>
                                    <input type="text" class="form-control" id="miembro_autocomplete" name="id_miembro"
                                        placeholder="Buscar miembro..." required>
                                    <small class="form-text text-muted">Empiece a escribir para buscar un miembro
                                        registrado.</small>
                                </div>

                                <div class="form-group">
                                    <label for="id_tipo_membresia">Tipo de membresía</label>
                                    <select class="form-control" name="id_tipo_membresia" id="id_tipo_membresia"
                                        required>
                                        <option value="">Seleccione un tipo</option>
                                        <!-- Opciones se cargan dinámicamente desde PHP -->
                                        <?php

                                        $query = $pdo->query("SELECT id_tipo_membresia, nombre FROM tiposmembresia");
                                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value=\"{$row['id_tipo_membresia']}\">{$row['nombre']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de fin</label>
                                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Registrar
                                        Membresía</button>
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


<script>
$(function() {
    $("#miembro_autocomplete").autocomplete({
        source: "../../controllers/membresias/search_miembros.php",
        minLength: 2,
        select: function(event, ui) {
            $("#miembro_autocomplete").val(ui.item.label);
            $("#id_miembro").val(ui.item.value);
            return false;
        }
    });
});
</script>