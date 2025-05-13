<?php

include ('../../config.php');
session_start();

// Guardar datos del formulario para repoblación en caso de error
$_SESSION['old_data'] = $_POST;

// Obtener datos del formulario
$id_entrenador = $_POST['id_entrenador'] ?? null;
$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$especialidad = trim($_POST['especialidad'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo_electronico = trim($_POST['correo_electronico'] ?? '');
$fecha_contratacion = trim($_POST['fecha_contratacion'] ?? '');
$estado = trim($_POST['estado'] ?? '');
$certificacion = trim($_POST['certificacion'] ?? '');
$id_usuario = trim($_POST['id_usuario'] ?? '');

// Validar ID del entrenador
if (!$id_entrenador || !is_numeric($id_entrenador)) {
    $_SESSION['mensaje'] = "Error: ID de entrenador no válido.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/entrenadores/index.php");
    exit();
}

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

// Validar que el correo electrónico sea único (excluyendo el entrenador actual)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM entrenadores WHERE correo_electronico = ? AND id_entrenador != ?");
$stmt->execute([$correo_electronico, $id_entrenador]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El correo electrónico ya está en uso.";
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/entrenadores/update.php?id_entrenador=$id_entrenador");
    exit();
}

// Construir consulta SQL
$sql = "UPDATE entrenadores SET 
    nombres = :nombres,
    apellidos = :apellidos,
    especialidad = :especialidad,
    telefono = :telefono,
    correo_electronico = :correo_electronico,
    fecha_contratacion = :fecha_contratacion,
    estado = :estado,
    certificacion = :certificacion,
    id_usuario = :id_usuario
    WHERE id_entrenador = :id_entrenador";

$params = [
    ':nombres' => $nombres,
    ':apellidos' => $apellidos,
    ':especialidad' => $especialidad,
    ':telefono' => $telefono,
    ':correo_electronico' => $correo_electronico,
    ':fecha_contratacion' => $fecha_contratacion,
    ':estado' => $estado,
    ':certificacion' => $certificacion,
    ':id_usuario' => $id_usuario,
    ':id_entrenador' => $id_entrenador
];

// Preparar y ejecutar consulta
try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        unset($_SESSION['old_data']);
        $_SESSION['mensaje'] = "Entrenador actualizado correctamente.";
        $_SESSION['icono'] = 'success';
        header("Location: $URL/App/views/entrenadores/index.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el entrenador.";
        $_SESSION['icono'] = 'error';
        header("Location: $URL/App/views/entrenadores/update.php?id_entrenador=$id_entrenador");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/entrenadores/update.php?id_entrenador=$id_entrenador");
}
exit();