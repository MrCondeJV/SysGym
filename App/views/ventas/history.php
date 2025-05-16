<?php
// filepath: c:\xampp\htdocs\SysGym\App\views\ventas\history.php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/ventas/historial_ventas.php');
include('../layout/sesion.php');
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
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Ventas Registradas</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm text-center" id="tabla_historial_ventas">
                                    <thead>
                                        <tr>
                                            <th>ID Venta</th>
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
                                                <td colspan="5">No hay ventas registradas.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>