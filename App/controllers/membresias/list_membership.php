<?php
try {
    $stmt = $pdo->prepare("
       SELECT 
    m.id_membresia, 
    CONCAT(mi.nombres, ' ', mi.apellidos) AS nombre_miembro, 
    tm.nombre AS nombre_membresia,
    m.fecha_inicio, 
    m.fecha_fin,
    DATEDIFF(m.fecha_fin, CURDATE()) AS dias_restantes
FROM membresias m
INNER JOIN miembros mi ON m.id_miembro = mi.id_miembro
INNER JOIN tiposmembresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
ORDER BY m.id_membresia DESC;

    ");
    $stmt->execute();
    $membresias_datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $membresias_datos = [];
    $_SESSION['mensaje'] = "Error al cargar las membresías: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
}