<?php
include('../../config.php');
session_start();

$term = $_GET['term'] ?? '';

if (strlen($term) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT id_tipo_membresia, nombre 
                       FROM tiposmembresia 
                       WHERE nombre LIKE ? 
                       ORDER BY nombre ASC 
                       LIMIT 15");
$stmt->execute(["%$term%"]);

$resultado = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultado[] = [
        'id' => $row['id_tipo_membresia'],
        'label' => $row['nombre'],
        'value' => $row['nombre']
    ];
}

echo json_encode($resultado);