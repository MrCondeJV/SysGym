<?php
include('../../config.php');
include('../layout/parte1.php');
require_once __DIR__ . '/../../controllers/solicitudesController.php';
$solicitudesController = new SolicitudesController();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo '<div class="alert alert-danger m-4">ID de solicitud no válido.</div>';
    include('../layout/parte2.php');
    exit;
}
$solicitud = $solicitudesController->obtener($id);
if (!$solicitud) {
    echo '<div class="alert alert-warning m-4">Solicitud no encontrada.</div>';
    include('../layout/parte2.php');
    exit;
}
$historial = $solicitudesController->historialEstados($id);
$adjuntos = $solicitudesController->listarAdjuntos($id);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg"
                        style="background: linear-gradient(90deg,rgb(21, 41, 124),rgb(21, 41, 150)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.2rem; letter-spacing: 2px;">
                            <i class="fas fa-ticket-alt fa-lg"></i> Detalle de Solicitud
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12 mx-auto">
                    <div class="card card-outline card-primary shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Solicitud #<?php echo $solicitud['id']; ?></h3>
                            <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h5 class="font-weight-bold">Título</h5>
                                    <p><?php echo htmlspecialchars($solicitud['titulo'] ?? $solicitud['motivo'] ?? ''); ?></p>
                                    <h5 class="font-weight-bold">Descripción</h5>
                                    <p><?php echo nl2br(htmlspecialchars($solicitud['descripcion'] ?? '')); ?></p>
                                    <h5 class="font-weight-bold">Solicitante</h5>
                                    <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($solicitud['usuario_nombre'] ?? $solicitud['nombre_solicitante'] ?? 'N/A'); ?></p>
                                    <h5 class="font-weight-bold">Dependencia</h5>
                                    <p><i class="fas fa-building"></i> <?php echo htmlspecialchars($solicitud['dependencia_nombre'] ?? ''); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h5 class="font-weight-bold">Aula Solicitada</h5>
                                    <p><i class="fas fa-door-open"></i> <?php echo htmlspecialchars($solicitud['aula_nombre'] ?? ''); ?></p>
                                    <h5 class="font-weight-bold">Fechas</h5>
                                    <p><i class="fas fa-calendar-alt"></i> <b>Inicio:</b> <?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_inicio'])); ?><br>
                                        <i class="fas fa-calendar-check"></i> <b>Fin:</b> <?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_fin'])); ?>
                                    </p>
                                    <h5 class="font-weight-bold">Asistentes</h5>
                                    <p><i class="fas fa-users"></i> <?php echo htmlspecialchars($solicitud['asistentes'] ?? ''); ?></p>
                                    <h5 class="font-weight-bold">Estado actual</h5>
                                    <span class="badge badge-<?php echo ($solicitud['estado_nombre'] == 'aprobada') ? 'success' : (($solicitud['estado_nombre'] == 'rechazada') ? 'danger' : 'secondary'); ?> p-2">
                                        <?php echo ucfirst($solicitud['estado_nombre']); ?>
                                    </span>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h5 class="font-weight-bold"><i class="fas fa-history"></i> Historial de Estados</h5>
                                    <?php if ($historial && count($historial) > 0): ?>
                                        <ul class="list-group">
                                            <?php foreach ($historial as $h): ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <b><?php echo ucfirst($h['estado_nombre']); ?></b> por <?php echo htmlspecialchars($h['usuario_nombre']); ?>
                                                    </span>
                                                    <?php
                                                    $fecha_hist = $h['cambiado_en'] ?? '';
                                                    ?>
                                                    <?php if (!empty($fecha_hist) && $fecha_hist !== '0000-00-00 00:00:00'): ?>
                                                        <span class="text-muted" style="font-size:0.95em;">
                                                            <i class="far fa-clock"></i> <?php
                                                                                            setlocale(LC_TIME, 'es_ES.UTF-8', 'spanish');
                                                                                            $dt_hist = strtotime($fecha_hist);
                                                                                            echo strftime('%d/%m/%Y %H:%M', $dt_hist);
                                                                                            ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted">Sin historial de cambios.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h5 class="font-weight-bold"><i class="fas fa-paperclip"></i> Adjuntos</h5>
                                    <?php if ($adjuntos && count($adjuntos) > 0): ?>
                                        <ul class="list-group">
                                            <?php foreach ($adjuntos as $adj): ?>
                                                <li class="list-group-item">
                                                    <?php
                                                    $nombre_archivo = $adj['nombre_archivo'] ?? '';
                                                    $fecha = $adj['subido_en'] ?? '';
                                                    // Asume que los archivos están en /public/adjuntos/ si se guardan en disco
                                                    $ruta = $URL . '/public/adjuntos/' . rawurlencode($nombre_archivo);
                                                    ?>
                                                    <a href="<?php echo $ruta; ?>" target="_blank">
                                                        <i class="fas fa-file"></i> <?php echo htmlspecialchars($nombre_archivo); ?>
                                                    </a>
                                                    <?php if (!empty($fecha) && $fecha !== '0000-00-00 00:00:00'): ?>
                                                        <span class="text-muted float-right" style="font-size:0.95em;">
                                                            <i class="far fa-clock"></i> <?php
                                                                                            setlocale(LC_TIME, 'es_ES.UTF-8', 'spanish');
                                                                                            $dt = strtotime($fecha);
                                                                                            echo strftime('%d/%m/%Y %H:%M', $dt);
                                                                                            ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted">Sin archivos adjuntos.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>