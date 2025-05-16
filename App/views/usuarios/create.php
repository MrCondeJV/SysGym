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
                        <i class="fas fa-user-plus"></i> Registro de un <span class="font-weight-bold">Nuevo Usuario</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para registrar un nuevo usuario en el sistema.</p>
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
                            <form action="../../controllers/usuarios/create_user.php" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="nombres">Nombres</label>
                                        <input type="text" name="nombres" class="form-control" placeholder="Escriba los nombres..." required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="apellidos">Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control" placeholder="Escriba los apellidos..." required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nombre_usuario">Nombre de Usuario</label>
                                        <input type="text" name="nombre_usuario" class="form-control" placeholder="Escriba el nombre de usuario..." required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="contrasena_hash">Contraseña</label>
                                        <input type="password" name="contrasena_hash" class="form-control" id="contrasena_hash" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="contrasena_hash">Repita Contraseña</label>
                                        <input type="password" name="contrasena_hash_repeat" class="form-control" id="contrasena_hash" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="rol">Seleccione Rol</label>
                                        <select name="rol" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($roles_datos as $roles_dato) { ?>
                                                <option value="<?= $roles_dato['id']; ?>"><?= $roles_dato['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="form-group col-md-4">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" name="telefono" class="form-control" placeholder="Escriba el teléfono..."
                                            pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="correo_electronico">Correo Electrónico</label>
                                        <input type="email" name="correo_electronico" class="form-control" placeholder="Escriba el correo electrónico..." required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                            <option value="suspendido">Suspendido</option>
                                        </select>
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