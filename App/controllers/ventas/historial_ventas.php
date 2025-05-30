<?php
include('../../config.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario_actual = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario_actual) {
    die("No hay usuario autenticado.");
}

// Obtener rol del usuario
$stmtRol = $pdo->prepare("SELECT rol FROM usuariossistema WHERE id_usuario = :id_usuario");
$stmtRol->execute([':id_usuario' => $id_usuario_actual]);
$usuario = $stmtRol->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

$rol_usuario = $usuario['rol'];

// Obtener fechas desde GET
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

$where = [];
$params = [];

// Filtrado por fecha (igual que antes)
if ($fecha_inicio && $fecha_fin) {
    $where[] = "DATE(v.fecha_venta) BETWEEN ? AND ?";
    $params[] = $fecha_inicio;
    $params[] = $fecha_fin;
} elseif ($fecha_inicio) {
    $where[] = "DATE(v.fecha_venta) >= ?";
    $params[] = $fecha_inicio;
} elseif ($fecha_fin) {
    $where[] = "DATE(v.fecha_venta) <= ?";
    $params[] = $fecha_fin;
}

// Filtrar solo si NO es admin
if ($rol_usuario !== 'admin') {
    $where[] = "v.id_usuario = ?";
    $params[] = $id_usuario_actual;
}

$where_sql = '';
if (!empty($where)) {
    $where_sql = "WHERE " . implode(' AND ', $where);
}

$sql = "SELECT v.id_venta, v.fecha_venta, v.total, v.numero_factura, CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario
        FROM ventas v
        LEFT JOIN usuariossistema u ON v.id_usuario = u.id_usuario
        $where_sql
        ORDER BY v.fecha_venta DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $ventas listo para usarse en la vista