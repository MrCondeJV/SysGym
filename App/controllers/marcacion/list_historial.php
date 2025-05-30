<?php

// Consulta para obtener todos los accesos con datos del miembro y usuario
$busqueda = $_GET['buscar'] ?? '';
$params = [];
$sql = "SELECT h.id_acceso, h.fecha_entrada, h.fecha_salida, h.metodo_acceso, 
               m.id_miembro, m.nombres, m.apellidos, m.numero_documento, m.url_foto,
               u.nombres AS usuario
        FROM historialaccesos h
        INNER JOIN miembros m ON h.id_miembro = m.id_miembro
        LEFT JOIN usuariossistema u ON h.id_usuario = u.id_usuario";

$sql .= " ORDER BY h.fecha_entrada DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);