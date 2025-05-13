<?php

include ('../../config.php');

header('Content-Type: application/json');

// Obtener el ID del proveedor
$id_proveedor = $_POST['id'] ?? null;

// Verificar que se haya pasado el ID del proveedor
if (!$id_proveedor || !is_numeric($id_proveedor)) {
    echo json_encode(['success' => false, 'message' => 'ID del proveedor no proporcionado o no válido.']);
    exit;
}

try {
    // Iniciar transacción para garantizar la consistencia
    $pdo->beginTransaction();

    // Eliminar el proveedor de la tabla proveedores
    $sentencia = $pdo->prepare("DELETE FROM proveedores WHERE id_proveedor = :id_proveedor");
    $sentencia->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);

    if ($sentencia->execute()) {
        // Confirmar la transacción
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'El proveedor se eliminó correctamente.']);
    } else {
        // Revertir la transacción en caso de error
        $pdo->rollBack();

        echo json_encode(['success' => false, 'message' => 'Error al eliminar el proveedor.']);
    }
} catch (PDOException $e) {
    // Manejar errores y revertir la transacción
    $pdo->rollBack();

    echo json_encode(['success' => false, 'message' => 'Error al eliminar el proveedor: ' . $e->getMessage()]);
}