<?php
include('../../config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM tiposmembresia WHERE id_tipo_membresia = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['mensaje'] = "Tipo de membresía eliminado correctamente";
            $_SESSION['icono'] = 'success';
            $_SESSION['titulo'] = 'Éxito';
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el registro']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}