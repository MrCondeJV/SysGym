<?php
include('../../config.php');
//include('../layout/sesion.php');
include('../layout/parte1.php');

require_once __DIR__ . '/../../controllers/usuariosController.php';
$usuariosController = new UsuariosController();
$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;
$usuario = $usuariosController->obtener($id_usuario);
if (!$usuario) {
    echo '<div class="alert alert-danger m-4">Usuario no encontrado.</div>';
    include('../layout/parte2.php');
    exit;
}

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
                                <?php if (!empty($usuario['foto'])): ?>
                                    <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Avatar" class="img-fluid rounded-circle" style="width:120px;height:120px;object-fit:cover;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h2 class="perfil-nombre mb-1"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></h2>
                                <div class="perfil-usuario mb-1"><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($usuario['usuario']); ?></div>
                                <span class="badge badge-<?php echo getBadgeClass($usuario['rol']); ?> perfil-rol mr-2" title="Rol del usuario">
                                    <i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($usuario['rol']); ?>
                                </span>
                                <span class="badge badge-<?php echo $usuario['activo'] ? 'success' : 'secondary'; ?> perfil-estado" title="Estado">
                                    <i class="fas fa-toggle-on"></i> <?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="perfil-actions text-md-right mt-3 mt-md-0">
                            <a href="update.php?id=<?php echo $usuario['id']; ?>" class="btn btn-warning mr-2"><i class="fas fa-edit"></i> Editar</a>
                            <?php if ($usuario['activo']): ?>
                                <button type="button" class="btn btn-danger mr-2" id="btnDesactivar"><i class="fas fa-user-slash"></i> Desactivar</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-success mr-2" id="btnActivar"><i class="fas fa-user-check"></i> Activar</button>
                            <?php endif; ?>
                            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 perfil-info-card">
                        <div class="card-header bg-gradient-primary text-white font-weight-bold">
                            <i class="fas fa-address-card"></i> Información Personal
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-envelope mr-2"></i> <strong>Correo:</strong> <?php echo htmlspecialchars($usuario['correo']); ?></li>
                                <li class="mb-2"><i class="fas fa-phone mr-2"></i> <strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></li>
                                <li class="mb-2"><i class="fas fa-building mr-2"></i> <strong>Dependencia:</strong> <?php echo htmlspecialchars($usuario['dependencia']); ?></li>
                                <li class="mb-2"><i class="fas fa-id-badge mr-2"></i> <strong>ID Usuario:</strong> <?php echo $usuario['id']; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 perfil-info-card">
                        <div class="card-header bg-gradient-info text-white font-weight-bold">
                            <i class="fas fa-history"></i> Actividad y Seguridad
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-calendar-alt mr-2"></i> <strong>Creado En:</strong> <?php echo $usuario['creado_en']; ?></li>
                                <li class="mb-2"><i class="fas fa-calendar-check mr-2"></i> <strong>Actualizado En:</strong> <?php echo $usuario['actualizado_en'] ? $usuario['actualizado_en'] : '—'; ?></li>
                                <li class="mb-2"><i class="fas fa-key mr-2"></i> <strong>Restablecer Contraseña:</strong> <button type="button" class="btn btn-sm btn-outline-primary ml-2" data-toggle="modal" data-target="#modalPassword"><i class="fas fa-sync-alt"></i> Restablecer</button></li>
                        </div>


                        <!-- Modal Restablecer Contraseña -->
                        <div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="modalPasswordLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalPasswordLabel"><i class="fas fa-key"></i> Restablecer contraseña</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="formPassword">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="nuevaClave">Nueva contraseña</label>
                                                <input type="password" class="form-control" id="nuevaClave" name="nuevaClave" required minlength="6">
                                            </div>
                                            <div class="form-group">
                                                <label for="confirmarClave">Confirmar contraseña</label>
                                                <input type="password" class="form-control" id="confirmarClave" name="confirmarClave" required minlength="6">
                                            </div>
                                            <div id="msgPassword" class="text-danger small"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            // SweetAlert2 para desactivar usuario
                            document.getElementById('btnDesactivar')?.addEventListener('click', function() {
                                Swal.fire({
                                    title: '¿Desactivar usuario?',
                                    text: 'El usuario no podrá acceder hasta ser reactivado.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, desactivar',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        fetch('../../controllers/usuarios/desactivar_user.php', {
                                                method: 'POST',
                                                body: new URLSearchParams({
                                                    id: <?php echo $usuario['id']; ?>
                                                })
                                            })
                                            .then(r => r.json())
                                            .then(data => {
                                                if (data.success) {
                                                    Swal.fire('Desactivado!', data.message, 'success').then(() => location.reload());
                                                } else {
                                                    Swal.fire('Error!', data.message || 'Error al desactivar', 'error');
                                                }
                                            });
                                    }
                                });
                            });

                            // Activar usuario
                            document.getElementById('btnActivar')?.addEventListener('click', function() {
                                Swal.fire({
                                    title: '¿Reactivar usuario?',
                                    text: 'El usuario podrá volver a acceder al sistema.',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, reactivar',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        fetch('../../controllers/usuarios/reactivar_user.php', {
                                            method: 'POST',
                                            body: new URLSearchParams({id: <?php echo $usuario['id']; ?>})
                                        })
                                        .then(r => r.json())
                                        .then(data => {
                                            if (data.success) {
                                                Swal.fire('Reactivado!', data.message, 'success').then(() => location.reload());
                                            } else {
                                                Swal.fire('Error!', data.message || 'Error al reactivar', 'error');
                                            }
                                        });
                                    }
                                });
                            });

                            // Restablecer contraseña
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('formPassword');
                                if (form) {
                                    form.addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        const clave = document.getElementById('nuevaClave').value;
                                        if (!clave || clave.length < 6) {
                                            Swal.fire('Error', 'La contraseña debe tener al menos 6 caracteres.', 'error');
                                            return;
                                        }
                                        const formData = new FormData();
                                        formData.append('id', <?php echo json_encode($id_usuario); ?>);
                                        formData.append('clave', clave);
                                        fetch('../../controllers/usuarios/reset_password.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                Swal.fire('¡Listo!', data.message, 'success');
                                                $('#modalPassword').modal('hide');
                                                form.reset();
                                            } else {
                                                Swal.fire('Error', data.message || 'No se pudo actualizar la contraseña.', 'error');
                                            }
                                        })
                                        .catch(() => {
                                            Swal.fire('Error', 'No se pudo contactar al servidor.', 'error');
                                        });
                                    });
                                }
                            });
                        </script>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Timeline de actividad (placeholder, puedes conectar con historial real) -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 perfil-info-card mb-4">
                    <div class="card-header bg-gradient-secondary text-white font-weight-bold">
                        <i class="fas fa-stream"></i> Última Actividad
                    </div>
                    <div class="card-body">
                        <ul class="timeline list-unstyled mb-0">
                            <li class="timeline-item"><span class="timeline-date">2025-08-15</span> Inició sesión en el sistema</li>
                            <li class="timeline-item"><span class="timeline-date">2025-08-14</span> Actualizó su información personal</li>
                            <li class="timeline-item"><span class="timeline-date">2025-08-10</span> Se creó el usuario</li>
                        </ul>
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
        background: rgba(11, 191, 251, 0.12);
        border-radius: 50%;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px 0 rgba(11, 191, 251, 0.18);
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

    .perfil-rol,
    .perfil-estado {
        font-size: 1rem;
        padding: 0.5em 1.2em;
        border-radius: 1.2em;
        margin-right: 0.5em;
    }

    .perfil-actions .btn {
        min-width: 120px;
        margin-bottom: 0.5em;
    }

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

    .timeline {
        border-left: 3px solid #0bbffb;
        margin-left: 1.5rem;
        padding-left: 1.5rem;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.2rem;
        padding-left: 0.5rem;
    }

    .timeline-item:before {
        content: '';
        position: absolute;
        left: -1.7rem;
        top: 0.2rem;
        width: 1rem;
        height: 1rem;
        background: #0bbffb;
        border-radius: 50%;
        box-shadow: 0 0 0 3px #fff;
    }

    .timeline-date {
        font-weight: bold;
        color: #136cb6;
        margin-right: 0.5em;
    }

    @media (max-width: 767px) {
        .perfil-header-card {
            padding: 1.2rem 0.5rem;
        }

        .perfil-avatar-lg {
            font-size: 3.5rem;
            width: 80px;
            height: 80px;
        }

        .perfil-nombre {
            font-size: 1.3rem;
        }

        .perfil-actions .btn {
            min-width: 100px;
            font-size: 0.95rem;
        }
    }
</style>

<?php
// Función para definir el color del badge según el rol
function getBadgeClass($rol)
{
    switch (strtolower($rol)) {
        case 'administrador':
            return 'danger'; // Rojo
        case 'administrativo':
            return 'primary'; // Azul
        case 'conductor':
            return 'success'; // Verde
        case 'coordinador':
            return 'info'; // Celeste
        case 'invitado':
            return 'warning'; // Amarillo
        default:
            return 'secondary'; // Gris por defecto
    }
}
?>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>