<?php
// filepath: c:\xampp\htdocs\SysGym\App\controllers\huellasdigitales\get_miembro.php

include('../../config.php');
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID no recibido']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM miembros WHERE id_miembro = ?");
$stmt->execute([$id]);
$miembro = $stmt->fetch(PDO::FETCH_ASSOC);

if ($miembro) {
    echo json_encode(['success' => true, 'miembro' => $miembro]);
} else {
    echo json_encode(['success' => false, 'message' => 'Miembro no encontrado']);
}
?>