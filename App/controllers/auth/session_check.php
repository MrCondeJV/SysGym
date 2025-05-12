<?php
session_start();

$session_lifetime = 30; // Tiempo de sesión en segundos

if (!isset($_SESSION['LAST_ACTIVITY'])) {
    echo json_encode(["status" => "expired"]);
    exit();
}

// Si han pasado más de 30 segundos, cerrar sesión
if (time() - $_SESSION['LAST_ACTIVITY'] > $session_lifetime) {
    session_unset();
    session_destroy();
    echo json_encode(["status" => "expired"]);
    exit();
}

// Actualizar el tiempo de actividad
$_SESSION['LAST_ACTIVITY'] = time();
echo json_encode(["status" => "active"]);
exit();
?>