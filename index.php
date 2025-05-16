<?php
include('App/config.php');
include('App/views/layout/sesion.php');
include('App/views/layout/parte1.php');
include_once('App/controllers/miembros/contar_activos.php'); // Debe definir $miembros_activos y $miembros_activos_semana
include_once('App/controllers/clases/clases_hoy.php'); // Debe definir $clases_hoy, $clases_manana, $clases_tarde
include_once('App/controllers/ventas/ingresos_mes.php'); // Debe definir $ingresos_mes
include_once('App/controllers/miembros/nuevos_mes.php'); // Debe definir $nuevos_miembros y $porcentaje_nuevos

?>

<style>
@media (max-width: 576px) {
    .nombre-conductor span {
        display: block;
        word-break: break-word;
    }
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Título de bienvenida con margen superior -->
            <div class="row mb-3 mt-5">
                <!-- Agregamos mt-5 para margen superior -->
                <div class="col">
                    <div class="alert alert-dark text-center font-weight-bold mb-0 py-3 rounded">
                        BIENVENIDO AL GYMNASIO ESFIM
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

                <!-- Clases Hoy -->
                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title">Clases Hoy</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-calendar-check fa-3x text-primary"></i>
                            </div>
                            <h3 class="fw-bold"><?= htmlspecialchars($clases_hoy) ?></h3>
                            <small><?= htmlspecialchars($clases_manana) ?> mañana, <?= htmlspecialchars($clases_tarde) ?> tarde</small>
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
                                <i class="fas fa-heartbeat fa-3x text-success"></i>
                            </div>
                            <h3 class="fw-bold"><?= htmlspecialchars($nuevos_miembros) ?></h3>
                            <small class="text-success"><?= $porcentaje_nuevos >= 0 ? '+' : '' ?><?= htmlspecialchars($porcentaje_nuevos) ?>%</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Próximas clases -->
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="card card-info shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-calendar-alt text-info me-2"></i>Próximas Clases
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <div>
                                        <strong>8:00 AM</strong> - Spinning
                                        <small class="d-block text-muted">Instructor: Carlos</small>
                                    </div>
                                    <span class="badge badge-danger rounded-pill">12/15</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <div>
                                        <strong>10:00 AM</strong> - CrossFit
                                        <small class="d-block text-muted">Instructor: Laura</small>
                                    </div>
                                    <span class="badge badge-success rounded-pill">8/10</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Retos activos -->
                <div class="col-lg-6">
                    <div class="card card-warning shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-trophy text-warning me-2"></i>Retos Activos</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <div>
                                        <strong>Maratón 30 días</strong>
                                        <small class="d-block text-muted">Running</small>
                                    </div>
                                    <span class="badge badge-primary rounded-pill">45 participantes</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <div>
                                        <strong>Summer Body</strong>
                                        <small class="d-block text-muted">Pérdida de peso</small>
                                    </div>
                                    <span class="badge badge-primary rounded-pill">63 participantes</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Botones de Acción Rápida -->
            <div class="row mt-3">
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <button class="btn btn-primary w-100">Agregar Nueva Clase</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <button class="btn btn-success w-100">Ver Nuevos Miembros</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <button class="btn btn-warning w-100">Ver Pagos Pendientes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

<?php include('App/views/layout/parte2.php'); ?>