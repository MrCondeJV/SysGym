<?php
include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');

// 1. Miembros registrados por mes (año actual)
$anio = date('Y');
$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$miembros_por_mes = array_fill(1, 12, 0);
$stmt = $pdo->prepare("SELECT MONTH(fecha_registro) as mes, COUNT(*) as cantidad FROM miembros WHERE YEAR(fecha_registro) = ? GROUP BY mes");
$stmt->execute([$anio]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $miembros_por_mes[(int)$row['mes']] = (int)$row['cantidad'];
}

// 2. Miembros por estado (activo/inactivo)
$stmt = $pdo->query("SELECT estado, COUNT(*) as cantidad FROM miembros GROUP BY estado");
$miembros_estado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Membresías por tipo
$stmt = $pdo->query("
    SELECT tm.nombre, COUNT(*) as cantidad 
    FROM membresias m
    JOIN tiposmembresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
    GROUP BY m.id_tipo_membresia
");
$membresias_tipo = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Total de miembros registrados
$stmt = $pdo->query("SELECT COUNT(*) as total FROM miembros");
$total_miembros = $stmt->fetchColumn();

// 5. Valor total de las ventas
$stmt = $pdo->query("SELECT SUM(total) as total_ventas FROM ventas");
$total_ventas = $stmt->fetchColumn();

// 6. Miembros por género
$stmt = $pdo->query("SELECT genero, COUNT(*) as cantidad FROM miembros GROUP BY genero");
$miembros_genero = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 7. Ingresos por mes (ventas)
$ingresos_por_mes = array_fill(1, 12, 0);
$stmt = $pdo->prepare("SELECT MONTH(fecha_venta) as mes, SUM(total) as ingresos FROM ventas WHERE YEAR(fecha_venta) = ? GROUP BY mes");
$stmt->execute([date('Y')]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $ingresos_por_mes[(int)$row['mes']] = (float)$row['ingresos'];
}

// 8. Productos más vendidos (top 5)
$stmt = $pdo->query("
    SELECT p.nombre, SUM(dv.cantidad) as cantidad
    FROM detallesventa dv
    JOIN productos p ON dv.id_producto = p.id_producto
    GROUP BY dv.id_producto
    ORDER BY cantidad DESC
    LIMIT 5
");
$productos_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 9. Stock bajo (top 5)
$stmt = $pdo->query("
    SELECT nombre, cantidad_stock
    FROM productos
    WHERE cantidad_stock < stock_minimo
    ORDER BY cantidad_stock ASC
    LIMIT 5
");
$productos_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 10. Accesos por día (historialaccesos)
$hoy = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) FROM historialaccesos WHERE DATE(fecha_entrada) = ?");
$stmt->execute([$hoy]);
$entradas_dia = $stmt->fetchColumn();

// 11. Métodos de pago más utilizados
$stmt = $pdo->query("
    SELECT mp.nombre, COUNT(*) as cantidad
    FROM ventas v
    JOIN metodos_pago mp ON v.id_metodo_pago = mp.id_metodo
    GROUP BY v.id_metodo_pago
    ORDER BY cantidad DESC
");
$metodos_pago = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-10">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-chart-bar"></i> Reportes y <span class="font-weight-bold">Estadísticas</span>
                    </h1>
                    <p class="text-muted mt-2">
                        Visualiza los indicadores clave y el comportamiento de tu gimnasio en tiempo real.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card principal que agrupa todo -->
    <div class="card card-outline card-primary shadow mb-4" style="margin: 30px 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-pie"></i> Dashboard Estadístico</h3>
        </div>
        <div class="card-body">
            <!-- KPIs -->
            <div class="row mb-4 g-3 justify-content-center">
                <div class="col-md-3">
                    <div class="card border-0 shadow text-center bg-gradient-primary text-white h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-2">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Miembros Registrados</h6>
                            <div class="display-5 fw-bold"><?= number_format($total_miembros) ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow text-center bg-gradient-success text-white h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-2">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Total Ventas</h6>
                            <div class="display-5 fw-bold">$<?= number_format($total_ventas, 2, ',', '.') ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow text-center bg-gradient-maroon text-white h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-2">
                                <i class="fas fa-door-open fa-2x"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Entradas Hoy</h6>
                            <div class="display-5 fw-bold"><?= number_format($entradas_dia) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts agrupados en cards -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-primary text-white text-center">
                            Miembros registrados por mes (<?= date('Y') ?>)
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartMiembrosPorMes" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-primary text-white text-center">
                            Miembros por estado
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartMiembrosEstado" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-indigo text-white text-center">
                            Membresías por tipo
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartMembresiasTipo" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-indigo text-white text-center">
                            Miembros por género
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartMiembrosGenero" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-warning text-dark text-center">
                            Ingresos por mes
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartIngresosMes" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-warning text-dark text-center">
                            Métodos de pago más utilizados
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartMetodosPago" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-success text-white text-center">
                            Productos más vendidos
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartProductosVendidos" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-danger text-white text-center">
                            Stock bajo (Productos bajo mínimo)
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartStockBajo" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/parte2.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Miembros por mes
    const labelsMeses = <?= json_encode($meses) ?>;
    const dataMeses = <?= json_encode(array_values($miembros_por_mes)) ?>;
    new Chart(document.getElementById('chartMiembrosPorMes'), {
        type: 'bar',
        data: {
            labels: labelsMeses,
            datasets: [{
                label: 'Miembros registrados',
                data: dataMeses,
                backgroundColor: '#0bbffb'
            }]
        },
        options: {
            responsive: true
        }
    });

    // Miembros por estado
    const labelsEstado = <?= json_encode(array_column($miembros_estado, 'estado')) ?>;
    const dataEstado = <?= json_encode(array_column($miembros_estado, 'cantidad')) ?>;
    new Chart(document.getElementById('chartMiembrosEstado'), {
        type: 'pie',
        data: {
            labels: labelsEstado,
            datasets: [{
                label: 'Miembros',
                data: dataEstado,
                backgroundColor: ['#28a745', '#dc3545', '#ffc107']
            }]
        },
        options: {
            responsive: true
        }
    });

    // Membresías por tipo
    const labelsMembresias = <?= json_encode(array_column($membresias_tipo, 'nombre')) ?>;
    const dataMembresias = <?= json_encode(array_column($membresias_tipo, 'cantidad')) ?>;
    new Chart(document.getElementById('chartMembresiasTipo'), {
        type: 'doughnut',
        data: {
            labels: labelsMembresias,
            datasets: [{
                label: 'Membresías',
                data: dataMembresias,
                backgroundColor: ['#0bbffb', '#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true
        }
    });

    // Miembros por género
    const labelsGenero = <?= json_encode(array_column($miembros_genero, 'genero')) ?>;
    const dataGenero = <?= json_encode(array_column($miembros_genero, 'cantidad')) ?>;
    new Chart(document.getElementById('chartMiembrosGenero'), {
        type: 'pie',
        data: {
            labels: labelsGenero,
            datasets: [{
                label: 'Miembros',
                data: dataGenero,
                backgroundColor: ['#0bbffb', '#28a745', '#ffc107']
            }]
        },
        options: {
            responsive: true
        }
    });

    // Ingresos por mes
    const dataIngresosMes = <?= json_encode(array_values($ingresos_por_mes)) ?>;
    new Chart(document.getElementById('chartIngresosMes'), {
        type: 'line',
        data: {
            labels: labelsMeses,
            datasets: [{
                label: 'Ingresos ($)',
                data: dataIngresosMes,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255,193,7,0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true
        }
    });

    // Métodos de pago
    const labelsMetodos = <?= json_encode(array_column($metodos_pago, 'nombre')) ?>;
    const dataMetodos = <?= json_encode(array_column($metodos_pago, 'cantidad')) ?>;
    new Chart(document.getElementById('chartMetodosPago'), {
        type: 'bar',
        data: {
            labels: labelsMetodos,
            datasets: [{
                label: 'Veces utilizado',
                data: dataMetodos,
                backgroundColor: '#6610f2'
            }]
        },
        options: {
            responsive: true
        }
    });

    // Productos más vendidos
    const labelsProductos = <?= json_encode(array_column($productos_vendidos, 'nombre')) ?>;
    const dataProductos = <?= json_encode(array_column($productos_vendidos, 'cantidad')) ?>;
    new Chart(document.getElementById('chartProductosVendidos'), {
        type: 'bar',
        data: {
            labels: labelsProductos,
            datasets: [{
                label: 'Cantidad vendida',
                data: dataProductos,
                backgroundColor: '#fd7e14'
            }]
        },
        options: {
            responsive: true
        }
    });

    // Stock bajo
    const labelsStock = <?= json_encode(array_column($productos_stock, 'nombre')) ?>;
    const dataStock = <?= json_encode(array_column($productos_stock, 'cantidad_stock')) ?>;
    new Chart(document.getElementById('chartStockBajo'), {
        type: 'bar',
        data: {
            labels: labelsStock,
            datasets: [{
                label: 'Stock actual',
                data: dataStock,
                backgroundColor: '#dc3545'
            }]
        },
        options: {
            responsive: true
        }
    });
});
</script>