<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID de la sala a editar
$id_sala = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id_sala || !is_numeric($id_sala)) {
    $_SESSION['mensaje'] = "No se especificó una sala válida para editar.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/salas/index.php');
    exit;
}

// Obtener datos actuales de la sala
$stmt = $pdo->prepare("SELECT * FROM salas WHERE id_sala = ?");
$stmt->execute([$id_sala]);
$sala = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sala) {
    $_SESSION['mensaje'] = "Sala no encontrada.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/salas/index.php');
    exit;
}

// Mostrar errores si existen
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    unset($_SESSION['errores']);
}
?>