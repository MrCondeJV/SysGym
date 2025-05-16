<?php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/proveedores/update_proveedor.php');
include('../layout/sesion.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(5, 99, 32),rgb(51, 240, 114)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-truck"></i> Actualizar Proveedor
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Actualizar Información del Proveedor
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Datos del proveedor</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="../../controllers/proveedores/update.php" method="post">
                                <input type="hidden" name="id_proveedor" value="<?= $id_proveedor; ?>">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre del Proveedor</label>
                                        <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del proveedor..." value="<?= htmlspecialchars($proveedor['nombre'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="contacto">Nombre del Contacto</label>
                                        <input type="text" name="contacto" class="form-control" placeholder="Escriba el nombre del contacto..." value="<?= htmlspecialchars($proveedor['contacto'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" name="telefono" class="form-control" placeholder="Escriba el teléfono..." pattern="[0-9]{7,15}" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= htmlspecialchars($proveedor['telefono'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="correo_electronico">Correo Electrónico</label>
                                        <input type="email" name="correo_electronico" class="form-control" placeholder="Escriba el correo electrónico..." value="<?= htmlspecialchars($proveedor['correo_electronico'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" name="direccion" class="form-control" placeholder="Escriba la dirección..." value="<?= htmlspecialchars($proveedor['direccion'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="notas">Notas</label>
                                        <textarea name="notas" class="form-control" placeholder="Escriba notas adicionales..." rows="3"><?= htmlspecialchars($proveedor['notas'] ?? ''); ?></textarea>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group text-center">
                                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Mostrar/ocultar contraseñas (si es necesario para otros campos)
        $('.toggle-password').on('click', function() {
            const input = $($(this).data('target'));
            const icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>