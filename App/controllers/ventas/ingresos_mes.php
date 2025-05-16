<?php
if (!isset($ingresos_mes)) {
    $mes = date('Y-m');
    $stmt = $pdo->prepare("SELECT SUM(total) FROM ventas WHERE DATE_FORMAT(fecha_venta, '%Y-%m') = ?");
    $stmt->execute([$mes]);
    $ingresos_mes = $stmt->fetchColumn() ?: 0;
}