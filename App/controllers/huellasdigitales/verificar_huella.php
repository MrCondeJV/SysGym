<?php
// filepath: c:\xampp\htdocs\SysGym\App\controllers\huellasdigitales\verificar_huella.php

include('../../config.php');
header('Content-Type: application/json');

// Log para depuración (opcional, puedes quitarlo en producción)
file_put_contents(__DIR__ . '/debug_post.log', print_r($_POST, true));

$plantilla = $_POST['huella'] ?? null;

if (!$plantilla) {
    echo json_encode(['success' => false, 'message' => 'No se recibió la plantilla de la huella']);
    exit;
}

// Buscar coincidencia en la base de datos
$stmt = $pdo->prepare("SELECT h.id_miembro, m.nombres, m.apellidos, m.url_foto
                       FROM huellasdigitales h
                       INNER JOIN miembros m ON h.id_miembro = m.id_miembro
                       WHERE h.datos_plantilla = ?");
$stmt->execute([$plantilla]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    echo json_encode([
        'success' => true,
        'coincide' => true,
        'usuario' => [
            'id_miembro' => $usuario['id_miembro'],
            'nombres' => $usuario['nombres'],
            'apellidos' => $usuario['apellidos'],
            'url_foto' => $usuario['url_foto']
        ]
    ]);
} else {
    echo json_encode([
        'success' => true,
        'coincide' => false,
        'message' => 'No se encontró coincidencia para la huella'
    ]);
}
?>