<?php
include ('../../config.php');

header('Content-Type: application/json');

// Obtener el ID del usuario
$id_usuario = $_POST['id_usuario'] ?? null;


// Verificar que se haya pasado el ID del usuario
if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'ID del usuario no proporcionado.']);
    exit;
}

try {
    // Iniciar transacción para garantizar la consistencia
    $pdo->beginTransaction();

    // Eliminar el usuario de la tabla usuarios
    $sentencia = $pdo->prepare("DELETE FROM usuariossistema WHERE id_usuario = :id_usuario");
    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    if ($sentencia->execute()) {
        // Confirmar la transacción
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'El usuario se eliminó correctamente.']);
    } else {
        // Revertir la transacción en caso de error
        $pdo->rollBack();

        echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario.']);
    }
} catch (PDOException $e) {
    // Manejar errores y revertir la transacción
    $pdo->rollBack();

    echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario: ' . $e->getMessage()]);
}
