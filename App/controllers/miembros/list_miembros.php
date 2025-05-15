<?php
include('../../config.php');

// Consulta para obtener todos los miembros
$sql_miembros = "SELECT * FROM miembros";
$query_miembros = $pdo->prepare($sql_miembros);
$query_miembros->execute();
$miembros_datos = $query_miembros->fetchAll(PDO::FETCH_ASSOC);
?>