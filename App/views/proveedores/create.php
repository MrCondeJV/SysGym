<?php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/roles/list_rol.php');
include('../layout/sesion.php');

if (isset($_SESSION['mensaje'])): ?>
    <script>
        $(document).ready(function() {
            const swalConfig = {
                icon: '<?php echo $_SESSION['icono']; ?>',
                title: '<?php echo $_SESSION['mensaje']; ?>',
                showConfirmButton: true,
                confirmButtonText: '<?php echo $_SESSION['icono'] == 'success' ? 'Continuar' : 'Reintentar'; ?>',
                confirmButtonColor: '<?php echo $_SESSION['icono'] == 'success' ? '#28a745' : '#dc3545'; ?>'
            };

            Swal.fire(swalConfig).then((result) => {
                <?php if ($_SESSION['icono'] == 'success'): ?>
                    if (result.isConfirmed) {
                        window.location.href = '<?php echo $URL; ?>usuarios/index.php';
                    }
                <?php endif; ?>
            });
        });
    </script>
<?php
    unset($_SESSION['mensaje']);
    unset($_SESSION['icono']);
endif;
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-truck"></i> Registro de un <span class="font-weight-bold">Nuevo Proveedor</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para registrar un nuevo proveedor en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
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
                            <form action="../../controllers/proveedores/create_proveedor.php" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre del Proveedor</label>
                                        <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del proveedor..." required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="contacto">Nombre del Contacto</label>
                                        <input type="text" name="contacto" class="form-control" placeholder="Escriba el nombre del contacto..." required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" name="telefono" class="form-control" placeholder="Escriba el teléfono..." pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="correo_electronico">Correo Electrónico</label>
                                        <input type="email" name="correo_electronico" class="form-control" placeholder="Escriba el correo electrónico..." required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" name="direccion" class="form-control" placeholder="Escriba la dirección..." required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="notas">Notas</label>
                                        <textarea name="notas" class="form-control" placeholder="Escriba notas adicionales..." rows="3"></textarea>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group text-center">
                                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Guardar</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php include('../layout/parte2.php'); ?>