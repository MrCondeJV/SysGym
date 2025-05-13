<?php
include ('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$nombre_usuario = $_POST['nombre_usuario'];
$contrasena_hash = $_POST['contrasena_hash'];
$contrasena_hash_repeat = $_POST['contrasena_hash_repeat'];
$rol = $_POST['rol'];
$telefono = $_POST['telefono'];
$correo_electronico = $_POST['correo_electronico'];
$ultimo_acceso = date('Y-m-d H:i:s');
$estado = $_POST['estado'];

// Asignar la fecha y hora actual al campo creado_en
$creado_en = date('Y-m-d H:i:s');



// Validación 1: Contraseñas coinciden
if ($contrasena_hash !== $contrasena_hash_repeat) {
    $_SESSION['mensaje'] = "Las contraseñas no coinciden. Por favor, inténtelo de nuevo.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/usuarios/create.php');
    exit;
}

// Validación 2: Nombre de usuario único
$sentencia_verificar = $pdo->prepare("SELECT COUNT(*) as total FROM usuariossistema WHERE nombre_usuario = :nombre_usuario");
$sentencia_verificar->bindParam(':nombre_usuario', $nombre_usuario);
$sentencia_verificar->execute();
$resultado = $sentencia_verificar->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $_SESSION['mensaje'] = "El nombre de usuario ya está en uso. Por favor elija otro.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/usuarios/create.php');
    exit;
}

// Validación 3: Correo electrónico único
$sentencia_verificar = $pdo->prepare("SELECT COUNT(*) as total FROM usuariossistema WHERE correo_electronico = :correo_electronico");
$sentencia_verificar->bindParam(':correo_electronico', $correo_electronico);
$sentencia_verificar->execute();
$resultado = $sentencia_verificar->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $_SESSION['mensaje'] = "El correo electronico ya está en uso. Por favor elija otro.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/usuarios/create.php');
    exit;
}

// Si pasó todas las validaciones, proceder con la creación
$contrasena_hash = password_hash($contrasena_hash, PASSWORD_DEFAULT);

$sentencia = $pdo->prepare("INSERT INTO usuariossistema 
    (nombres, apellidos, nombre_usuario, contrasena_hash, rol, telefono, correo_electronico, ultimo_acceso, estado, creado_en) 
    VALUES (:nombres, :apellidos, :nombre_usuario, :contrasena_hash, :rol, :telefono, :correo_electronico, :ultimo_acceso, :estado, :creado_en)");

$sentencia->bindParam(':nombres', $nombres, PDO::PARAM_STR);
$sentencia->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
$sentencia->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
$sentencia->bindParam(':contrasena_hash', $contrasena_hash, PDO::PARAM_STR);
$sentencia->bindParam(':rol', $rol, PDO::PARAM_INT);
$sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
$sentencia->bindParam(':correo_electronico', $correo_electronico, PDO::PARAM_STR);
$sentencia->bindParam(':ultimo_acceso', $ultimo_acceso, PDO::PARAM_STR);
$sentencia->bindParam(':estado', $estado, PDO::PARAM_STR);
$sentencia->bindParam(':creado_en', $creado_en, PDO::PARAM_STR);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Se registró al usuario correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'App/views/usuarios/index.php');
} else {
    $_SESSION['mensaje'] = "Error al registrar el usuario.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/usuarios/create.php');
}
?>