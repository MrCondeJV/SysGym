<?php
include('../../config.php');
//include('../layout/sesion.php');
include('../layout/parte1.php');
include('../../controllers/salas/show_sala.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(19, 108, 182),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-door-open fa-lg"></i> Información de la Sala
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
                                <i class="fas fa-info-circle"></i> Información de la Sala
                            </h3>
                        </div>

                        <div class="card-body" style="font-family: 'Arial', sans-serif; font-size: 1rem;">
                            <div class="row">
                                <!-- ID Sala -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-id-badge"></i> ID Sala:</strong>
                                    <p class="text-muted"><?php echo $id_sala; ?></p>
                                </div>
                                <!-- Nombre -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-door-open"></i> Nombre:</strong>
                                    <p class="text-muted"><?php echo $nombre; ?></p>
                                </div>
                                <!-- Capacidad -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-users"></i> Capacidad:</strong>
                                    <p class="text-muted"><?php echo $capacidad; ?></p>
                                </div>
                                <!-- Descripción -->
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <strong><i class="fas fa-align-left"></i> Descripción:</strong>
                                    <p class="text-muted"><?php echo $descripcion; ?></p>
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