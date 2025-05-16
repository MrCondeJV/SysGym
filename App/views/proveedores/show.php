<?php
include('../../config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../../controllers/proveedores/show_proveedor.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(19, 108, 182),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-truck fa-lg"></i> Información del Proveedor
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Consulta detallada de los datos registrados
                        </p>
                        
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
                                <i class="fas fa-info-circle"></i> Detalles del Proveedor
                            </h3>
                        </div>

                        <div class="card-body" style="font-family: 'Arial', sans-serif; font-size: 1rem;">
                            <div class="row">
                                
                                <!-- Nombre -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <strong><i class="fas fa-building"></i> Nombre:</strong>
                                    <p class="text-muted"><?php echo $nombre; ?></p>
                                </div>
                                <!-- Contacto -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <strong><i class="fas fa-user"></i> Contacto:</strong>
                                    <p class="text-muted"><?php echo $contacto; ?></p>
                                </div>
                                <!-- Teléfono -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <strong><i class="fas fa-phone"></i> Teléfono:</strong>
                                    <p class="text-muted"><?php echo $telefono; ?></p>
                                </div>
                                <!-- Correo Electrónico -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <strong><i class="fas fa-envelope"></i> Correo Electrónico:</strong>
                                    <p class="text-muted"><?php echo $correo_electronico; ?></p>
                                </div>
                                <!-- Dirección -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong>
                                    <p class="text-muted"><?php echo $direccion; ?></p>
                                </div>
                                <!-- Notas -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <strong><i class="fas fa-sticky-note"></i> Notas:</strong>
                                    <p class="text-muted"><?php echo $notas; ?></p>
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