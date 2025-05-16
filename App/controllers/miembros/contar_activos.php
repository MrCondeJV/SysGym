<?php

// Total de miembros activos
$stmt = $pdo->query("SELECT COUNT(*) FROM miembros WHERE estado = 'activo'");
$miembros_activos = $stmt->fetchColumn();

// Miembros activos registrados en la última semana
$stmt2 = $pdo->query("SELECT COUNT(*) FROM miembros WHERE estado = 'activo' AND fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$miembros_activos_semana = $stmt2->fetchColumn();

// Si quieres obtener la lista de los últimos miembros activos registrados:
$stmt3 = $pdo->query("SELECT id_miembro, nombres, apellidos, fecha_registro FROM miembros WHERE estado = 'activo' ORDER BY fecha_registro DESC LIMIT 5");
$ultimos_miembros_activos = $stmt3->fetchAll(PDO::FETCH_ASSOC);
