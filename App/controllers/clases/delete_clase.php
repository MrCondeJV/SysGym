<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID de la clase a eliminar (asumiendo que se pasa por GET o POST)
// Es común pasar el ID por GET en un enlace de "Eliminar"
$id_clase = $_GET['id'] ?? $_POST['id_clase'] ?? null;

// Validar ID de la clase
if (!$id_clase || !is_numeric($id_clase)) {
    $_SESSION['mensaje'] = "Error: ID de clase no válido para eliminar.";
    $_SESSION['icono'] = "error";
    // Asumiendo que la vista de índice de clases está en App/views/clases/index.php
    header("Location: $URL/App/views/clases/index.php");
    exit();
}

// Consulta para eliminar la clase
$sql = "DELETE FROM clases WHERE id_clase = :id_clase";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_clase', $id_clase, PDO::PARAM_INT);

try {
    if ($stmt->execute()) {
        // Verificar si se eliminó alguna fila
        if ($stmt->rowCount() > 0) {
            $_SESSION['mensaje'] = "Clase eliminada correctamente.";
            $_SESSION['icono'] = 'success';
        } else {
            $_SESSION['mensaje'] = "La clase con ID $id_clase no fue encontrada para eliminar.";
            $_SESSION['icono'] = 'warning'; // O error, dependiendo de la lógica deseada
        }
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la clase.";
        $_SESSION['icono'] = 'error';
    }
} catch (PDOException $e) {
    // Capturar errores de base de datos, por ejemplo, restricciones de clave foránea
    if ($e->getCode() == '23000') { // Código SQLSTATE común para violación de restricción de integridad
         $_SESSION['mensaje'] = "Error: No se puede eliminar la clase porque tiene registros asociados (ej. inscripciones).";
         $_SESSION['icono'] = 'error';
    } else {
        $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
        $_SESSION['icono'] = 'error';
    }
}

// Redirigir a la página principal de clases
header("Location: $URL/App/views/clases/index.php");
exit();
?>
