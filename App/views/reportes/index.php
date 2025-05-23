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

// 2. Miembros registrados por semana (semana actual)
$semana = date('W');
$dias_semana = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];
$miembros_por_dia = array_fill_keys(array_keys($dias_semana), 0);
$stmt = $pdo->prepare("SELECT DAYNAME(fecha_registro) as dia, COUNT(*) as cantidad FROM miembros WHERE YEAR(fecha_registro) = ? AND WEEK(fecha_registro, 1) = ? GROUP BY dia");
$stmt->execute([$anio, $semana]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $miembros_por_dia[$row['dia']] = (int)$row['cantidad'];
}


// 3. Clases más populares (por inscripciones)
// Clases más populares por cantidad de veces programadas
$stmt = $pdo->query("
    SELECT nombre, COUNT(*) AS veces_programada
    FROM clases
    GROUP BY nombre
    ORDER BY veces_programada DESC
    LIMIT 7
");
$clases_populares = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $clases_populares[] = [
        'nombre' => $row['nombre'],
        'inscritos' => (int)$row['veces_programada'] // Usamos 'inscritos' para reutilizar el JS
    ];
}
// 4. Total de miembros registrados
$stmt = $pdo->query("SELECT COUNT(*) as total FROM miembros");
$total_miembros = $stmt->fetchColumn();

// 5. Valor total de las renovaciones
$stmt = $pdo->query("
    SELECT SUM(t.precio) as total_renovaciones
    FROM renovaciones r
    JOIN membresias m ON r.id_membresia = m.id_membresia
    JOIN tiposmembresia t ON m.id_tipo_membresia = t.id_tipo_membresia
");
$total_renovaciones = $stmt->fetchColumn();

// 6. Miembros por género
$stmt = $pdo->query("SELECT genero, COUNT(*) as cantidad FROM miembros GROUP BY genero");
$miembros_genero = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 7. Clases programadas por día de la semana
$stmt = $pdo->query("
    SELECT dia_semana, COUNT(*) as cantidad
    FROM clases
    WHERE cancelada = 0
    GROUP BY dia_semana
    ORDER BY FIELD(dia_semana, 'lunes','martes','miércoles','jueves','viernes','sábado','domingo')
");
$clases_por_dia = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 8. Ingresos por mes (ventas)
$ingresos_por_mes = array_fill(1, 12, 0);
$stmt = $pdo->prepare("SELECT MONTH(fecha_venta) as mes, SUM(total) as ingresos FROM ventas WHERE YEAR(fecha_venta) = ? GROUP BY mes");
$stmt->execute([date('Y')]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $ingresos_por_mes[(int)$row['mes']] = (float)$row['ingresos'];
}

// 9. Productos más vendidos (top 5)
$stmt = $pdo->query("
    SELECT p.nombre, SUM(dv.cantidad) as cantidad
    FROM detallesventa dv
    JOIN productos p ON dv.id_producto = p.id_producto
    GROUP BY dv.id_producto
    ORDER BY cantidad DESC
    LIMIT 5
");
$productos_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 10. Stock bajo (top 5)
$stmt = $pdo->query("
    SELECT nombre, cantidad_stock
    FROM productos
    ORDER BY cantidad_stock ASC
    LIMIT 5
");
$productos_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

// KPI adicional: Entradas del día (historialaccesos)
$hoy = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) FROM historialaccesos WHERE DATE(fecha_entrada) = ?");
$stmt->execute([$hoy]);
$entradas_dia = $stmt->fetchColumn();
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
                                <i class="fas fa-sync-alt fa-2x"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Total Renovaciones</h6>
                            <div class="display-5 fw-bold">$<?= number_format($total_renovaciones, 2, ',', '.') ?></div>
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
                            Miembros registrados esta semana
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartMiembrosPorSemana" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-indigo text-white text-center">
                            Clases más populares
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartClasesPopulares" style="max-width: 100%; max-height: 260px;"></canvas>
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
                            Clases programadas por día de la semana
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center"
                            style="min-height:320px;">
                            <canvas id="chartClasesPorDia" style="max-width: 100%; max-height: 260px;"></canvas>
                        </div>
                    </div>
                </div>
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
                            Stock bajo (Top 5)
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

    // Miembros por semana
    const labelsSemana = <?= json_encode(array_values($dias_semana)) ?>;
    const dataSemana = <?= json_encode(array_values($miembros_por_dia)) ?>;
    new Chart(document.getElementById('chartMiembrosPorSemana'), {
        type: 'bar',
        data: {
            labels: labelsSemana,
            datasets: [{
                label: 'Miembros registrados',
                data: dataSemana,
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true
        }
    });

    // Clases más populares
    const labelsClases = <?= json_encode(array_column($clases_populares, 'nombre')) ?>;
    const dataClases = <?= json_encode(array_column($clases_populares, 'inscritos')) ?>;
    new Chart(document.getElementById('chartClasesPopulares'), {
        type: 'doughnut',
        data: {
            labels: labelsClases,
            datasets: [{
                label: 'Veces Programada',
                data: dataClases,
                backgroundColor: ['#0bbffb', '#28a745', '#ffc107', '#dc3545', '#6610f2',
                    '#fd7e14', '#20c997'
                ]
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
                backgroundColor: ['#0bbffb', '#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true
        }
    });

    // Clases programadas por día
    const labelsDias = <?= json_encode(array_column($clases_por_dia, 'dia_semana')) ?>;
    const dataDias = <?= json_encode(array_column($clases_por_dia, 'cantidad')) ?>;
    new Chart(document.getElementById('chartClasesPorDia'), {
        type: 'bar',
        data: {
            labels: labelsDias,
            datasets: [{
                label: 'Clases programadas',
                data: dataDias,
                backgroundColor: '#6610f2'
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