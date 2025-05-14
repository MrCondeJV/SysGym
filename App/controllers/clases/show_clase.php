<?php

include('../../config.php');

// Verificar si se recibe un ID válido
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id_clase_get = $_GET['id'];

    // Consulta para obtener los datos de la clase
    $sql_clase = "
        SELECT
            id_clase,
            nombre,
            descripcion,
            id_entrenador,
            horario,
            duracion_minutos,
            capacidad_maxima,
            id_sala,
            dia_semana,
            precio,
            nivel,
            requisitos,
            id_categoria_clase,
            cancelada
        FROM clases
        WHERE id_clase = :id_clase
    ";

    $query_clase = $pdo->prepare($sql_clase);
    $query_clase->bindParam(':id_clase', $id_clase_get, PDO::PARAM_INT);
    $query_clase->execute();
    $clase_datos = $query_clase->fetch(PDO::FETCH_ASSOC);

    if ($clase_datos) {
        // Asignar los datos de la clase a variables
        $id_clase = $clase_datos['id_clase'];
        $nombre = $clase_datos['nombre'];
        $descripcion = $clase_datos['descripcion'];
        $id_entrenador = $clase_datos['id_entrenador'];
        $horario = $clase_datos['horario'];
        $duracion_minutos = $clase_datos['duracion_minutos'];
        $capacidad_maxima = $clase_datos['capacidad_maxima'];
        $id_sala = $clase_datos['id_sala'];
        $dia_semana = $clase_datos['dia_semana'];
        $precio = $clase_datos['precio'];
        $nivel = $clase_datos['nivel'];
        $requisitos = $clase_datos['requisitos'];
        $id_categoria_clase = $clase_datos['id_categoria_clase'];
        $cancelada = $clase_datos['cancelada'];

        // Nota: Si necesitas mostrar los nombres del entrenador, sala o categoría,
        // deberías añadir LEFT JOINs a sus respectivas tablas aquí.
        // Por ahora, solo se obtienen los IDs.

    } else {
        echo "<script>alert('Clase no encontrada'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de clase no válido'); window.location.href='index.php';</script>";
    exit();
}
?>
