<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID del proveedor a editar
$id_proveedor = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id_proveedor || !is_numeric($id_proveedor)) {
    $_SESSION['mensaje'] = "No se especificó un proveedor válido para editar.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/proveedores/index.php');
    exit;
}

// Obtener datos actuales del proveedor
$stmt = $pdo->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
$stmt->execute([$id_proveedor]);
$proveedor = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$proveedor) {
    $_SESSION['mensaje'] = "Proveedor no encontrado.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/proveedores/index.php');
    exit;
}
// Mostrar errores si existen
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    unset($_SESSION['errores']);
}
?>