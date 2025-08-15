<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../solicitudesController.php';
require_once __DIR__ . '/../usuariosController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$accion = $_POST['accion'] ?? '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
// Aquí deberías obtener el ID del usuario autenticado, por ejemplo desde $_SESSION
$id_usuario = $_SESSION['id_usuario'] ?? 1; // Fallback a 1 para pruebas

$solicitudesController = new SolicitudesController();

if ($accion === 'aprobar') {
    $ok = $solicitudesController->aprobar($id, $id_usuario);
    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'La solicitud fue aprobada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo aprobar la solicitud.']);
    }
    exit;
}
if ($accion === 'rechazar') {
    $ok = $solicitudesController->rechazar($id, $id_usuario);
    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'La solicitud fue rechazada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo rechazar la solicitud.']);
    }
    exit;
}
echo json_encode(['success' => false, 'message' => 'Acción no válida']);
