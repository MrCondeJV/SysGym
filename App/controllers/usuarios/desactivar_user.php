<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/usuariosController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no válido']);
    exit;
}

$usuariosController = new UsuariosController();
$result = $usuariosController->eliminar($id); // Usa la función que pone activo=0

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Usuario desactivado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo desactivar el usuario.']);
}
