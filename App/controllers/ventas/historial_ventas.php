<?php
// filepath: c:\xampp\htdocs\SysGym\App\controllers\ventas\historial_ventas.php

include('../../config.php');

// Consulta para obtener el historial de ventas con información básica
$sql = "SELECT v.id_venta, v.fecha_venta, v.total, u.nombre AS nombre_usuario
        FROM ventas v
        LEFT JOIN usuariossistema u ON v.id_usuario = u.id_usuario
        ORDER BY v.fecha_venta DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Puedes usar $ventas en tu vista para mostrar el historial
?>