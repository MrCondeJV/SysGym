<?php
include('../../config.php');
include('../layout/parte1.php');
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
            window.location.href = '<?php echo $URL; ?>miembros/index.php';
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
                        <i class="fas fa-user-plus"></i> Registro de un <span class="font-weight-bold">Nuevo
                            Miembro</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para registrar un nuevo miembro en el
                        sistema.</p>
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
                            <form action="../../controllers/miembros/create_miembro.php" method="post"
                                enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="tipo_documento">Tipo de Documento</label>
                                        <select name="tipo_documento" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <option value="CC">Cédula de Ciudadanía</option>
                                            <option value="TI">Tarjeta de Identidad</option>
                                            <option value="CE">Cédula de Extranjería</option>
                                            <option value="NIT">Número de Identificación Tributaria (NIT)</option>
                                            <option value="RC">Registro Civil</option>
                                            <option value="PASAPORTE">Pasaporte</option>
                                            <option value="PEP">Permiso Especial de Permanencia (PEP)</option>
                                            <option value="DNI">Documento Nacional de Identidad (DNI)</option>
                                            <option value="CM">Carné Militar</option>
                                            <option value="CI">Carné Institucional</option>
                                            <option value="LM">Libreta Militar</option>
                                            <option value="OTRO">Otro</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="numero_documento">Número de Documento</label>
                                        <input type="text" name="numero_documento" class="form-control"
                                            placeholder="Ingrese el número..." required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombres">Nombres</label>
                                        <input type="text" name="nombres" class="form-control"
                                            placeholder="Escriba los nombres..." required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="apellidos">Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control"
                                            placeholder="Escriba los apellidos..." required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="genero">Género</label>
                                        <select name="genero" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="correo_electronico">Correo Electrónico</label>
                                        <input type="email" name="correo_electronico" class="form-control"
                                            placeholder="Escriba el correo electrónico..." required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" name="telefono" class="form-control"
                                            placeholder="Escriba el teléfono..." pattern="[0-9]{7,15}" maxlength="15"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" name="direccion" class="form-control"
                                        placeholder="Escriba la dirección..." required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="url_foto">Foto</label>
                                        <input type="file" name="url_foto" class="form-control" accept="image/*">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                            <option value="suspendido">Suspendido</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="contacto_emergencia_nombre">Nombre Contacto de Emergencia</label>
                                        <input type="text" name="contacto_emergencia_nombre" class="form-control"
                                            placeholder="Nombre del contacto de emergencia..." required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="contacto_emergencia_telefono">Teléfono Contacto de
                                            Emergencia</label>
                                        <input type="tel" name="contacto_emergencia_telefono" class="form-control"
                                            placeholder="Teléfono del contacto de emergencia..." pattern="[0-9]{7,15}"
                                            maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            required>
                                    </div>
                                </div>
                                <!--
                                <div class="form-group">
                                    <label for="creado_por">Creado por</label>
                                    <input type="text" name="creado_por" class="form-control"
                                        placeholder="Usuario que registra..." required>
                                </div>
-->
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