<?php

include('../../config.php');
//include('../layout/sesion.php');
include('../layout/parte1.php');
include('../../controllers/entrenadores/show_entrenador.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(19, 108, 182),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-dumbbell fa-lg"></i> Información del Entrenador
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
                                <i class="fas fa-info-circle"></i> Detalles del Entrenador
                            </h3>
                        </div>

                        <div class="card-body" style="font-family: 'Arial', sans-serif; font-size: 1rem;">
                            <div class="row">
                                <!-- ID Entrenador -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-id-badge"></i> ID Entrenador:</strong>
                                    <p class="text-muted"><?php echo $id_entrenador; ?></p>
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
                                <!-- Especialidad -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-clipboard-list"></i> Especialidad:</strong>
                                    <p class="text-muted"><?php echo $especialidad; ?></p>
                                </div>
                                <!-- Teléfono -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-phone"></i> Teléfono:</strong>
                                    <p class="text-muted"><?php echo $telefono; ?></p>
                                </div>
                                <!-- Correo Electrónico -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-envelope"></i> Correo Electrónico:</strong>
                                    <p class="text-muted"><?php echo $correo_electronico; ?></p>
                                </div>
                                <!-- Fecha de Contratación -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-calendar-alt"></i> Fecha de Contratación:</strong>
                                    <p class="text-muted"><?php echo $fecha_contratacion; ?></p>
                                </div>
                                <!-- Estado -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-toggle-on"></i> Estado:</strong>
                                    <p class="text-muted"><?php echo $estado; ?></p>
                                </div>
                                <!-- Certificación -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-certificate"></i> Certificación:</strong>
                                    <p class="text-muted"><?php echo $certificacion; ?></p>
                                </div>
                                <!-- ID Usuario Asociado -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-user-tag"></i> ID Usuario Asociado:</strong>
                                    <p class="text-muted"><?php echo $id_usuario; ?></p>
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