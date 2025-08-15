<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../usuariosController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$data = $_POST;

$usuariosController = new UsuariosController();
$ok = $usuariosController->actualizar($id, $data);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el usuario.']);
}
