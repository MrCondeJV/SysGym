<?php
include ('../../config.php');
session_start();

// Guardar datos del formulario para repoblación en caso de error
$_SESSION['old_data'] = $_POST;

// Obtener datos del formulario
$id_usuario = $_POST['id_usuario'] ?? null;
$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
$rol = $_POST['rol'] ?? 0;
$telefono = trim($_POST['telefono'] ?? '');
$correo_electronico = trim($_POST['correo_electronico'] ?? '');
$estado = $_POST['estado'] ?? 'Activo';
$contrasena = $_POST['contrasena_hash'] ?? '';
$contrasena_repetida = $_POST['contrasena_hash_repeat'] ?? '';

// Validar ID del usuario
if (!$id_usuario || !is_numeric($id_usuario)) {
    $_SESSION['mensaje'] = "Error: ID de usuario no válido.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/usuarios/index.php");
    exit();
}

// Validar campos obligatorios
$errores = [];
if (empty($nombres)) $errores[] = "El campo 'Nombres' es obligatorio.";
if (empty($apellidos)) $errores[] = "El campo 'Apellidos' es obligatorio.";
if (empty($nombre_usuario)) $errores[] = "El campo 'Nombre de Usuario' es obligatorio.";
if (empty($correo_electronico)) $errores[] = "El campo 'Correo Electrónico' es obligatorio.";

// Validar que las contraseñas coincidan (si se proporcionan)
if (!empty($contrasena) && $contrasena !== $contrasena_repetida) {
    $errores[] = "Las contraseñas no coinciden.";
}

// Validar que el nombre de usuario sea único (excluyendo el usuario actual)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuariossistema WHERE nombre_usuario = ? AND id_usuario != ?");
$stmt->execute([$nombre_usuario, $id_usuario]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El nombre de usuario ya está en uso.";
}

// Validar que el correo electrónico sea único (excluyendo el usuario actual)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuariossistema WHERE correo_electronico = ? AND id_usuario != ?");
$stmt->execute([$correo_electronico, $id_usuario]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El correo electrónico ya está en uso.";
}

// Validar formato de teléfono
if (!preg_match('/^[0-9]{7,15}$/', $telefono)) {
    $errores[] = "El teléfono debe contener solo números (7-15 dígitos).";
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/usuarios/update.php?id_usuario=$id_usuario");
    exit();
}

// Construir consulta SQL
$sql = "UPDATE usuariossistema SET 
    nombres = :nombres,
    apellidos = :apellidos,
    nombre_usuario = :nombre_usuario,
    rol = :rol,
    telefono = :telefono,
    correo_electronico = :correo_electronico,
    estado = :estado";

$params = [
    ':nombres' => $nombres,
    ':apellidos' => $apellidos,
    ':nombre_usuario' => $nombre_usuario,
    ':rol' => $rol,
    ':telefono' => $telefono,
    ':correo_electronico' => $correo_electronico,
    ':estado' => $estado,
    ':id_usuario' => $id_usuario
];

// Solo actualizar contraseña si se proporciona
if (!empty($contrasena)) {
    $sql .= ", contrasena_hash = :contrasena";
    $params[':contrasena'] = password_hash($contrasena, PASSWORD_DEFAULT);
}

$sql .= " WHERE id_usuario = :id_usuario";

// Preparar y ejecutar consulta
try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        unset($_SESSION['old_data']);
        $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
        $_SESSION['icono'] = 'success';
        header("Location: $URL/App/views/usuarios/index.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el usuario.";
        $_SESSION['icono'] = 'error';
        header("Location: $URL/App/views/usuarios/update.php?id_usuario=$id_usuario");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/usuarios/update.php?id_usuario=$id_usuario");
}
exit();