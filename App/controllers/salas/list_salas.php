<?php
include('../../config.php');

// Consultar todas las salas
$sql_salas = "SELECT * FROM salas";
$query_salas = $pdo->prepare($sql_salas);
$query_salas->execute();
$salas_datos = $query_salas->fetchAll(PDO::FETCH_ASSOC);
?>