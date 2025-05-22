<?php
include('../../config.php');
header('Content-Type: application/json');

$id_miembro = $_GET['id_miembro'] ?? null;
if (!$id_miembro) {
    echo json_encode(['success' => false, 'message' => 'ID miembro requerido']);
    exit;
}

$stmt = $pdo->prepare("SELECT fecha_entrada, fecha_salida, DATE(fecha_entrada) as fecha FROM historialaccesos WHERE id_miembro = ? ORDER BY fecha_entrada DESC LIMIT 5");
$stmt->execute([$id_miembro]);
$accesos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'accesos' => $accesos]);
?>