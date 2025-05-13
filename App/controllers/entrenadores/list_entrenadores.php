<?php

include('../../config.php');

// Consulta para obtener todos los entrenadores
$sql_entrenadores = "SELECT * FROM entrenadores";
$query_entrenadores = $pdo->prepare($sql_entrenadores);
$query_entrenadores->execute();
$entrenadores_datos = $query_entrenadores->fetchAll(PDO::FETCH_ASSOC);
?>