<?php

include('../../config.php');

// Consulta para obtener todos los productos
$sql_categorias_clases = "SELECT * FROM categorias_clases";
$query_categorias_clases = $pdo->prepare($sql_categorias_clases);
$query_categorias_clases->execute();
$categorias_clases_datos = $query_categorias_clases->fetchAll(PDO::FETCH_ASSOC);
?>