<?php


include('../../config.php');

// Verificar si se recibe un ID válido
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id_entrenador = $_GET['id'];

    // Consulta para obtener los datos del entrenador con los campos requeridos
    $sql_entrenador = "
        SELECT 
            id_entrenador,
            nombres,
            apellidos,
            especialidad,
            telefono,
            correo_electronico,
            fecha_contratacion,
            estado,
            certificacion,
            id_usuario
        FROM entrenadores
        WHERE id_entrenador = :id_entrenador
    ";

    $query_entrenador = $pdo->prepare($sql_entrenador);
    $query_entrenador->bindParam(':id_entrenador', $id_entrenador, PDO::PARAM_INT);
    $query_entrenador->execute();
    $entrenador_datos = $query_entrenador->fetch(PDO::FETCH_ASSOC);

    if ($entrenador_datos) {
        // Asignar los datos del entrenador a variables
        $id_entrenador = $entrenador_datos['id_entrenador'];
        $nombres = $entrenador_datos['nombres'];
        $apellidos = $entrenador_datos['apellidos'];
        $especialidad = $entrenador_datos['especialidad'];
        $telefono = $entrenador_datos['telefono'];
        $correo_electronico = $entrenador_datos['correo_electronico'];
        $fecha_contratacion = $entrenador_datos['fecha_contratacion'];
        $estado = $entrenador_datos['estado'];
        $certificacion = $entrenador_datos['certificacion'];
        $id_usuario = $entrenador_datos['id_usuario'];
    } else {
        echo "<script>alert('Entrenador no encontrado'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de entrenador no válido'); window.location.href='index.php';</script>";
    exit();
}
?>