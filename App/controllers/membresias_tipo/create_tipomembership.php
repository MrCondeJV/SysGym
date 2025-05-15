<?php
include('../../config.php');
session_start();

// Guardar datos del formulario para repoblación en caso de error
$_SESSION['old_data'] = $_POST;

// Captura y sanitización de datos
$nombre = trim($_POST['nombre'] ?? '');
$duracion_dias = intval($_POST['duracion_dias'] ?? 0);
$precio = floatval($_POST['precio'] ?? 0);
$descripcion = trim($_POST['descripcion'] ?? '');
$beneficios = trim($_POST['beneficios'] ?? '');

// Validaciones
$errores = [];

// Campos obligatorios
$campos_obligatorios = [
    'Nombre' => $nombre,
    'Duración (días)' => $duracion_dias,
    'Precio' => $precio
];

foreach ($campos_obligatorios as $campo => $valor) {
    if (empty($valor) && $valor !== 0) {
        $errores[] = "El campo {$campo} es obligatorio";
    }
}

// Validaciones específicas
if ($duracion_dias <= 0) {
    $errores[] = "La duración debe ser un número positivo";
}
if ($precio < 0) {
    $errores[] = "El precio no puede ser negativo";
}
if (strlen($nombre) > 50) {
    $errores[] = "El nombre no puede tener más de 50 caracteres";
}

// Validar unicidad de nombre (opcional, si se desea que no haya nombres repetidos)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tiposmembresia WHERE nombre = ?");
$stmt->execute([$nombre]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "Ya existe un tipo de membresía con ese nombre";
}

// Si hay errores, redirigir con mensaje de error
if (!empty($errores)) {
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error en el formulario';
    header('Location: ../../views/membresias_tipo/create.php');
    exit;
}

// Preparar consulta INSERT
$sentencia = $pdo->prepare("INSERT INTO tiposmembresia 
    (nombre, duracion_dias, precio, descripcion, beneficios) 
    VALUES (:nombre, :duracion_dias, :precio, :descripcion, :beneficios)");

$sentencia->bindParam(':nombre', $nombre);
$sentencia->bindParam(':duracion_dias', $duracion_dias, PDO::PARAM_INT);
$sentencia->bindParam(':precio', $precio);
$sentencia->bindParam(':descripcion', $descripcion);
$sentencia->bindParam(':beneficios', $beneficios);

try {
    if ($sentencia->execute()) {
        unset($_SESSION['old_data']); // Limpiar datos antiguos
        $_SESSION['mensaje'] = "Tipo de membresía registrado correctamente";
        $_SESSION['icono'] = 'success';
        $_SESSION['titulo'] = 'Éxito';
        header('Location: ../../views/membresias_tipo/');
    } else {
        $_SESSION['mensaje'] = "Error al registrar el tipo de membresía";
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        header('Location: ../../views/membresias_tipo/create.php');
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    header('Location: ../../views/membresias_tipo/create.php');
}
exit;