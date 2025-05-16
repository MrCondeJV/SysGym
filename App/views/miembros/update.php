<?php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/miembros/update_miembro.php');
include('../layout/sesion.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(19, 108, 182),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-user-edit fa-lg"></i> Actualizar Miembro
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Actualizar Información del Miembro
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
                            <h3 class="card-title">Datos del Miembro</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="../../controllers/miembros/update.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id_miembro" value="<?= $miembro['id_miembro']; ?>">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombres">Nombres</label>
                                        <input type="text" name="nombres" class="form-control" placeholder="Escriba los nombres..." value="<?= htmlspecialchars($miembro['nombres'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="apellidos">Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control" placeholder="Escriba los apellidos..." value="<?= htmlspecialchars($miembro['apellidos'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" class="form-control" value="<?= htmlspecialchars($miembro['fecha_nacimiento'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="genero">Género</label>
                                        <select name="genero" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <option value="Masculino" <?= ($miembro['genero'] ?? '') === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                            <option value="Femenino" <?= ($miembro['genero'] ?? '') === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                            <option value="Otro" <?= ($miembro['genero'] ?? '') === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="correo_electronico">Correo Electrónico</label>
                                        <input type="email" name="correo_electronico" class="form-control" placeholder="Escriba el correo electrónico..." value="<?= htmlspecialchars($miembro['correo_electronico'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" name="telefono" class="form-control" placeholder="Escriba el teléfono..." pattern="[0-9]{7,15}" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= htmlspecialchars($miembro['telefono'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" name="direccion" class="form-control" placeholder="Escriba la dirección..." value="<?= htmlspecialchars($miembro['direccion'] ?? ''); ?>" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="url_foto">Foto</label>
                                        <input type="file" name="url_foto" class="form-control" accept="image/*">
                                        <?php if (!empty($miembro['url_foto'])): ?>
                                            <small class="form-text text-muted">Foto actual:</small>
                                            <img src="../../<?= $miembro['url_foto']; ?>" alt="Foto del Miembro" class="img-thumbnail" style="max-width: 120px; margin-top: 10px;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="activo" <?= ($miembro['estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                            <option value="inactivo" <?= ($miembro['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                            <option value="suspendido" <?= ($miembro['estado'] ?? '') === 'suspendido' ? 'selected' : ''; ?>>Suspendido</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="contacto_emergencia_nombre">Nombre Contacto de Emergencia</label>
                                        <input type="text" name="contacto_emergencia_nombre" class="form-control" placeholder="Nombre del contacto de emergencia..." value="<?= htmlspecialchars($miembro['contacto_emergencia_nombre'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="contacto_emergencia_telefono">Teléfono Contacto de Emergencia</label>
                                        <input type="tel" name="contacto_emergencia_telefono" class="form-control" placeholder="Teléfono del contacto de emergencia..." pattern="[0-9]{7,15}" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= htmlspecialchars($miembro['contacto_emergencia_telefono'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="creado_por">Creado por</label>
                                    <input type="text" name="creado_por" class="form-control" placeholder="Usuario que registra..." value="<?= htmlspecialchars($miembro['creado_por'] ?? ''); ?>" required>
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

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>