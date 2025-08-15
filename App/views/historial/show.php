<?php
include('../../config.php');
include('../layout/parte1.php');
require_once __DIR__ . '/../../controllers/historialController.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$historialController = new HistorialController();
$detalle = $historialController->obtener($id);

if (!$detalle) {
    echo '<div class="alert alert-warning m-4">Registro de historial no encontrado.</div>';
    include('../layout/parte2.php');
    exit;
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg"
                        style="background: linear-gradient(90deg,rgb(21, 41, 124),rgb(21, 41, 150)); color: #fff; font-family: Arial, sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.2rem; letter-spacing: 2px;">
                            <i class="fas fa-eye fa-lg"></i> Detalle del Historial
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="card card-outline card-primary shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Registro #<?php echo $detalle['id']; ?></h3>
                            <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
                        </div>
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item bg-light"><strong>Solicitud:</strong> <span class="text-primary"><?php echo htmlspecialchars($detalle['solicitud_titulo'] ?? ''); ?></span></li>
                                            <li class="list-group-item"><strong>Reserva:</strong> <span><?php echo htmlspecialchars($detalle['codigo_reserva'] ?? ''); ?></span></li>
                                            <li class="list-group-item"><strong>Persona que solicitó:</strong> <span><?php echo htmlspecialchars($detalle['solicitante_nombre'] ?? ''); ?></span></li>
                                            <li class="list-group-item"><strong>Unidad/Área:</strong> <span><?php echo htmlspecialchars($detalle['solicitante_unidad'] ?? ''); ?></span></li>
                                            <li class="list-group-item"><strong>Documento del solicitante:</strong> <span><?php echo htmlspecialchars($detalle['solicitante_documento'] ?? ''); ?></span></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item bg-light"><strong>Estado anterior:</strong> <span class="badge badge-secondary"><?php echo htmlspecialchars($detalle['estado_anterior_nombre'] ?? ''); ?></span></li>
                                            <li class="list-group-item"><strong>Estado nuevo:</strong> <span class="badge badge-primary"><?php echo htmlspecialchars($detalle['estado_nuevo_nombre'] ?? ''); ?></span></li>
                                            <li class="list-group-item"><strong>Usuario que realizó el cambio:</strong> <span><?php echo htmlspecialchars($detalle['usuario_nombre'] ?? ''); ?></span></li>
                                            <li class="list-group-item"><strong>Fecha de cambio:</strong> <span><?php echo !empty($detalle['cambiado_en']) ? date('d/m/Y H:i', strtotime($detalle['cambiado_en'])) : ''; ?></span></li>
                                            <li class="list-group-item"><strong>Comentario:</strong> <span><?php echo htmlspecialchars($detalle['comentario'] ?? ''); ?></span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header py-2"><strong><i class="fas fa-paperclip"></i> Documento adjunto de la solicitud</strong></div>
                                            <div class="card-body">
                                                <?php if (!empty($detalle['adjunto_nombre'])) {
                                                    $adjunto_url = $URL . '/public/adjuntos/' . rawurlencode($detalle['adjunto_nombre']);
                                                    $adjunto_nombre = htmlspecialchars($detalle['adjunto_nombre']);
                                                ?>
                                                    <a href="#" class="btn btn-outline-primary btn-sm" onclick="verAdjunto('<?php echo $adjunto_url; ?>', '<?php echo $adjunto_nombre; ?>'); return false;">
                                                        <i class="fas fa-eye"></i> Visualizar documento
                                                    </a>
                                                    <?php if (!empty($detalle['adjunto_fecha'])) {
                                                        $fechaAdj = date('d/m/Y H:i', strtotime($detalle['adjunto_fecha']));
                                                    ?>
                                                        <span class="text-muted ml-2" style="font-size:0.95em;">
                                                            <i class="far fa-clock"></i> <?php echo $fechaAdj; ?>
                                                        </span>
                                                    <?php }
                                                } else {
                                                    echo '<span class="text-muted">Sin adjunto</span>';
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal visor de adjunto -->
                            <div class="modal fade" id="modalAdjunto" tabindex="-1" role="dialog" aria-labelledby="modalAdjuntoLabel" aria-hidden="true">
                              <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-primary text-white py-2">
                                    <h5 class="modal-title" id="modalAdjuntoLabel"><i class="fas fa-file"></i> Visualizador de documento</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body" style="min-height:70vh;">
                                    <div id="visorAdjunto" style="width:100%;height:65vh;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border:1px solid #e0e0e0;border-radius:8px;overflow:auto;"></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <script>
                            function verAdjunto(url, nombre) {
                                var visor = document.getElementById('visorAdjunto');
                                visor.innerHTML = '';
                                var ext = nombre.split('.').pop().toLowerCase();
                                if (ext === 'pdf') {
                                    visor.innerHTML = '<iframe src="' + url + '" style="width:100%;height:100%;border:none;"></iframe>';
                                } else if(['jpg','jpeg','png','gif','bmp','webp'].includes(ext)) {
                                    visor.innerHTML = '<img src="' + url + '" alt="Adjunto" style="max-width:100%;max-height:60vh;display:block;margin:auto;">';
                                } else {
                                    visor.innerHTML = '<a href="' + url + '" target="_blank">Descargar archivo</a>';
                                }
                                $('#modalAdjunto').modal('show');
                            }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>