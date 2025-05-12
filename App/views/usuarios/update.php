<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/usuarios/update_user.php');
include('../app/controllers/roles/listado_de_roles.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center">
                <div class="col-sm-12">
                    <h1 class="m-0">Actualizar usuario</h1>
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
                            <form action="../app/controllers/usuarios/update.php" method="post">
                                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

                                <div class="form-group">
                                    <label><?php echo htmlspecialchars($tipo_relacionado); ?> asociado</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo htmlspecialchars($nombre_relacionado); ?>"
                                        disabled>
                                    <?php if ($tipo_relacionado === 'Cliente'): ?>
                                        <small class="form-text text-muted">
                                            Tipo: <?php echo htmlspecialchars($usuario_datos['cli_tipo'] ?? 'N/A'); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="nombre_usuario">Nombre de Usuario</label>
                                    <input type="text" name="nombre_usuario" class="form-control"
                                        value="<?php echo htmlspecialchars($nombre_usuario); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="rol">Rol</label>
                                    <select name="rol" class="form-control" required>
                                        <?php foreach ($roles_datos as $rol_dato): ?>
                                            <option value="<?php echo $rol_dato['id']; ?>"
                                                <?php echo ($rol_dato['id'] == $rol_id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($rol_dato['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="password_user">Nueva Contraseña (Opcional)</label>
                                    <div class="input-group">
                                        <input type="password" name="password_user" class="form-control"
                                            id="password_user">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                                data-target="#password_user">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual</small>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="password_repeat">Repetir Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password_repeat" class="form-control"
                                            id="password_repeat">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                                data-target="#password_repeat">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
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