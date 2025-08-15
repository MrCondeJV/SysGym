<?php
include('App/config.php');
//include('App/views/layout/sesion.php');
include('App/views/layout/parte1.php');


date_default_timezone_set('America/Bogota');

// Consulta para obtener los últimos accesos
$stmt = $pdo->prepare("
    SELECT ha.fecha_entrada, m.nombres, m.apellidos
    FROM historialaccesos ha
    INNER JOIN miembros m ON ha.id_miembro = m.id_miembro
    ORDER BY ha.fecha_entrada DESC
    LIMIT 5
");
$stmt->execute();
$ultimos_accesos = $stmt->fetchAll(PDO::FETCH_ASSOC);

//productos mas vendidos
$stmt = $pdo->prepare("
    SELECT 
        p.nombre,
        p.codigo_barras,
        SUM(dv.cantidad) AS total_vendido,
        p.precio,
        (SUM(dv.cantidad) * p.precio) AS ingresos_generados
    FROM detallesventa dv
    JOIN productos p ON dv.id_producto = p.id_producto
    GROUP BY dv.id_producto
    ORDER BY total_vendido DESC
    LIMIT 5
");
$stmt->execute();
$productos_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Consulta para obtener ventas recientes con nombre del usuario que realizó la venta
$stmt = $pdo->query("
    SELECT 
        v.fecha_venta, 
        CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario,
        v.total,
        v.numero_factura
    FROM ventas v
    LEFT JOIN usuariossistema u ON v.id_usuario = u.id_usuario
    ORDER BY v.fecha_venta DESC
    LIMIT 5
");
$ventas_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para contar accesos hoy
$hoy = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) FROM historialaccesos WHERE DATE(fecha_entrada) = ?");
$stmt->execute([$hoy]);
$accesos_hoy = $stmt->fetchColumn();
?>

<style>
@media (max-width: 576px) {
    .nombre-conductor span {
        display: block;
        word-break: break-word;
    }
}
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 3): ?>
                <!-- SOLO VEEDOR: Tabla de personas que han ingresado -->
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-8 col-md-10">
                        <div class="card card-indigo shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-users"></i> Últimos ingresos registrados</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Fecha y Hora Entrada</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($ultimos_accesos)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">No hay ingresos recientes.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($ultimos_accesos as $i => $acceso): ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><?= htmlspecialchars($acceso['nombres'] . ' ' . $acceso['apellidos']) ?></td>
                                                        <td><?= date('d/m/Y H:i', strtotime($acceso['fecha_entrada'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Todo el dashboard visual actual -->
                <!-- ...todo el contenido visual actual que ya tienes... -->
                <!-- Título de bienvenida animado -->
                <style>
                @keyframes gradientMoveFormal {
                    0% {
                        background-position: 0% 50%;
                    }
                    100% {
                        background-position: 100% 50%;
                    }
                }
                .titulo-bienvenida-corporativo {
                    background: #232526;
                    color: #fff;
                    font-weight: 700;
                    font-size: 1.15rem;
                    letter-spacing: 1.2px;
                    box-shadow: 0 2px 12px rgba(14, 148, 160, 0.08);
                    border-radius: 0.8rem;
                    padding: 0.8rem 1rem 0.8rem 1.2rem;
                    margin-bottom: 1.2rem;
                    position: relative;
                    overflow: hidden;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.7rem;
                    border-left: 6px solid #FFFFFF;
                }
                .titulo-bienvenida-corporativo .icon-corporate {
                    font-size: 1.5rem;
                    color: #e0e0e0;
                    margin-right: 0.5rem;
                    opacity: 0.92;
                }
                .titulo-bienvenida-corporativo .main-text {
                    display: inline-block;
                    background: linear-gradient(90deg, #e0e0e0, #fff, #b2bec3, #e0e0e0);
                    background-size: 250% 100%;
                    color: inherit;
                    -webkit-background-clip: text;
                    background-clip: text;
                    -webkit-text-fill-color: transparent;
                    animation: gradientMoveFormal 4s linear infinite alternate;
                    font-weight: bold;
                    letter-spacing: 1.2px;
                }
                .titulo-bienvenida-corporativo .sub {
                    display: block;
                    font-size: 0.98rem;
                    font-weight: 400;
                    margin-top: 0.2rem;
                    letter-spacing: 0.5px;
                    color: #eafaff;
                    opacity: 0;
                    animation: fadeUp 1.1s 0.5s cubic-bezier(.77, 0, .18, 1) forwards;
                }
                @media (max-width: 576px) {
                    .titulo-bienvenida-corporativo {
                        font-size: 0.95rem;
                        padding: 0.6rem 0.5rem 0.6rem 0.7rem;
                        border-radius: 0.6rem;
                        gap: 0.4rem;
                    }
                    .titulo-bienvenida-corporativo .icon-corporate {
                        font-size: 1.1rem;
                    }
                }
                @keyframes fadeUp {
                    0% {
                        opacity: 0;
                        transform: translateY(18px);
                    }
                    100% {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                </style>

                <div class="row mb-3 mt-4">
                    <div class="col">
                        <div class="titulo-bienvenida-corporativo">
                            <i class="fas fa-dumbbell icon-corporate"></i>
                            <span class="main-text">BIENVENIDO AL GIMNASIO </span>
                            <span class="sub">¡Entrena con excelencia y profesionalismo!</span>
                        </div>
                    </div>
                </div>

                <!-- Tarjetas de métricas -->
                <div class="row">
                    <!-- Miembros Activos -->
                    <div class="col-lg-3 col-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title">Miembros Activos</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-users fa-3x text-danger"></i>
                                </div>
                                <h3 class="fw-bold"><?= htmlspecialchars($miembros_activos) ?></h3>
                                <small class="text-success">+<?= htmlspecialchars($miembros_activos_semana) ?> esta semana</small>
                            </div>
                        </div>
                    </div>
                    <!-- Accesos Hoy -->
                    <div class="col-lg-3 col-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title">Accesos Hoy</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-door-open fa-3x text-primary"></i>
                                </div>
                                <h3 class="fw-bold"><?= htmlspecialchars($accesos_hoy) ?></h3>
                                <small>Entradas registradas</small>
                            </div>
                        </div>
                    </div>
                    <!-- Ingresos -->
                    <div class="col-lg-3 col-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title">Ingresos</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-dollar-sign fa-3x text-warning"></i>
                                </div>
                                <h3 class="fw-bold">$<?= number_format($ingresos_mes, 2) ?></h3>
                                <small>Este mes</small>
                            </div>
                        </div>
                    </div>
                    <!-- Nuevos Miembros -->
                    <div class="col-lg-3 col-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title">Nuevos Miembros</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-user-plus fa-3x text-success"></i>
                                </div>
                                <h3 class="fw-bold"><?= htmlspecialchars($nuevos_miembros) ?></h3>
                                <small class="text-success"><?= $porcentaje_nuevos >= 0 ? '+' : '' ?><?= htmlspecialchars($porcentaje_nuevos) ?>%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de información -->
                <div class="row mt-4">
                    <!-- Productos Más Vendidos -->
                    <div class="col-lg-6">
                        <div class="card card-primary shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-trophy"></i> Productos Más Vendidos
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Producto</th>
                                                <th class="text-center">Unidades</th>
                                                <th class="text-end">Ingresos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($productos_vendidos)): ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">No hay datos de ventas</td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($productos_vendidos as $producto): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($producto['nombre']) ?></strong>
                                                    <small class="d-block text-muted">Código: <?= htmlspecialchars($producto['codigo_barras']) ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary rounded-pill">
                                                        <?= (int)$producto['total_vendido'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-end text-success fw-bold">
                                                    $<?= number_format($producto['ingresos_generados'], 2) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Últimos accesos -->
                    <div class="col-lg-6">
                        <div class="card card-indigo shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title">Últimos 5 ingresos</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php if (empty($ultimos_accesos)): ?>
                                    <li class="list-group-item text-center text-muted">
                                        No hay ingresos recientes.
                                    </li>
                                    <?php else: ?>
                                    <?php foreach ($ultimos_accesos as $acceso): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div>
                                            <strong><?= htmlspecialchars($acceso['nombres'] . ' ' . $acceso['apellidos']) ?></strong>
                                            <small class="d-block text-muted">
                                                <?= date('d/m/Y H:i', strtotime($acceso['fecha_entrada'])) ?>
                                            </small>
                                        </div>
                                        <span class="badge badge-primary rounded-pill">
                                            Ingresó
                                        </span>
                                    </li>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ventas recientes -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-success shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title">Ventas Recientes</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Factura</th>
                                                <th>Realizado por</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($ventas_recientes)): ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No hay ventas recientes</td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($ventas_recientes as $venta): ?>
                                            <tr>
                                                <td><?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></td>
                                                <td><?= htmlspecialchars($venta['numero_factura'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($venta['nombre_usuario'] ?? 'Sistema') ?></td>
                                                <td>$<?= number_format($venta['total'], 2) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción Rápida -->
                <div class="row mt-3">
                    <div class="col-lg-4 col-6">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <a href="App/views/miembros/create.php" class="btn btn-primary w-100">
                                    <i class="fas fa-user-plus"></i> Nuevo Miembro
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <a href="App/views/ventas/index.php" class="btn btn-success w-100">
                                    <i class="fas fa-cash-register"></i> Nueva Venta
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <a href="App/views/pagos/historial_renovaciones_general.php" class="btn btn-warning w-100">
                                    <i class="fas fa-money-bill-wave"></i> Ver Pagos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include('App/views/layout/parte2.php'); ?>