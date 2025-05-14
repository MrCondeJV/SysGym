<?php
include ('../../config.php');

header('Content-Type: application/json');

// Obtener el ID de la sala
$id_sala = $_POST['id'] ?? null;

// Verificar que se haya pasado el ID de la sala
if (!$id_sala) {
    echo json_encode(['success' => false, 'message' => 'ID de la sala no proporcionado.']);
    exit;
}

try {
    // Iniciar transacción para garantizar la consistencia
    $pdo->beginTransaction();

    // Eliminar la sala de la tabla salas
    $sentencia = $pdo->prepare("DELETE FROM salas WHERE id_sala = :id_sala");
    $sentencia->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);

    if ($sentencia->execute()) {
        // Confirmar la transacción
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'La sala se eliminó correctamente.']);
    } else {
        // Revertir la transacción en caso de error
        $pdo->rollBack();

        echo json_encode(['success' => false, 'message' => 'Error al eliminar la sala.']);
    }
} catch (PDOException $e) {
    // Manejar errores y revertir la transacción
    $pdo->rollBack();

    echo json_encode(['success' => false, 'message' => 'Error al eliminar la sala: ' . $e->getMessage()]);
}