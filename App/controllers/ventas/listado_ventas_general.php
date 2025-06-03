<?php
include('../../config.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario_actual = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario_actual) {
    die("No hay usuario autenticado.");
}

// Obtener el nombre del rol del usuario
$stmtRol = $pdo->prepare("
    SELECT r.nombre AS nombre_rol 
    FROM usuariossistema u
    JOIN roles r ON u.rol = r.id
    WHERE u.id_usuario = :id_usuario
");
$stmtRol->execute([':id_usuario' => $id_usuario_actual]);
$usuario = $stmtRol->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

$rol_usuario = strtolower($usuario['nombre_rol']);

// Obtener fechas desde GET
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

$where = [];
$params = [];

// Filtro por fechas
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

// Filtro por usuario si NO es administrador
if ($rol_usuario !== 'administrador') {
    $where[] = "v.id_usuario = ?";
    $params[] = $id_usuario_actual;
}

$where_sql = !empty($where) ? "WHERE " . implode(' AND ', $where) : '';

// Consulta principal para listar ventas con datos del usuario
$sql = "SELECT v.id_venta, v.fecha_venta, v.total, v.numero_factura, 
               CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario
        FROM ventas v
        LEFT JOIN usuariossistema u ON v.id_usuario = u.id_usuario
        $where_sql
        ORDER BY v.fecha_venta DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener el total de ventas según filtro
$sql_total = "SELECT SUM(v.total) AS total_ventas FROM ventas v $where_sql";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params);
$row_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
$total_ventas = $row_total['total_ventas'] ?? 0;

// Ahora tienes:
// $ventas = listado filtrado de ventas con datos del usuario
// $total_ventas = suma total filtrada