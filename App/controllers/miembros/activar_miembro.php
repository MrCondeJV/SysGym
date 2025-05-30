<?php
include('../../config.php');
header('Content-Type: application/json');

$id_miembro = $_POST['id'] ?? null;

if (!$id_miembro || !is_numeric($id_miembro)) {
    echo json_encode(['success' => false, 'message' => 'ID del miembro no proporcionado o no válido.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE miembros SET estado = 'activo' WHERE id_miembro = :id_miembro");
    $stmt->bindParam(':id_miembro', $id_miembro, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'El miembro fue activado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo activar el miembro.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al activar el miembro: ' . $e->getMessage()]);
}
?>