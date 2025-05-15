<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID del miembro a editar
$id_miembro = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id_miembro || !is_numeric($id_miembro)) {
    $_SESSION['mensaje'] = "No se especificó un miembro válido para editar.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/miembros/index.php');
    exit;
}

// Obtener datos actuales del miembro
$stmt = $pdo->prepare("SELECT * FROM miembros WHERE id_miembro = ?");
$stmt->execute([$id_miembro]);
$miembro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$miembro) {
    $_SESSION['mensaje'] = "Miembro no encontrado.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/miembros/index.php');
    exit;
}

// Mostrar errores si existen
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    unset($_SESSION['errores']);
}
?>