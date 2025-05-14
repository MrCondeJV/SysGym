<?php
include ('../../config.php');
session_start();

// Guardar datos del formulario para repoblación en caso de error
$_SESSION['old_data'] = $_POST;

// Obtener datos del formulario
$id_sala = $_POST['id_sala'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$capacidad = trim($_POST['capacidad'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

// Validar ID de la sala
if (!$id_sala || !is_numeric($id_sala)) {
    $_SESSION['mensaje'] = "Error: ID de sala no válido.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/salas/index.php");
    exit();
}

// Validar campos obligatorios
$errores = [];
if (empty($nombre)) $errores[] = "El campo 'Nombre' es obligatorio.";
if (empty($capacidad) || !is_numeric($capacidad)) $errores[] = "El campo 'Capacidad' debe ser un número válido.";
if (empty($descripcion)) $errores[] = "El campo 'Descripción' es obligatorio.";

// Validar que el nombre de la sala sea único (excluyendo la sala actual)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM salas WHERE nombre = ? AND id_sala != ?");
$stmt->execute([$nombre, $id_sala]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El nombre de la sala ya está en uso.";
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/salas/update.php?id_sala=$id_sala");
    exit();
}

// Construir consulta SQL
$sql = "UPDATE salas SET 
    nombre = :nombre,
    capacidad = :capacidad,
    descripcion = :descripcion
    WHERE id_sala = :id_sala";

$params = [
    ':nombre' => $nombre,
    ':capacidad' => $capacidad,
    ':descripcion' => $descripcion,
    ':id_sala' => $id_sala
];

// Preparar y ejecutar consulta
try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        unset($_SESSION['old_data']);
        $_SESSION['mensaje'] = "Sala actualizada correctamente.";
        $_SESSION['icono'] = 'success';
        header("Location: $URL/App/views/salas/index.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la sala.";
        $_SESSION['icono'] = 'error';
        header("Location: $URL/App/views/salas/update.php?id_sala=$id_sala");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/salas/update.php?id_sala=$id_sala");
}
exit();