<?php

if (!isset($nuevos_miembros)) {
    $mes = date('Y-m');
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM miembros WHERE DATE_FORMAT(fecha_registro, '%Y-%m') = ?");
    $stmt->execute([$mes]);
    $nuevos_miembros = $stmt->fetchColumn();

    // Porcentaje respecto al mes anterior
    $mes_anterior = date('Y-m', strtotime('-1 month'));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM miembros WHERE DATE_FORMAT(fecha_registro, '%Y-%m') = ?");
    $stmt->execute([$mes_anterior]);
    $nuevos_mes_anterior = $stmt->fetchColumn();
    $porcentaje_nuevos = $nuevos_mes_anterior > 0 ? round(($nuevos_miembros - $nuevos_mes_anterior) / $nuevos_mes_anterior * 100) : 100;
}