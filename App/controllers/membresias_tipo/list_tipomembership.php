<?php
include('../../config.php');

// Debe incluirse config.php antes de este archivo para tener $pdo configurado

try {
    $stmt = $pdo->prepare("SELECT * FROM tiposmembresia ORDER BY id_tipo_membresia DESC");
    $stmt->execute();
    $tiposmembresia_datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Opcional: manejar error o enviar a log
    $tiposmembresia_datos = [];
    $_SESSION['mensaje'] = "Error al cargar los tipos de membresía: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
}