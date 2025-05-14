<?php
include ('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$capacidad = $_POST['capacidad'];
$descripcion = $_POST['descripcion'];



// Validación 1: Verificar que el nombre de la sala sea único
$sentencia_verificar = $pdo->prepare("SELECT COUNT(*) as total FROM salas WHERE nombre = :nombre");
$sentencia_verificar->bindParam(':nombre', $nombre);
$sentencia_verificar->execute();
$resultado = $sentencia_verificar->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $_SESSION['mensaje'] = "El nombre de la sala ya está en uso. Por favor elija otro.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/salas/create.php');
    exit;
}

// Si pasó todas las validaciones, proceder con la creación
$sentencia = $pdo->prepare("INSERT INTO salas 
    (nombre, capacidad, descripcion) 
    VALUES (:nombre, :capacidad, :descripcion)");

$sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$sentencia->bindParam(':capacidad', $capacidad, PDO::PARAM_INT);
$sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);


if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Se registró la sala correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'App/views/salas/index.php');
} else {
    $_SESSION['mensaje'] = "Error al registrar la sala.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/salas/create.php');
}
?>