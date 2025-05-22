<?php

include('../../config.php');

$sql = "SELECT id_metodo, nombre, descripcion, activo FROM metodos_pago ORDER BY nombre ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Puedes usar $metodos en tu vista para mostrar la lista de métodos de pago