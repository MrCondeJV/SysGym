<?php
// filepath: c:\xampp\htdocs\SysGym\App\controllers\huellasdigitales\get_miembro.php

include('../../config.php');
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID no recibido']);
    exit;
}

// Trae los datos del miembro y su membresía más reciente
$stmt = $pdo->prepare("
    SELECT m.*, 
           mb.id_membresia,
           mb.id_tipo_membresia,
           mb.fecha_inicio, 
           mb.fecha_fin, 
           mb.estado_pago,
           mb.precio,
           mb.id_metodo,
           mb.creado_en,
           mb.pausada_desde,
           mb.pausada_hasta,
           mb.id_promocion,
           mb.dias_pausa_total,
           mb.creado_por AS creado_por_membresia,
           GREATEST(DATEDIFF(mb.fecha_fin, CURDATE()), 0) AS dias_vigentes,
           DATEDIFF(mb.fecha_fin, mb.fecha_inicio) AS dias_total
    FROM miembros m
    LEFT JOIN membresias mb ON mb.id_miembro = m.id_miembro
    WHERE m.id_miembro = ?
    ORDER BY mb.fecha_fin DESC
    LIMIT 1
");
$stmt->execute([$id]);
$miembro = $stmt->fetch(PDO::FETCH_ASSOC);

if ($miembro) {
    echo json_encode(['success' => true, 'miembro' => $miembro]);
} else {
    echo json_encode(['success' => false, 'message' => 'Miembro no encontrado']);
}
?>