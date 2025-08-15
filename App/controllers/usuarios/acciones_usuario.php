<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../controllers/usuariosController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$accion = $_POST['accion'] ?? '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$usuariosController = new UsuariosController();

if ($accion === 'desactivar') {
    $ok = $usuariosController->desactivar($id);
    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'Usuario desactivado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo desactivar el usuario.']);
    }
    exit;
}
if ($accion === 'reactivar') {
    $ok = $usuariosController->reactivar($id);
    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'Usuario reactivado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo reactivar el usuario.']);
    }
    exit;
}
echo json_encode(['success' => false, 'message' => 'Acción no válida']);
