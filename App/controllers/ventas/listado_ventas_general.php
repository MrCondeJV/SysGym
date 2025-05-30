<?php
include('../../config.php');

// Obtener todas las ventas
$sql = "SELECT * FROM ventas";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular la sumatoria total de las ventas
$sql_total = "SELECT SUM(total) AS total_ventas FROM ventas";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute();
$row_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
$total_ventas = $row_total['total_ventas'] ?? 0;

// Ahora tienes:
// $ventas -> array con todas las ventas
// $total_ventas -> sumatoria total de todas las ventas
?>