<?php
include('../../config.php');
session_start();

// Captura y sanitización
$id = intval($_POST['id_tipo_membresia'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$duracion_dias = intval($_POST['duracion_dias'] ?? 0);
$precio = floatval($_POST['precio'] ?? 0);
$descripcion = trim($_POST['descripcion'] ?? '');
$beneficios = trim($_POST['beneficios'] ?? '');

// Validaciones
$errores = [];

if ($id <= 0) {
    $errores[] = "ID inválido";
}
if (empty($nombre)) {
    $errores[] = "El nombre es obligatorio";
} elseif (strlen($nombre) > 50) {
    $errores[] = "El nombre no puede exceder 50 caracteres";
}
if ($duracion_dias <= 0) {
    $errores[] = "La duración debe ser un número positivo";
}
if ($precio < 0) {
    $errores[] = "El precio no puede ser negativo";
}

// Validar unicidad del nombre excluyendo el actual registro
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tiposmembresia WHERE nombre = ? AND id_tipo_membresia != ?");
$stmt->execute([$nombre, $id]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "Ya existe un tipo de membresía con ese nombre";
}

if (!empty($errores)) {
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error en el formulario';
    header("Location: ../../views/membresias_tipo/update.php?id=$id");
    exit;
}

// Preparar UPDATE
try {
    $stmt = $pdo->prepare("UPDATE tiposmembresia SET 
        nombre = :nombre, 
        duracion_dias = :duracion_dias, 
        precio = :precio, 
        descripcion = :descripcion, 
        beneficios = :beneficios 
        WHERE id_tipo_membresia = :id");

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':duracion_dias', $duracion_dias, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':beneficios', $beneficios);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Tipo de membresía actualizado correctamente";
        $_SESSION['icono'] = 'success';
        $_SESSION['titulo'] = 'Éxito';
        header('Location: ../../views/membresias_tipo/');
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el tipo de membresía";
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        header("Location: ../../views/membresias_tipo/update.php?id=$id");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    header("Location: ../../views/membresias_tipo/update.php?id=$id");
}
exit;