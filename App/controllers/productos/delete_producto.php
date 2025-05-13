<?php


include ('../../config.php');

header('Content-Type: application/json');

// Obtener el ID del producto
$id_producto = $_POST['id'] ?? null;

// Verificar que se haya pasado el ID del producto
if (!$id_producto || !is_numeric($id_producto)) {
    echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado o no válido.']);
    exit;
}

try {
    // Iniciar transacción para garantizar la consistencia
    $pdo->beginTransaction();

    // Eliminar el producto de la tabla productos
    $sentencia = $pdo->prepare("DELETE FROM productos WHERE id_producto = :id_producto");
    $sentencia->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);

    if ($sentencia->execute()) {
        // Confirmar la transacción
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'El producto se eliminó correctamente.']);
    } else {
        // Revertir la transacción en caso de error
        $pdo->rollBack();

        echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto.']);
    }
} catch (PDOException $e) {
    // Manejar errores y revertir la transacción
    $pdo->rollBack();

    echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . $e->getMessage()]);
}