<?php

include('../../config.php');

$sql = "SELECT id_metodo, nombre FROM metodos_pago WHERE activo = 1 ORDER BY nombre ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($metodos);