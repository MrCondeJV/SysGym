<?php
include('../../config.php');
header('Content-Type: application/json');
session_start();

$numero_documento = $_POST['numero_documento'] ?? $_GET['numero_documento'] ?? null;

if (!$numero_documento) {
    echo json_encode(['success' => false, 'message' => 'Número de documento requerido']);
    exit;
}

// Trae los datos del miembro y su membresía más reciente (vigente o no)
$stmt = $pdo->prepare("
    SELECT m.*, 
           mb.id_membresia,
           mb.id_tipo_membresia,
           mb.fecha_inicio, 
           mb.fecha_fin, 
           mb.creado_en,
           GREATEST(DATEDIFF(mb.fecha_fin, CURDATE()), 0) AS dias_vigentes,
           DATEDIFF(mb.fecha_fin, mb.fecha_inicio) AS dias_total
    FROM miembros m
    LEFT JOIN membresias mb 
        ON mb.id_miembro = m.id_miembro
    WHERE m.numero_documento = ?
    ORDER BY mb.fecha_fin DESC
    LIMIT 1
");
$stmt->execute([$numero_documento]);
$miembro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$miembro) {
    echo json_encode(['success' => false, 'message' => 'Miembro no encontrado']);
    exit;
}

$advertencias = [];

// Verificar estado del miembro
if (isset($miembro['estado']) && strtolower($miembro['estado']) !== 'activo') {
    $advertencias[] = 'El miembro no está activo';
}

// Verificar membresía y días vigentes
if (empty($miembro['id_membresia'])) {
    $advertencias[] = 'No tiene membresía registrada';
} elseif ($miembro['dias_vigentes'] <= 0) {
    $advertencias[] = 'La membresía está vencida';
}

// Agregar advertencias al resultado
$miembro['advertencias'] = $advertencias;

echo json_encode(['success' => true, 'miembro' => $miembro]);
?>