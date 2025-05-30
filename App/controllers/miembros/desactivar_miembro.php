<?php

include ('../../config.php');

header('Content-Type: application/json');

// Obtener el ID del miembro
$id_miembro = $_POST['id'] ?? null;

// Verificar que se haya pasado el ID del miembro
if (!$id_miembro || !is_numeric($id_miembro)) {
    echo json_encode(['success' => false, 'message' => 'ID del miembro no proporcionado o no válido.']);
    exit;
}

try {
    // Cambiar el estado del miembro a 'inactivo'
    $stmt = $pdo->prepare("UPDATE miembros SET estado = 'inactivo' WHERE id_miembro = :id_miembro");
    $stmt->bindParam(':id_miembro', $id_miembro, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'El miembro fue desactivado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo desactivar el miembro.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al desactivar el miembro: ' . $e->getMessage()]);
}
?>