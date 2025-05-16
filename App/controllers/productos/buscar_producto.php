<?php
// filepath: c:\xampp\htdocs\SysGym\App\controllers\productos\buscar_producto.php

include('../../config.php');

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

// Buscar por nombre o código de producto (puedes agregar más campos si lo deseas)
$sql = "SELECT id_producto, nombre, precio, cantidad_stock 
        FROM productos 
        WHERE (nombre LIKE :q OR codigo_producto LIKE :q) 
        AND activo = 1 
        LIMIT 10";

$stmt = $pdo->prepare($sql);
$like = "%$q%";
$stmt->bindParam(':q', $like, PDO::PARAM_STR);
$stmt->execute();

$resultados = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultados[] = [
        'id_producto' => $row['id_producto'],
        'nombre' => $row['nombre'],
        'precio' => $row['precio'],
        'cantidad_stock' => $row['cantidad_stock']
    ];
}

echo json_encode($resultados);
?>