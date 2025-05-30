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

// Definir condiciones y parámetros para filtro
$where = '';
$params = [];

if ($rol_usuario !== 'admin') {
    $where = "WHERE id_usuario = ?";
    $params[] = $id_usuario_actual;
}

// Consulta para obtener ventas
$sql = "SELECT * FROM ventas $where";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener total de ventas
$sql_total = "SELECT SUM(total) AS total_ventas FROM ventas $where";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params);
$row_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
$total_ventas = $row_total['total_ventas'] ?? 0;

// $ventas y $total_ventas están listos para usarse