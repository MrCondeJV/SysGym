<?php
include ('../../config.php');
session_start();

// Guardar datos del formulario para repoblación en caso de error
$_SESSION['old_data'] = $_POST;

// Obtener datos del formulario
$id_categoria = $_POST['id_categoria'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

// Validar ID
if (!$id_categoria || !is_numeric($id_categoria)) {
    $_SESSION['mensaje'] = "Error: ID de categoría no válido.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/clases/index_categorias.php");
    exit();
}

// Validar campos obligatorios
$errores = [];
if (empty($nombre)) $errores[] = "El campo 'Nombre' es obligatorio.";
if (empty($descripcion)) $errores[] = "El campo 'Descripción' es obligatorio.";

// Validar que el nombre sea único, excluyendo el actual
$stmt = $pdo->prepare("SELECT COUNT(*) FROM categorias_clases WHERE nombre = ? AND id_categoria != ?");
$stmt->execute([$nombre, $id_categoria]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El nombre ya está en uso por otra categoría.";
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/clases/update_categoria.php?id_categoria=$id_categoria");
    exit();
}

// Consulta SQL para actualizar
$sql = "UPDATE categorias_clases SET 
    nombre = :nombre,
    descripcion = :descripcion
    WHERE id_categoria = :id_categoria";

$params = [
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':id_categoria' => $id_categoria
];

try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        unset($_SESSION['old_data']);
        $_SESSION['mensaje'] = "Categoría actualizada correctamente.";
        $_SESSION['icono'] = 'success';
        header("Location: $URL/App/views/clases/index_categorias.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la categoría.";
        $_SESSION['icono'] = 'error';
        header("Location: $URL/App/views/clases/update_categoria.php?id_categoria=$id_categoria");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/clases/update_categoria.php?id_categoria=$id_categoria");
}
exit();
