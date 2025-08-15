<?php
require_once __DIR__ . '/../../controllers/solicitudesController.php';
header('Content-Type: application/json');
$controller = new SolicitudesController();
$reservas = $controller->listarReservasParaCalendario();
echo json_encode($reservas);