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
    // Iniciar transacción para garantizar la consistencia
    $pdo->beginTransaction();

    // Eliminar las huellas digitales asociadas al miembro (si existen)
    $stmt_huellas = $pdo->prepare("DELETE FROM huellasdigitales WHERE id_miembro = :id_miembro");
    $stmt_huellas->bindParam(':id_miembro', $id_miembro, PDO::PARAM_INT);
    $stmt_huellas->execute();

    // Eliminar el miembro de la tabla miembros
    $stmt_miembro = $pdo->prepare("DELETE FROM miembros WHERE id_miembro = :id_miembro");
    $stmt_miembro->bindParam(':id_miembro', $id_miembro, PDO::PARAM_INT);

    if ($stmt_miembro->execute()) {
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'El miembro se eliminó correctamente.']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el miembro.']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el miembro: ' . $e->getMessage()]);
}
?>