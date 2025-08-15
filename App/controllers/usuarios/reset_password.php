<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../usuariosController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nuevaClave = $_POST['clave'] ?? '';

if (!$id || !$nuevaClave) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$usuariosController = new UsuariosController();
$ok = $usuariosController->cambiarClave($id, $nuevaClave);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la contraseña.']);
}
