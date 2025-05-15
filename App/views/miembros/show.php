<?php

include('../../config.php');
//include('../layout/sesion.php');
include('../layout/parte1.php');
include('../../controllers/miembros/show_miembro.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(19, 108, 182),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-user fa-lg"></i> Información del Miembro
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Consulta detallada de los datos registrados
                        </p>
                        <div class="mt-3">
                            <span class="badge badge-light text-dark p-2" style="font-size: 1rem; border-radius: 20px;">
                                <i class="fas fa-calendar-alt"></i> Última actualización: <?= date('d/m/Y'); ?>
                            </span>
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
                    <div class="card card-primary">
                        <div class="card-header text-center">
                            <h3 class="card-title" style="font-family: 'Arial', sans-serif; font-size: 1.5rem;">
                                <i class="fas fa-info-circle"></i> Detalles del Miembro
                            </h3>
                        </div>

                        <div class="card-body" style="font-family: 'Arial', sans-serif; font-size: 1rem;">
                            <div class="row">
                                <!-- ID Miembro -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-id-badge"></i> ID Miembro:</strong>
                                    <p class="text-muted"><?php echo $id_miembro; ?></p>
                                </div>
                                <!-- Nombres -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-user"></i> Nombres:</strong>
                                    <p class="text-muted"><?php echo $nombres; ?></p>
                                </div>
                                <!-- Apellidos -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-user-circle"></i> Apellidos:</strong>
                                    <p class="text-muted"><?php echo $apellidos; ?></p>
                                </div>
                                <!-- Fecha de Nacimiento -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-birthday-cake"></i> Fecha de Nacimiento:</strong>
                                    <p class="text-muted"><?php echo $fecha_nacimiento; ?></p>
                                </div>
                                <!-- Género -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-venus-mars"></i> Género:</strong>
                                    <p class="text-muted"><?php echo $genero; ?></p>
                                </div>
                                <!-- Correo Electrónico -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-envelope"></i> Correo Electrónico:</strong>
                                    <p class="text-muted"><?php echo $correo_electronico; ?></p>
                                </div>
                                <!-- Teléfono -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-phone"></i> Teléfono:</strong>
                                    <p class="text-muted"><?php echo $telefono; ?></p>
                                </div>
                                <!-- Dirección -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong>
                                    <p class="text-muted"><?php echo $direccion; ?></p>
                                </div>
                                <!-- Fecha de Registro -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-calendar-plus"></i> Fecha de Registro:</strong>
                                    <p class="text-muted"><?php echo $fecha_registro; ?></p>
                                </div>
                                <!-- Estado -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-toggle-on"></i> Estado:</strong>
                                    <p class="text-muted"><?php echo $estado; ?></p>
                                </div>
                                <!-- Foto -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-image"></i> Foto:</strong>
                                    <p class="text-muted">
                                        <?php if (!empty($url_foto)): ?>
                                            <a href="../../<?php echo $url_foto; ?>" target="_blank">
                                                <img src="../../<?php echo $url_foto; ?>" alt="Foto del Miembro" class="img-thumbnail" style="max-width: 120px;">
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Sin foto</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <!-- Contacto de Emergencia -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-user-shield"></i> Contacto Emergencia:</strong>
                                    <p class="text-muted"><?php echo $contacto_emergencia_nombre; ?></p>
                                </div>
                                <!-- Teléfono de Emergencia -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-phone-square"></i> Tel. Emergencia:</strong>
                                    <p class="text-muted"><?php echo $contacto_emergencia_telefono; ?></p>
                                </div>
                                <!-- Creado por -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-user-tag"></i> Creado por:</strong>
                                    <p class="text-muted"><?php echo $creado_por; ?></p>
                                </div>
                            </div>
                            <hr>

                            <div class="text-right mt-3">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Regresar
                                </a>
                            </div>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>