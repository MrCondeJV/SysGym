<?php
include ('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

// Validación: Verificar que el nombre de la categoría sea único
$sentencia_verificar = $pdo->prepare("SELECT COUNT(*) as total FROM categorias_clases WHERE nombre = :nombre");
$sentencia_verificar->bindParam(':nombre', $nombre);
$sentencia_verificar->execute();
$resultado = $sentencia_verificar->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $_SESSION['mensaje'] = "El nombre de la categoría ya está en uso. Por favor elija otro.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/clases/create.php');
    exit;
}

// Insertar nueva categoría
$sentencia = $pdo->prepare("INSERT INTO categorias_clases 
    (nombre, descripcion) 
    VALUES (:nombre, :descripcion)");

$sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Categoría registrada correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'App/views/clases/index_categorias.php');
} else {
    $_SESSION['mensaje'] = "Error al registrar la categoría.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/clases/create_categoria.php');
}
?>
