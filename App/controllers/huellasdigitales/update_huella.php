<?php
include('../../config.php');

$id_miembro = $_POST['id_miembro'] ?? null;
$plantilla = $_POST['datos_plantilla'] ?? null;

if ($id_miembro && $plantilla) {
    // Verificar si ya existe una huella para este miembro
    $stmt_check = $pdo->prepare("SELECT id_huella FROM huellasdigitales WHERE id_miembro = ?");
    $stmt_check->execute([$id_miembro]);
    $existe = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existe) {
        // Actualizar la huella existente
        $stmt = $pdo->prepare("UPDATE huellasdigitales SET datos_plantilla = ?, ultimo_uso = NOW() WHERE id_miembro = ?");
        if ($stmt->execute([$plantilla, $id_miembro])) {
            echo "Huella actualizada correctamente";
        } else {
            echo "Error al actualizar la huella";
        }
    } else {
        // Insertar nueva huella si no existe
        $stmt = $pdo->prepare("INSERT INTO huellasdigitales (id_miembro, datos_plantilla, fecha_registro, ultimo_uso) VALUES (?, ?, NOW(), NOW())");
        if ($stmt->execute([$id_miembro, $plantilla])) {
            echo "Huella registrada correctamente";
        } else {
            echo "Error al registrar la huella";
        }
    }
} else {
    echo "Datos incompletos";
}
?>