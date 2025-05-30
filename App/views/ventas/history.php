<?php
// filepath: c:\xampp\htdocs\SysGym\App\views\ventas\history.php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/ventas/historial_ventas.php');
include('../../controllers/ventas/listado_ventas_general.php');
include('../layout/sesion.php');

$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-history"></i> Historial de Ventas
                    </h1>
                    <p class="text-muted mt-2">Consulta todas las ventas realizadas en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Filtro de fechas -->
            <form method="get" class="mb-3">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <label for="fecha_inicio">Desde:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?= htmlspecialchars($fecha_inicio) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin">Hasta:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?= htmlspecialchars($fecha_fin) ?>">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filtrar</button>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <a href="history.php" class="btn btn-secondary btn-block"><i class="fas fa-times"></i> Limpiar</a>
                    </div>
                </div>
            </form>
            <!-- Fin filtro de fechas -->

            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Ventas Registradas</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm text-center" id="example1">
                                    <thead>
                                        <tr>
                                            <th>ID Venta</th>
                                            <th>Factura</th> <!-- Nueva columna -->
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Usuario</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($ventas)): ?>
                                            <?php foreach ($ventas as $venta): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($venta['id_venta']) ?></td>
                                                    <td><?= htmlspecialchars($venta['numero_factura'] ?? '-') ?></td> <!-- Mostrar número de factura -->
                                                    <td><?= htmlspecialchars($venta['fecha_venta']) ?></td>
                                                    <td>$<?= number_format($venta['total'], 2) ?></td>
                                                    <td><?= htmlspecialchars($venta['nombre_usuario'] ?? 'Desconocido') ?></td>
                                                    <td>
                                                        <a href="detalle.php?id=<?= $venta['id_venta'] ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i> Ver Detalle
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6">No hay ventas registradas.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                 <div class="mt-3 text-right">
                                    <span class="h5">
                                        <strong>Total:</strong>
                                        <span class="badge badge-primary" id="total-ventas-js">
                                            $<?= number_format($total_ventas, 2, ',', '.') ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<script>

    function actualizarTotal() {
        var total = 0;
        // La columna de precio es la 6 (índice 6, empieza en 0)
        table.rows({
            search: 'applied'
        }).every(function() {
            var data = this.data();
            // Extrae el número del badge
            var precio = $(data[6]).text().replace(/[^0-9,.-]+/g, "").replace('.', '').replace(',',
                '.');
            total += parseFloat(precio) || 0;
        });
        $('#total-ventas-js').text('$' + total.toLocaleString('es-ES', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    }

    table.on('draw', actualizarTotal);
    table.on('search', actualizarTotal);
    actualizarTotal();

</script>
<script src="datatable.js"></script>
<?php include('../layout/parte2.php'); ?>