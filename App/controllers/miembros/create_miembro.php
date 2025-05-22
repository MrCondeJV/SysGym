<?php

include('../../config.php');
if (session_status() === PHP_SESSION_NONE) session_start();
$creado_por = $_SESSION['id_usuario'] ?? null; // Ajusta según tu sistema de login

$tipo_documento = trim($_POST['tipo_documento'] ?? '');
$numero_documento = trim($_POST['numero_documento'] ?? '');

$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$genero = trim($_POST['genero'] ?? '');
$correo_electronico = trim($_POST['correo_electronico'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$url_foto = trim($_POST['url_foto'] ?? '');
$contacto_emergencia_nombre = trim($_POST['contacto_emergencia_nombre'] ?? '');
$contacto_emergencia_telefono = trim($_POST['contacto_emergencia_telefono'] ?? '');
#$creado_por = trim($_POST['creado_por'] ?? '');

$errores = [];

// Validaciones
if (empty($tipo_documento)) $errores[] = "El tipo de documento es obligatorio.";
if (empty($numero_documento)) $errores[] = "El número de documento es obligatorio.";

if (empty($nombres)) $errores[] = "El nombre es obligatorio.";
if (empty($apellidos)) $errores[] = "El apellido es obligatorio.";
if (empty($fecha_nacimiento)) $errores[] = "La fecha de nacimiento es obligatoria.";
if (empty($genero)) $errores[] = "El género es obligatorio.";
if (empty($correo_electronico) || !filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) $errores[] = "El correo electrónico es obligatorio y debe ser válido.";
if (empty($telefono)) $errores[] = "El teléfono es obligatorio.";
if (empty($direccion)) $errores[] = "La dirección es obligatoria.";
if (empty($contacto_emergencia_nombre)) $errores[] = "El nombre del contacto de emergencia es obligatorio.";
if (empty($contacto_emergencia_telefono)) $errores[] = "El teléfono del contacto de emergencia es obligatorio.";
if (empty($creado_por)) $errores[] = "El campo 'creado por' es obligatorio.";

// Puedes agregar más validaciones según tus necesidades

// Validar que el número de documento sea único
$stmt = $pdo->prepare("SELECT COUNT(*) FROM miembros WHERE tipo_documento = ? AND numero_documento = ?");
$stmt->execute([$tipo_documento, $numero_documento]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El número de documento ya está registrado para otro miembro.";
}

if (!empty($errores)) {
    echo json_encode(['errores' => $errores]);
    exit;
}
$sql = "INSERT INTO miembros (
    tipo_documento, numero_documento, nombres, apellidos, fecha_nacimiento, genero, correo_electronico, telefono,
    direccion, url_foto, contacto_emergencia_nombre, contacto_emergencia_telefono, creado_por, fecha_registro, estado
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'activo')";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $tipo_documento,
    $numero_documento,
    $nombres,
    $apellidos,
    $fecha_nacimiento,
    $genero,
    $correo_electronico,
    $telefono,
    $direccion,
    $url_foto,
    $contacto_emergencia_nombre,
    $contacto_emergencia_telefono,
    $creado_por
]);

$id_miembro = $pdo->lastInsertId();

#echo json_encode(['id_miembro' => $id_miembro]);

$_SESSION['mensaje'] = '¡Miembro registrado exitosamente!';
$_SESSION['icono'] = 'success';
header('Location: ../../views/miembros/index.php'); // Ajusta la ruta según tu estructura
exit;