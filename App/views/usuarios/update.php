<?php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/roles/list_rol.php');
include('../../controllers/usuarios/update_user.php');
include('../layout/sesion.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(5, 99, 32),rgb(51, 240, 114)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-user-circle fa-lg"></i> Actualizar Usuario
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Actualizar Información del Usuario
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
                            <h3 class="card-title">Datos del usuario</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="../../controllers/usuarios/update.php" method="post">
                                <input type="hidden" name="id_usuario" value="<?= $id_usuario; ?>">

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="nombres">Nombres</label>
                                        <input type="text" name="nombres" class="form-control" placeholder="Escriba los nombres..." value="<?= htmlspecialchars($usuario['nombres'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="apellidos">Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control" placeholder="Escriba los apellidos..." value="<?= htmlspecialchars($usuario['apellidos'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nombre_usuario">Nombre de Usuario</label>
                                        <input type="text" name="nombre_usuario" class="form-control" placeholder="Escriba el nombre de usuario..." value="<?= htmlspecialchars($usuario['nombre_usuario'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="contrasena_hash">Contraseña</label>
                                        <input type="password" name="contrasena_hash" class="form-control" id="contrasena_hash">
                                        <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</small>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="contrasena_hash_repeat">Repita Contraseña</label>
                                        <input type="password" name="contrasena_hash_repeat" class="form-control" id="contrasena_hash_repeat">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="rol">Seleccione Rol</label>
                                        <select name="rol" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($roles_datos as $roles_dato): ?>
                                                <option value="<?= $roles_dato['id']; ?>" <?= ($roles_dato['id'] == ($usuario['rol'] ?? '')) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($roles_dato['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" name="telefono" class="form-control" placeholder="Escriba el teléfono..." pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= htmlspecialchars($usuario['telefono'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="correo_electronico">Correo Electrónico</label>
                                        <input type="email" name="correo_electronico" class="form-control" placeholder="Escriba el correo electrónico..." value="<?= htmlspecialchars($usuario['correo_electronico'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="activo" <?= ($usuario['estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                            <option value="inactivo" <?= ($usuario['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                            <option value="suspendido" <?= ($usuario['estado'] ?? '') === 'suspendido' ? 'selected' : ''; ?>>Suspendido</option>
                                        </select>
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
        // Mostrar/ocultar contraseñas
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