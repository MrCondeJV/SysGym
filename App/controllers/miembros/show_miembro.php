<?php

include('../../config.php');

// Verificar si se recibe un ID válido
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id_miembro = $_GET['id'];

    // Consulta para obtener los datos del miembro con los campos requeridos
    $sql_miembro = "
        SELECT 
            id_miembro,
            nombres,
            apellidos,
            fecha_nacimiento,
            genero,
            correo_electronico,
            telefono,
            direccion,
            fecha_registro,
            estado,
            url_foto,
            contacto_emergencia_nombre,
            contacto_emergencia_telefono,
            creado_por
        FROM miembros
        WHERE id_miembro = :id_miembro
    ";

    $query_miembro = $pdo->prepare($sql_miembro);
    $query_miembro->bindParam(':id_miembro', $id_miembro, PDO::PARAM_INT);
    $query_miembro->execute();
    $miembro_datos = $query_miembro->fetch(PDO::FETCH_ASSOC);

    if ($miembro_datos) {
        // Asignar los datos del miembro a variables
        $id_miembro = $miembro_datos['id_miembro'];
        $nombres = $miembro_datos['nombres'];
        $apellidos = $miembro_datos['apellidos'];
        $fecha_nacimiento = $miembro_datos['fecha_nacimiento'];
        $genero = $miembro_datos['genero'];
        $correo_electronico = $miembro_datos['correo_electronico'];
        $telefono = $miembro_datos['telefono'];
        $direccion = $miembro_datos['direccion'];
        $fecha_registro = $miembro_datos['fecha_registro'];
        $estado = $miembro_datos['estado'];
        $url_foto = $miembro_datos['url_foto'];
        $contacto_emergencia_nombre = $miembro_datos['contacto_emergencia_nombre'];
        $contacto_emergencia_telefono = $miembro_datos['contacto_emergencia_telefono'];
        $creado_por = $miembro_datos['creado_por'];
    } else {
        echo "<script>alert('Miembro no encontrado'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de miembro no válido'); window.location.href='index.php';</script>";
    exit();
}
?>