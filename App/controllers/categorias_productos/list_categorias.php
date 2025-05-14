<?php

include('../../config.php');

// Consulta para obtener todos los productos
$sql_categorias = "SELECT * FROM categoriasproductos";
$query_categorias = $pdo->prepare($sql_categorias);
$query_categorias->execute();
$categorias_datos = $query_categorias->fetchAll(PDO::FETCH_ASSOC);
?>