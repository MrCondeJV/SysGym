<?php
include('../../config.php');
session_start();

$term = $_GET['term'] ?? '';

if (strlen($term) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT id_miembro, CONCAT(nombres, ' ', apellidos) AS nombre_completo 
                       FROM miembros 
                       WHERE nombres LIKE ? OR apellidos LIKE ?
                       ORDER BY nombres ASC 
                       LIMIT 15");
$stmt->execute(["%$term%", "%$term%"]);

$resultado = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultado[] = [
        'id' => $row['id_miembro'],
        'label' => $row['nombre_completo'],
        'value' => $row['nombre_completo']
    ];
}

echo json_encode($resultado);