<?php
include('../../config.php');
//include('../layout/sesion.php');
include('../layout/parte1.php');
include('../../controllers/productos/show_producto.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(19, 108, 182),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-box fa-lg"></i> Información del Producto
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
                                <i class="fas fa-info-circle"></i> Detalles del Producto
                            </h3>
                        </div>

                        <div class="card-body" style="font-family: 'Arial', sans-serif; font-size: 1rem;">
                            <div class="row">
                                <!-- Código del Producto -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-barcode"></i> Código del Producto:</strong>
                                    <p class="text-muted"><?php echo $codigo_producto; ?></p>
                                </div>
                                <!-- Nombre -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-box"></i> Nombre:</strong>
                                    <p class="text-muted"><?php echo $nombre; ?></p>
                                </div>
                                <!-- Descripción -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-align-left"></i> Descripción:</strong>
                                    <p class="text-muted"><?php echo $descripcion; ?></p>
                                </div>
                                <!-- Precio -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-dollar-sign"></i> Precio:</strong>
                                    <p class="text-muted"><?php echo number_format($precio, 2); ?></p>
                                </div>
                                <!-- Cantidad en Stock -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-boxes"></i> Cantidad en Stock:</strong>
                                    <p class="text-muted"><?php echo $cantidad_stock; ?></p>
                                </div>
                                <!-- Categoría -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-tags"></i> Categoría:</strong>
                                    <p class="text-muted"><?php echo $categoria_nombre; ?></p>
                                </div>
                                <!-- Código de Barras -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-barcode"></i> Código de Barras:</strong>
                                    <p class="text-muted"><?php echo $codigo_barras; ?></p>
                                </div>
                                <!-- Imagen -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-image"></i> Imagen del Producto:</strong>
                                    <p class="text-muted">
                                        <a href="<?php echo $url_imagen; ?>" target="_blank">
                                            <img src="<?php echo $url_imagen; ?>" alt="Imagen del Producto" class="img-thumbnail" style="max-width: 150px; cursor: pointer;">
                                        </a>
                                    </p>
                                </div>
                                <!-- Precio de Compra -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-dollar-sign"></i> Precio de Compra:</strong>
                                    <p class="text-muted"><?php echo number_format($precio_compra, 2); ?></p>
                                </div>
                                <!-- Margen de Ganancia -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-percentage"></i> Margen de Ganancia:</strong>
                                    <p class="text-muted"><?php echo number_format($margen_ganancia, 2); ?></p>
                                </div>
                                <!-- Stock Mínimo -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-exclamation-triangle"></i> Stock Mínimo:</strong>
                                    <p class="text-muted"><?php echo $stock_minimo; ?></p>
                                </div>
                                <!-- Estado -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-toggle-on"></i> Estado:</strong>
                                    <p class="text-muted"><?php echo $activo ? 'Activo' : 'Inactivo'; ?></p>
                                </div>
                                <!-- Proveedor -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-truck"></i> Proveedor:</strong>
                                    <p class="text-muted"><?php echo $proveedor_nombre; ?></p>
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