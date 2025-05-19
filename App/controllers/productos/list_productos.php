<?php

include('../../config.php');

// Consulta para obtener todos los productos con el nombre de la categoría
$sql_productos = "SELECT p.*, c.nombre AS nombre_categoria
                  FROM productos p
                  LEFT JOIN categoriasproductos c ON p.id_categoria = c.id_categoria";
$query_productos = $pdo->prepare($sql_productos);
$query_productos->execute();
$productos_datos = $query_productos->fetchAll(PDO::FETCH_ASSOC);
?>