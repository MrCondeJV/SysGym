<?php
include('../../config.php');
session_start(); // Asegúrate de iniciar la sesión
header('Content-Type: application/json');

$id_miembro = $_POST['id_miembro'] ?? null;
$metodo = $_POST['metodo_acceso'] ?? 'huella';
// Toma el id_usuario de la sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_miembro) {
    echo json_encode(['success' => false, 'message' => 'ID miembro requerido']);
    exit;
}

// ¿Hay un acceso abierto?
$stmt = $pdo->prepare("SELECT * FROM historialaccesos WHERE id_miembro = ? AND fecha_salida IS NULL ORDER BY fecha_entrada DESC LIMIT 1");
$stmt->execute([$id_miembro]);
$acceso = $stmt->fetch(PDO::FETCH_ASSOC);

if ($acceso) {
    // Hay acceso abierto, hacer UPDATE (salida)
    $stmt = $pdo->prepare("UPDATE historialaccesos SET fecha_salida = NOW() WHERE id_acceso = ?");
    $stmt->execute([$acceso['id_acceso']]);
    echo json_encode(['success' => true, 'tipo' => 'salida', 'message' => 'Salida registrada']);
} else {
    // No hay acceso abierto, hacer INSERT (entrada)
    $stmt = $pdo->prepare("INSERT INTO historialaccesos (id_miembro, fecha_entrada, metodo_acceso, id_usuario) VALUES (?, NOW(), ?, ?)");
    $stmt->execute([$id_miembro, $metodo, $id_usuario]);
    echo json_encode(['success' => true, 'tipo' => 'entrada', 'message' => 'Entrada registrada']);
}
?>