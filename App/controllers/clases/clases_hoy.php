<?php

if (!isset($clases_hoy)) {
    // 1 = lunes, 7 = domingo
    $dia_actual = date('N');
    // Total clases hoy
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clases WHERE dia_semana = ?");
    $stmt->execute([$dia_actual]);
    $clases_hoy = $stmt->fetchColumn();

    // Clases en la mañana (antes de las 12)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clases WHERE dia_semana = ? AND horario < '12:00:00'");
    $stmt->execute([$dia_actual]);
    $clases_manana = $stmt->fetchColumn();

    // Clases en la tarde (12:00 o después)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clases WHERE dia_semana = ? AND horario >= '12:00:00'");
    $stmt->execute([$dia_actual]);
    $clases_tarde = $stmt->fetchColumn();
}