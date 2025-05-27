<?php


if (!isset($clases_hoy)) {
    $dias = [
        1 => 'lunes',
        2 => 'martes',
        3 => 'miércoles',
        4 => 'jueves',
        5 => 'viernes',
        6 => 'sábado',
        7 => 'domingo'
    ];
    $dia_actual = $dias[date('N')];

    // Total clases hoy
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clases WHERE dia_semana = ? AND cancelada = 0");
    $stmt->execute([$dia_actual]);
    $clases_hoy = $stmt->fetchColumn();

    // Clases en la mañana (antes de las 12)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM clases 
        WHERE dia_semana = ? AND cancelada = 0 
        AND (
            (LENGTH(horario) = 8 AND horario < '12:00:00')
            OR
            (LENGTH(horario) > 8 AND SUBSTRING_INDEX(horario, ' ', 1) < '12:00:00')
        )
    ");
    $stmt->execute([$dia_actual]);
    $clases_manana = $stmt->fetchColumn();

    // Clases en la tarde (12:00 o después)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM clases 
        WHERE dia_semana = ? AND cancelada = 0 
        AND (
            (LENGTH(horario) = 8 AND horario >= '12:00:00')
            OR
            (LENGTH(horario) > 8 AND SUBSTRING_INDEX(horario, ' ', 1) >= '12:00:00')
        )
    ");
    $stmt->execute([$dia_actual]);
    $clases_tarde = $stmt->fetchColumn();
}