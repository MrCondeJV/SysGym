
<?php
include('../../config.php');
include('../layout/parte1.php');
require_once __DIR__ . '/../../controllers/usuariosController.php';
$usuariosController = new UsuariosController();
$roles_datos = $usuariosController->listarRoles();
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
            <div class="row mb-4">
                <div class="col-12">
                    <div class="perfil-header-card d-flex flex-column flex-md-row align-items-center justify-content-between p-4 mb-3">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="perfil-avatar-lg mr-4">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <h2 class="perfil-nombre mb-1">Registro de un <span class="font-weight-bold">Nuevo Usuario</span></h2>
                                <div class="perfil-usuario mb-1"><i class="fas fa-user-tag"></i> Complete los campos para registrar un usuario</div>
                            </div>
                        </div>
                        <div class="perfil-actions text-md-right mt-3 mt-md-0">
                            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
                            <button type="submit" form="form-usuario" class="btn btn-success"><i class="fas fa-save"></i> Guardar Usuario</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="card shadow-lg border-0 perfil-info-card">
                        <div class="card-body p-4">
                            <form id="form-usuario" action="../../controllers/usuarios/create_user.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre_completo"><i class="fas fa-user"></i> Nombre Completo</label>
                                        <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre completo..." required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="usuario"><i class="fas fa-user-tag"></i> Usuario</label>
                                        <input type="text" name="usuario" class="form-control" placeholder="Usuario..." required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="correo"><i class="fas fa-envelope"></i> Correo</label>
                                        <input type="email" name="correo" class="form-control" placeholder="Correo electrónico..." required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                                        <input type="tel" name="telefono" class="form-control" placeholder="Teléfono..." pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="id_rol"><i class="fas fa-user-shield"></i> Rol</label>
                                        <select name="id_rol" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($roles_datos as $roles_dato): ?>
                                            <option value="<?= $roles_dato['id']; ?>"><?= htmlspecialchars($roles_dato['nombre']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="id_dependencia"><i class="fas fa-building"></i> Dependencia</label>
                                        <input type="text" name="id_dependencia" class="form-control" placeholder="Dependencia...">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="activo"><i class="fas fa-toggle-on"></i> Estado</label>
                                        <select name="activo" class="form-control" required>
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="clave"><i class="fas fa-key"></i> Contraseña</label>
                                        <input type="password" name="clave" class="form-control" placeholder="Contraseña..." required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="clave_repeat"><i class="fas fa-key"></i> Repetir Contraseña</label>
                                        <input type="password" name="clave_repeat" class="form-control" placeholder="Repetir contraseña..." required>
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


    <style>
    .perfil-header-card {
        background: linear-gradient(90deg, #15297C 0%, #15297C 100%);
        color: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    }
    .perfil-avatar-lg {
        font-size: 5.5rem;
        color: #fff;
        background: rgba(11,191,251,0.12);
        border-radius: 50%;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px 0 rgba(11,191,251,0.18);
        overflow: hidden;
    }
    .perfil-nombre {
        font-size: 2.1rem;
        font-weight: bold;
        letter-spacing: 1.2px;
        text-shadow: 0 2px 8px #000a;
    }
    .perfil-usuario {
        font-size: 1.1rem;
        color: #e0f7fa;
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
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
    }
    .perfil-info-card strong {
        color: #136cb6;
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
    </script>

<?php include('../layout/parte2.php'); ?>