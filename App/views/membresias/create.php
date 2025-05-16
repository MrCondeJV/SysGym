<?php
include('../../config.php');
include('../layout/parte1.php');
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
                                    <div class="form-group col-md-6">
                                        <label for="miembro_autocomplete">Miembro</label>
                                        <input type="text" class="form-control" id="miembro_autocomplete"
                                            autocomplete="off" name="miembro_autocomplete"
                                            placeholder="Escribe y selecciona un miembro" required>
                                        <input type="hidden" name="id_miembro" id="id_miembro">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="tipo_autocomplete">Tipo de Membresía</label>
                                        <input type="text" class="form-control" id="tipo_autocomplete"
                                            autocomplete="off" name="tipo_autocomplete"
                                            placeholder="Escribe y selecciona el tipo de membresía" required>
                                        <input type="hidden" name="id_tipo_membresia" id="id_tipo_membresia">
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

<!-- Activar select2 -->
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

    $("#tipo_autocomplete").autocomplete({
        source: "../../controllers/membresias_tipo/search_tipomembership.php",
        minLength: 2,
        select: function(event, ui) {
            $("#id_tipo_membresia").val(ui.item.id);
        },
        change: function(event, ui) {
            if (!ui.item) {
                $("#id_tipo_membresia").val('');
                $(this).val('');
            }
        }
    });
});
</script>


<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>