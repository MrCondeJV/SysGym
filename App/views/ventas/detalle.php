<?php
include('../../config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../../controllers/ventas/detalle_venta.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(19, 108, 182),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-file-invoice"></i> Información de la Venta
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Consulta detallada de la venta registrada
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
                                <i class="fas fa-info-circle"></i> Detalles de la Venta
                            </h3>
                        </div>
                        <div class="card-body" style="font-family: 'Arial', sans-serif; font-size: 1rem;">
                            <?php if ($detalleVenta && $detalleVenta->venta): ?>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <strong><i class="fas fa-hashtag"></i> Número de Factura:</strong>
                                        <p class="text-muted"><?= htmlspecialchars($detalleVenta->venta['numero_factura'] ?? '-') ?></p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <strong><i class="fas fa-calendar-alt"></i> Fecha:</strong>
                                        <p class="text-muted"><?= htmlspecialchars($detalleVenta->venta['fecha_venta'] ?? '-') ?></p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <strong><i class="fas fa-user"></i> Usuario:</strong>
                                        <p class="text-muted"><?= htmlspecialchars($detalleVenta->venta['usuario'] ?? '-') ?></p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <strong><i class="fas fa-credit-card"></i> Método de Pago:</strong>
                                        <p class="text-muted"><?= htmlspecialchars($detalleVenta->venta['metodo_pago'] ?? '-') ?></p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <strong><i class="fas fa-percent"></i> Descuento Total:</strong>
                                        <p class="text-muted">$<?= number_format($detalleVenta->venta['descuento_total'] ?? 0, 2) ?></p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <strong><i class="fas fa-dollar-sign"></i> Total:</strong>
                                        <p class="text-muted">$<?= number_format($detalleVenta->venta['total'] ?? 0, 2) ?></p>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-3"><i class="fas fa-box"></i> Productos Vendidos</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped table-sm text-center shadow" style="background: #f8fafc; border-radius: 10px; overflow: hidden;">
                                        <thead class="thead-dark" style="background: linear-gradient(90deg, #136cb6, #0bbffb); color: #fff;">
                                            <tr>
                                                <th style="vertical-align: middle;">Producto</th>
                                                <th style="vertical-align: middle;">Cantidad</th>
                                                <th style="vertical-align: middle;">Precio Unitario</th>
                                                <th style="vertical-align: middle;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($detalleVenta->detalles as $detalle): ?>
                                                <tr style="font-size: 1.05rem;">
                                                    <td style="font-weight: 500;"><?= htmlspecialchars($detalle['nombre']) ?></td>
                                                    <td>
                                                        <span class="badge badge-info" style="font-size: 1rem;"><?= htmlspecialchars($detalle['cantidad']) ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success" style="font-size: 1rem;">$<?= number_format($detalle['precio_unitario'], 2) ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary" style="font-size: 1rem;">$<?= number_format($detalle['subtotal'], 2) ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($detalleVenta->detalles)): ?>
                                                <tr>
                                                    <td colspan="4">No hay productos en esta venta.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger">Venta no encontrada.</div>
                            <?php endif; ?>
                            <div class="text-right mt-3">
                                <a href="history.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Regresar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>