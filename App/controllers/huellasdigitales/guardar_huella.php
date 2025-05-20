<?php

include('../../config.php');

header('Content-Type: application/json');

$id_miembro = $_POST['id_miembro'] ?? null;
$plantilla = $_POST['huella'] ?? null;
$modo = $_POST['modo'] ?? 'registrar';

// Log de la huella recibida
file_put_contents(__DIR__ . '/log_huella.txt', date('Y-m-d H:i:s') . " | id_miembro: $id_miembro | huella: $plantilla\n", FILE_APPEND);

if ($id_miembro && $plantilla) {
    // Verificar si ya existe una huella para ese miembro
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM huellasdigitales WHERE id_miembro = ?");
    $stmt_check->execute([$id_miembro]);
    $existe = $stmt_check->fetchColumn();

    if ($existe) {
        if ($modo === "modificar") {
            // Actualizar la plantilla existente
            $stmt_update = $pdo->prepare("UPDATE huellasdigitales SET datos_plantilla = ?, fecha_registro = NOW(), ultimo_uso = NOW() WHERE id_miembro = ?");
            if ($stmt_update->execute([$plantilla, $id_miembro])) {
                echo json_encode(['success' => true, 'message' => 'Huella actualizada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la huella']);
            }
        } else {
            // Si ya existe y el modo es "registrar", devolver mensaje
            echo json_encode(['success' => false, 'message' => 'Ya existe una huella registrada para este usuario']);
        }
    } else {
        // Insertar nueva huella
        $stmt_insert = $pdo->prepare("INSERT INTO huellasdigitales (id_miembro, datos_plantilla, fecha_registro, ultimo_uso) VALUES (?, ?, NOW(), NOW())");
        if ($stmt_insert->execute([$id_miembro, $plantilla])) {
            echo json_encode(['success' => true, 'message' => 'Huella registrada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la huella']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>