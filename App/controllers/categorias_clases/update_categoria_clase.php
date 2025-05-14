<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID de la categoría a editar
$id_categoria = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id_categoria || !is_numeric($id_categoria)) {
    $_SESSION['mensaje'] = "No se especificó una categoría válida para editar.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/clases/index_categorias.php');
    exit;
}

// Obtener datos actuales de la categoría
$stmt = $pdo->prepare("SELECT * FROM categorias_clases WHERE id_categoria = ?");
$stmt->execute([$id_categoria]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    $_SESSION['mensaje'] = "Categoría no encontrada.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/clases/index_categorias.php');
    exit;
}

// Mostrar errores si existen
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    unset($_SESSION['errores']);
}
?>
