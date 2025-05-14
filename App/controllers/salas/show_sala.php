<?php
include('../../config.php');

// Obtener el ID de la sala desde el método GET
$id_sala = $_GET['id'] ?? '';

if (!empty($id_sala)) {
    // Consulta para obtener los datos de la sala con los campos requeridos
    $sql_sala = "
        SELECT 
            id_sala,
            nombre,
            capacidad,
            descripcion
        FROM salas
        WHERE id_sala = :id_sala
    ";

    $query_sala = $pdo->prepare($sql_sala);
    $query_sala->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $query_sala->execute();
    $sala_datos = $query_sala->fetch(PDO::FETCH_ASSOC);

    if ($sala_datos) {
        $id_sala = $sala_datos['id_sala'];
        $nombre = $sala_datos['nombre'];
        $capacidad = $sala_datos['capacidad'];
        $descripcion = $sala_datos['descripcion'];
    } else {
        echo "<script>alert('Sala no encontrada'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de sala no válido'); window.location.href='index.php';</script>";
    exit();
}
?>