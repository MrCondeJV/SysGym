<?php
include('../../config.php');

$sql_usuarios = "SELECT * FROM usuariossistema";
$query_usuarios = $pdo->prepare($sql_usuarios);
$query_usuarios->execute();
$usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);
?>
