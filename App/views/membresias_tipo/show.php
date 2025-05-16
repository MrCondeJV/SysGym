<?php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/membresias_tipo/show_tipomembership.php');
include('../layout/sesion.php');

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-tags"></i> Detalle del <span class="font-weight-bold">Tipo de Membresía</span>
                    </h1>
                    <p class="text-muted mt-2">Información completa del tipo de membresía seleccionado.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Detalles de:
                                <strong><?php echo htmlspecialchars($tipo['nombre']); ?></strong>
                            </h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Nombre:</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($tipo['nombre']); ?></dd>

                                <dt class="col-sm-4">Duración (días):</dt>
                                <dd class="col-sm-8"><?php echo $tipo['duracion_dias']; ?></dd>

                                <dt class="col-sm-4">Precio (COP):</dt>
                                <dd class="col-sm-8">$ <?php echo number_format($tipo['precio'], 2); ?></dd>

                                <dt class="col-sm-4">Descripción:</dt>
                                <dd class="col-sm-8"><?php echo nl2br(htmlspecialchars($tipo['descripcion'])); ?></dd>

                                <dt class="col-sm-4">Beneficios:</dt>
                                <dd class="col-sm-8"><?php echo nl2br(htmlspecialchars($tipo['beneficios'])); ?></dd>
                            </dl>
                        </div>
                        <div class="card-footer text-right">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>