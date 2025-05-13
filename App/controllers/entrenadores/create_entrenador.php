<?php


include ('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener datos del formulario
$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$especialidad = trim($_POST['especialidad'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo_electronico = trim($_POST['correo_electronico'] ?? '');
$fecha_contratacion = trim($_POST['fecha_contratacion'] ?? '');
$estado = trim($_POST['estado'] ?? '');
$certificacion = trim($_POST['certificacion'] ?? '');
$id_usuario = trim($_POST['id_usuario'] ?? '');

// Validar campos obligatorios
$errores = [];
if (empty($nombres)) $errores[] = "El campo 'Nombres' es obligatorio.";
if (empty($apellidos)) $errores[] = "El campo 'Apellidos' es obligatorio.";
if (empty($especialidad)) $errores[] = "El campo 'Especialidad' es obligatorio.";
if (empty($telefono)) $errores[] = "El campo 'Teléfono' es obligatorio.";
if (empty($correo_electronico)) $errores[] = "El campo 'Correo Electrónico' es obligatorio.";
if (empty($fecha_contratacion)) $errores[] = "El campo 'Fecha de Contratación' es obligatorio.";
if (empty($estado)) $errores[] = "El campo 'Estado' es obligatorio.";

// Validar formato de correo electrónico
if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El formato del correo electrónico no es válido.";
}

// Validar formato de teléfono
if (!preg_match('/^[0-9]{7,15}$/', $telefono)) {
    $errores[] = "El teléfono debe contener solo números (7-15 dígitos).";
}

// Validar que el correo electrónico sea único
$sentencia_verificar = $pdo->prepare("SELECT COUNT(*) as total FROM entrenadores WHERE correo_electronico = :correo_electronico");
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
    header('Location: '.$URL.'App/views/entrenadores/create.php');
    exit;
}

// Insertar entrenador en la base de datos
$sentencia = $pdo->prepare("INSERT INTO entrenadores 
    (nombres, apellidos, especialidad, telefono, correo_electronico, fecha_contratacion, estado, certificacion, id_usuario) 
    VALUES (:nombres, :apellidos, :especialidad, :telefono, :correo_electronico, :fecha_contratacion, :estado, :certificacion, :id_usuario)");

$sentencia->bindParam(':nombres', $nombres, PDO::PARAM_STR);
$sentencia->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
$sentencia->bindParam(':especialidad', $especialidad, PDO::PARAM_STR);
$sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
$sentencia->bindParam(':correo_electronico', $correo_electronico, PDO::PARAM_STR);
$sentencia->bindParam(':fecha_contratacion', $fecha_contratacion, PDO::PARAM_STR);
$sentencia->bindParam(':estado', $estado, PDO::PARAM_STR);
$sentencia->bindParam(':certificacion', $certificacion, PDO::PARAM_STR);
$sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Entrenador registrado correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'App/views/entrenadores/index.php');
} else {
    $_SESSION['mensaje'] = "Error al registrar el entrenador.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/entrenadores/create.php');
}
?>