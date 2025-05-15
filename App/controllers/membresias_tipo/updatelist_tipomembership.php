<?php
include('../../config.php');

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['mensaje'] = "ID inválido";
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM tiposmembresia WHERE id_tipo_membresia = ?");
    $stmt->execute([$id]);
    $tipo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tipo) {
        $_SESSION['mensaje'] = "Tipo de membresía no encontrado";
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    header('Location: index.php');
    exit;
}