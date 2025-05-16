<?php

include('../../config.php');

$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

$where = '';
$params = [];

if ($fecha_inicio && $fecha_fin) {
    $where = "WHERE DATE(v.fecha_venta) BETWEEN ? AND ?";
    $params[] = $fecha_inicio;
    $params[] = $fecha_fin;
} elseif ($fecha_inicio) {
    $where = "WHERE DATE(v.fecha_venta) >= ?";
    $params[] = $fecha_inicio;
} elseif ($fecha_fin) {
    $where = "WHERE DATE(v.fecha_venta) <= ?";
    $params[] = $fecha_fin;
}

$sql = "SELECT v.id_venta, v.fecha_venta, v.total, v.numero_factura, CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario
        FROM ventas v
        LEFT JOIN usuariossistema u ON v.id_usuario = u.id_usuario
        $where
        ORDER BY v.fecha_venta DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Puedes usar $ventas en tu vista para mostrar el historial
?>