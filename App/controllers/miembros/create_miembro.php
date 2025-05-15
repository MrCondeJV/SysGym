<?php

include('../../config.php');
if (session_status() === PHP_SESSION_NONE) session_start();

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
$creado_por = trim($_POST['creado_por'] ?? '');

$errores = [];

// Validaciones
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

if (!empty($errores)) {
    echo json_encode(['errores' => $errores]);
    exit;
}

$sql = "INSERT INTO miembros (
    nombres, apellidos, fecha_nacimiento, genero, correo_electronico, telefono,
    direccion, url_foto, contacto_emergencia_nombre, contacto_emergencia_telefono, creado_por, fecha_registro, estado
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'activo')";

$stmt = $pdo->prepare($sql);
$stmt->execute([
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

echo json_encode(['id_miembro' => $id_miembro]);
?>