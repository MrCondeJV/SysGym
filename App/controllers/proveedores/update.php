<?php
include ('../../config.php');
session_start();

// Guardar datos del formulario para repoblación en caso de error
$_SESSION['old_data'] = $_POST;

// Obtener datos del formulario
$id_proveedor = $_POST['id_proveedor'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$contacto = trim($_POST['contacto'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo_electronico = trim($_POST['correo_electronico'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$notas = trim($_POST['notas'] ?? '');

// Validar ID del proveedor
if (!$id_proveedor || !is_numeric($id_proveedor)) {
    $_SESSION['mensaje'] = "Error: ID de proveedor no válido.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/proveedores/index.php");
    exit();
}

// Validar campos obligatorios
$errores = [];
if (empty($nombre)) $errores[] = "El campo 'Nombre del Proveedor' es obligatorio.";
if (empty($contacto)) $errores[] = "El campo 'Nombre del Contacto' es obligatorio.";
if (empty($telefono)) $errores[] = "El campo 'Teléfono' es obligatorio.";
if (empty($correo_electronico)) $errores[] = "El campo 'Correo Electrónico' es obligatorio.";
if (empty($direccion)) $errores[] = "El campo 'Dirección' es obligatorio.";

// Validar formato de correo electrónico
if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El formato del correo electrónico no es válido.";
}

// Validar formato de teléfono
if (!preg_match('/^[0-9]{7,15}$/', $telefono)) {
    $errores[] = "El teléfono debe contener solo números (7-15 dígitos).";
}

// Validar que el correo electrónico sea único (excluyendo el proveedor actual)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM proveedores WHERE correo_electronico = ? AND id_proveedor != ?");
$stmt->execute([$correo_electronico, $id_proveedor]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El correo electrónico ya está en uso.";
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/proveedores/update.php?id_proveedor=$id_proveedor");
    exit();
}

// Construir consulta SQL
$sql = "UPDATE proveedores SET 
    nombre = :nombre,
    contacto = :contacto,
    telefono = :telefono,
    correo_electronico = :correo_electronico,
    direccion = :direccion,
    notas = :notas
    WHERE id_proveedor = :id_proveedor";

$params = [
    ':nombre' => $nombre,
    ':contacto' => $contacto,
    ':telefono' => $telefono,
    ':correo_electronico' => $correo_electronico,
    ':direccion' => $direccion,
    ':notas' => $notas,
    ':id_proveedor' => $id_proveedor
];

// Preparar y ejecutar consulta
try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        unset($_SESSION['old_data']);
        $_SESSION['mensaje'] = "Proveedor actualizado correctamente.";
        $_SESSION['icono'] = 'success';
        header("Location: $URL/App/views/proveedores/index.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el proveedor.";
        $_SESSION['icono'] = 'error';
        header("Location: $URL/App/views/proveedores/update.php?id_proveedor=$id_proveedor");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/proveedores/update.php?id_proveedor=$id_proveedor");
}
exit();