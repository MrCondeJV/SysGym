<?php

include('../../config.php');

// Consulta para obtener todas las clases
$sql_clases = "SELECT * FROM clases";
$query_clases = $pdo->prepare($sql_clases);
$query_clases->execute();
$clases_datos = $query_clases->fetchAll(PDO::FETCH_ASSOC);
?>
