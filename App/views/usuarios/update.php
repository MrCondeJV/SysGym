<?php
include('../../config.php');
include('../layout/parte1.php');
//include('../layout/sesion.php');
require_once __DIR__ . '/../../controllers/usuariosController.php';


$usuariosController = new UsuariosController();
$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;
$roles_datos = $usuariosController->listarRoles();
$usuario = $usuariosController->obtener($id_usuario);
$dependencias_datos = $usuariosController->listarDependencias();
if (!$usuario) {
    echo '<div class="alert alert-danger m-4">Usuario no encontrado.</div>';
    include('../layout/parte2.php');
    exit;
}
?>
<div class="content-wrapper">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="perfil-header-card d-flex flex-column flex-md-row align-items-center justify-content-between p-4 mb-3">
                            <div class="d-flex align-items-center mb-3 mb-md-0">
                                <div class="perfil-avatar-lg mr-4">
                                    <?php if (!empty($usuario['foto'])): ?>
                                        <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Avatar" class="img-fluid rounded-circle" style="width:120px;height:120px;object-fit:cover;">
                                    <?php else: ?>
                                        <i class="fas fa-user-circle"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h2 class="perfil-nombre mb-1"><?php echo htmlspecialchars($usuario['nombre_completo'] ?? ($usuario['nombres'] ?? '')); ?></h2>
                                    <div class="perfil-usuario mb-1"><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($usuario['usuario'] ?? $usuario['nombre_usuario'] ?? ''); ?></div>
                                    <span class="badge badge-<?php echo getBadgeClass($usuario['rol_nombre'] ?? $usuario['rol'] ?? ''); ?> perfil-rol mr-2" title="Rol del usuario">
                                        <i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($usuario['rol_nombre'] ?? $usuario['rol'] ?? ''); ?>
                                    </span>
                                    <span class="badge badge-<?php echo ($usuario['activo'] ?? $usuario['estado'] ?? 1) ? 'success' : 'secondary'; ?> perfil-estado" title="Estado">
                                        <i class="fas fa-toggle-on"></i> <?php echo ($usuario['activo'] ?? $usuario['estado'] ?? 1) ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="perfil-actions text-md-right mt-3 mt-md-0">
                                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
                                <button type="submit" form="form-usuario" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-md-12">
                        <div class="card shadow-lg border-0 perfil-info-card">
                            <div class="card-body p-4">
                                <form id="form-usuario" action="../../controllers/usuarios/update.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id_usuario" value="<?= $id_usuario; ?>">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombres"><i class="fas fa-user"></i> Nombre Completo</label>
                                            <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre completo..." value="<?= htmlspecialchars($usuario['nombre_completo'] ?? ($usuario['nombres'] ?? '')); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="usuario"><i class="fas fa-user-tag"></i> Usuario</label>
                                            <input type="text" name="usuario" class="form-control" placeholder="Usuario..." value="<?= htmlspecialchars($usuario['usuario'] ?? $usuario['nombre_usuario'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="correo"><i class="fas fa-envelope"></i> Correo</label>
                                            <input type="email" name="correo" class="form-control" placeholder="Correo electrónico..." value="<?= htmlspecialchars($usuario['correo'] ?? $usuario['correo_electronico'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                                            <input type="tel" name="telefono" class="form-control" placeholder="Teléfono..." pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="id_rol"><i class="fas fa-user-shield"></i> Rol</label>
                                            <select name="id_rol" class="form-control" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($roles_datos as $roles_dato): ?>
                                                <option value="<?= $roles_dato['id']; ?>" <?= ($roles_dato['id'] == ($usuario['id_rol'] ?? $usuario['rol'] ?? '')) ? 'selected' : ''; ?>><?= htmlspecialchars($roles_dato['nombre']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="id_dependencia"><i class="fas fa-building"></i> Dependencia</label>
                                            <select name="id_dependencia" class="form-control" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($dependencias_datos as $dep): ?>
                                                    <option value="<?= $dep['id']; ?>" <?= ($dep['id'] == ($usuario['id_dependencia'] ?? '')) ? 'selected' : ''; ?>><?= htmlspecialchars($dep['nombre']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="activo"><i class="fas fa-toggle-on"></i> Estado</label>
                                            <select name="activo" class="form-control" required>
                                                <option value="1" <?= (($usuario['activo'] ?? $usuario['estado'] ?? 1) == 1) ? 'selected' : ''; ?>>Activo</option>
                                                <option value="0" <?= (($usuario['activo'] ?? $usuario['estado'] ?? 1) == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="clave"><i class="fas fa-key"></i> Nueva Contraseña</label>
                                            <input type="password" name="clave" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="foto"><i class="fas fa-image"></i> Foto/Avatar</label>
                                            <input type="file" name="foto" class="form-control-file">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <style>
    .perfil-header-card {
        background: linear-gradient(90deg, #f5f6fa 0%, #e3e6ed 100%);
        color: #222;
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
    }
    .perfil-avatar-lg {
        font-size: 5.5rem;
        color: #b0b4bb;
        background: #e3e6ed;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px 0 rgba(31,38,135,0.08);
        overflow: hidden;
    }
    .perfil-nombre {
        font-size: 2.1rem;
        font-weight: bold;
        letter-spacing: 1.2px;
        color: #222;
        text-shadow: 0 2px 8px #e3e6ed;
    }
    .perfil-usuario {
        font-size: 1.1rem;
        color: #6c757d;
    }
    .perfil-rol, .perfil-estado {
        font-size: 1rem;
        padding: 0.5em 1.2em;
        border-radius: 1.2em;
        margin-right: 0.5em;
    }
    .perfil-actions .btn { min-width: 120px; margin-bottom: 0.5em; }
    .perfil-info-card {
        border-radius: 1.2rem;
        background: #fff;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.06);
    }
    .perfil-info-card strong {
        color: #495057;
        font-weight: 600;
    }
    .perfil-info-card .text-muted {
        color: #333 !important;
        font-size: 1.05rem;
    }
    @media (max-width: 767px) {
        .perfil-header-card { padding: 1.2rem 0.5rem; }
        .perfil-avatar-lg { font-size: 3.5rem; width: 80px; height: 80px; }
        .perfil-nombre { font-size: 1.3rem; }
        .perfil-actions .btn { min-width: 100px; font-size: 0.95rem; }
    }
    </style>

    <?php
    // Badge color helper (puedes moverlo a un helper global)
    function getBadgeClass($rol)
    {
        switch (strtolower($rol)) {
            case 'administrador':
                return 'danger';
            case 'administrativo':
                return 'primary';
            case 'conductor':
                return 'success';
            case 'coordinador':
                return 'info';
            case 'invitado':
                return 'warning';
            default:
                return 'secondary';
        }
    }
    ?>

<script>
$(document).ready(function() {
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

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-usuario');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        formData.append('id', <?php echo json_encode($id_usuario); ?>);
        fetch('../../controllers/usuarios/update.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Actualizado!', data.message, 'success').then(() => window.location.reload());
            } else {
                Swal.fire('Error', data.message || 'No se pudo actualizar el usuario.', 'error');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'No se pudo contactar al servidor.', 'error');
        });
    });
});
</script>

<?php include('../layout/mensajes.php'); ?>

 <!-- Cierre de .content-wrapper principal -->
<?php include('../layout/parte2.php'); ?>