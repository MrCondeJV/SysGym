<?php

include ('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$contacto = trim($_POST['contacto'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo_electronico = trim($_POST['correo_electronico'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$notas = trim($_POST['notas'] ?? '');




// Validar que el correo electrónico sea único
$sentencia_verificar = $pdo->prepare("SELECT COUNT(*) as total FROM proveedores WHERE correo_electronico = :correo_electronico");
$sentencia_verificar->bindParam(':correo_electronico', $correo_electronico, PDO::PARAM_STR);
$sentencia_verificar->execute();
$resultado = $sentencia_verificar->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $errores[] = "El correo electrónico ya está en uso. Por favor elija otro.";
}

// Si hay errores, redirigir con mensajes
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/proveedores/create.php');
    exit;
}

// Insertar proveedor en la base de datos
$sentencia = $pdo->prepare("INSERT INTO proveedores 
    (nombre, contacto, telefono, correo_electronico, direccion, notas) 
    VALUES (:nombre, :contacto, :telefono, :correo_electronico, :direccion, :notas)");

$sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$sentencia->bindParam(':contacto', $contacto, PDO::PARAM_STR);
$sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
$sentencia->bindParam(':correo_electronico', $correo_electronico, PDO::PARAM_STR);
$sentencia->bindParam(':direccion', $direccion, PDO::PARAM_STR);
$sentencia->bindParam(':notas', $notas, PDO::PARAM_STR);


if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Proveedor registrado correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'App/views/proveedores/index.php');
} else {
    $_SESSION['mensaje'] = "Error al registrar el proveedor.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/proveedores/create.php');
}
?>