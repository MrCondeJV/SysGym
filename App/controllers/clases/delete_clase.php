<?php
include('../../config.php');

// Configurar cabecera para JSON
header('Content-Type: application/json');

// Iniciar sesión si no está activa (opcional para esta lógica AJAX)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_clase = $_POST['id'] ?? null;

if (!$id_clase || !is_numeric($id_clase)) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de clase no válido.'
    ]);
    exit();
}

$sql = "DELETE FROM clases WHERE id_clase = :id_clase";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_clase', $id_clase, PDO::PARAM_INT);

try {
    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Clase eliminada correctamente.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'La clase no fue encontrada.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo ejecutar la eliminación.'
        ]);
    }
} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
        echo json_encode([
            'success' => false,
            'message' => 'No se puede eliminar la clase porque tiene registros asociados.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error en la base de datos: ' . $e->getMessage()
        ]);
    }
}
