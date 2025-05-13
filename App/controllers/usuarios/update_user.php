<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID del usuario a editar
$id_usuario = $_GET['id'] ?? $_POST['id'] ?? null;



if (!$id_usuario || !is_numeric($id_usuario)) {
    $_SESSION['mensaje'] = "No se especificó un usuario válido para editar." ;
    $_SESSION['icono'] = 'error';
   
    header('Location: ' . $URL . 'App/views/usuarios/index.php');
    exit;
}

// Obtener datos actuales del usuario
$stmt = $pdo->prepare("SELECT * FROM usuariossistema WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['mensaje'] = "Usuario no encontrado.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/usuarios/index.php');
    exit;
}

// Mostrar errores si existen
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    unset($_SESSION['errores']);
}
?>