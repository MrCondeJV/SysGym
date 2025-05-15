<?php
include('../../config.php');
$id_miembro = $_POST['id_miembro'] ?? null;
$plantilla = $_POST['datos_plantilla'] ?? null;

if ($id_miembro && $plantilla) {
    $stmt = $pdo->prepare("INSERT INTO huellasdigitales (id_miembro, datos_plantilla, fecha_registro, ultimo_uso) VALUES (?, ?, NOW(), NOW())");
    if ($stmt->execute([$id_miembro, $plantilla])) {
        echo "Huella registrada correctamente";
    } else {
        echo "Error al registrar la huella";
    }
} else {
    echo "Datos incompletos";
}
?>