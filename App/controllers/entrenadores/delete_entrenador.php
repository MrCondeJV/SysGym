<?php


include ('../../config.php');

header('Content-Type: application/json');

// Obtener el ID del entrenador
$id_entrenador = $_POST['id'] ?? null;

// Verificar que se haya pasado el ID del entrenador
if (!$id_entrenador || !is_numeric($id_entrenador)) {
    echo json_encode(['success' => false, 'message' => 'ID del entrenador no proporcionado o no válido.']);
    exit;
}

try {
    // Iniciar transacción para garantizar la consistencia
    $pdo->beginTransaction();

    // Eliminar el entrenador de la tabla entrenadores
    $sentencia = $pdo->prepare("DELETE FROM entrenadores WHERE id_entrenador = :id_entrenador");
    $sentencia->bindParam(':id_entrenador', $id_entrenador, PDO::PARAM_INT);

    if ($sentencia->execute()) {
        // Confirmar la transacción
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'El entrenador se eliminó correctamente.']);
    } else {
        // Revertir la transacción en caso de error
        $pdo->rollBack();

        echo json_encode(['success' => false, 'message' => 'Error al eliminar el entrenador.']);
    }
} catch (PDOException $e) {
    // Manejar errores y revertir la transacción
    $pdo->rollBack();

    echo json_encode(['success' => false, 'message' => 'Error al eliminar el entrenador: ' . $e->getMessage()]);
}