<?php

include('../../config.php');

// Consulta para obtener todos los productos
$sql_productos = "SELECT * FROM productos";
$query_productos = $pdo->prepare($sql_productos);
$query_productos->execute();
$productos_datos = $query_productos->fetchAll(PDO::FETCH_ASSOC);
?>