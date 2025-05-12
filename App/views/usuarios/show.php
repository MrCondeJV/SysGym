<?php
include('../../config.php');
//include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/usuarios/show_user.php');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-user"></i> Datos del Usuario
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-10">
                    <div class="card card-warning">
                        <div class="card-header text-center">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Usuario</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <strong><i class="fas fa-id-badge"></i> ID Usuario:</strong>
                                    <p><?php echo $id_usuario; ?></p>
                                </div>
                                <div class="col-sm-12">
                                    <strong><i class="fas fa-user"></i> Nombre Relacionado:</strong>
                                    <p><?php echo $nombre_relacionado; ?></p>
                                </div>
                                <div class="col-sm-12">
                                    <strong><i class="fas fa-user-circle"></i> Usuario:</strong>
                                    <p><?php echo $nombre_usuario; ?></p>
                                </div>
                                <div class="col-sm-12">
                                    <strong><i class="fas fa-user-tag"></i> Tipo Relacionado:</strong>
                                    <p><?php echo $tipo_relacionado; ?></p>
                                </div>
                                <div class="col-sm-12">
                                    <strong><i class="fas fa-user-tag"></i> Rol:</strong>
                                    <span class="badge badge-<?php echo getBadgeClass($nombre_rol); ?>">
                                        <?php echo $nombre_rol; ?>
                                    </span>
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