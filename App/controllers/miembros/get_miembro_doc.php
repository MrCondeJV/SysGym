<?php
include('../../config.php');
header('Content-Type: application/json');
session_start();

$numero_documento = $_POST['numero_documento'] ?? $_GET['numero_documento'] ?? null;

if (!$numero_documento) {
    echo json_encode(['success' => false, 'message' => 'Número de documento requerido']);
    exit;
}

// Trae los datos del miembro y su membresía activa más reciente
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
        AND mb.fecha_fin >= CURDATE()
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

// Validar estado del miembro
if (isset($miembro['estado']) && strtolower($miembro['estado']) !== 'activo') {
    echo json_encode(['success' => false, 'message' => 'El miembro no está activo']);
    exit;
}

// Validar membresía activa y días vigentes
if (empty($miembro['id_membresia']) || $miembro['dias_vigentes'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'La membresía está vencida o no existe. Debe renovar.']);
    exit;
}

echo json_encode(['success' => true, 'miembro' => $miembro]);
?>