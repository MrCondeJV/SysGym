<?php
include('../../config.php');

// Consulta para obtener solo los miembros activos
$sql_miembros = "SELECT * FROM miembros WHERE estado = 'inactivo'";
$query_miembros = $pdo->prepare($sql_miembros);
$query_miembros->execute();
$miembros_datos = $query_miembros->fetchAll(PDO::FETCH_ASSOC);
?>