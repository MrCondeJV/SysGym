<?php
include('../App/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/roles/listado_de_roles.php');

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
                    <h1 class="m-0">Registro de un nuevo usuario</h1>
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
                            <form action="../app/controllers/usuarios/create_user.php" method="post">
                            <div class="form-group">
                                    <label for="nombre">Nombre de la Persona</label>
                                    <input type="text" name="nombre" class="form-control"
                                        placeholder="Escriba aquí el nombre del nuevo usuario..." required>
                                </div>

                                <div class="form-group">
                                    <label for="nombre">Nombre de Usuario</label>
                                    <input type="text" name="nombre" class="form-control"
                                        placeholder="Escriba aquí el nombre del nuevo usuario..." required>
                                </div>

                                <div class="form-group">
                                    <label for="correo_electronico">Correo Electrónico</label>
                                    <input type="email" name="correo_electronico" class="form-control"
                                        placeholder="Escriba el correo electrónico del usuario..." required>
                                </div>

                                <div class="form-group">
                                    <label for="rol">Seleccione Rol</label>
                                    <select name="rol" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($roles_datos as $roles_dato) { ?>
                                            <option value="<?= $roles_dato['id']; ?>"><?= $roles_dato['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" class="form-control" required>
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                        <option value="suspendido">Suspendido</option>
                                    </select>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="password_user">Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password_user" class="form-control"
                                            id="password_user" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                                data-target="#password_user"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="password_repeat">Repita la Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password_repeat" class="form-control"
                                            id="password_repeat" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                                data-target="#password_repeat"><i class="fas fa-eye"></i></button>
                                        </div>
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