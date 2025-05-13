<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID del entrenador a editar
$id_entrenador = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id_entrenador || !is_numeric($id_entrenador)) {
    $_SESSION['mensaje'] = "No se especificó un entrenador válido para editar.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/entrenadores/index.php');
    exit;
}

// Obtener datos actuales del entrenador
$stmt = $pdo->prepare("SELECT * FROM entrenadores WHERE id_entrenador = ?");
$stmt->execute([$id_entrenador]);
$entrenador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$entrenador) {
    $_SESSION['mensaje'] = "Entrenador no encontrado.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/entrenadores/index.php');
    exit;
}

// Mostrar errores si existen
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    unset($_SESSION['errores']);
}
?>