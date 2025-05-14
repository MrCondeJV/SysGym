<?php
include('../../config.php');

// Obtener el ID de la categoría de clase desde el método GET
$id_categoria_get = $_GET['id'] ?? '';

// Validar que el ID no esté vacío y sea numérico
if (!empty($id_categoria_get) && is_numeric($id_categoria_get)) {
    // Consulta para obtener los datos de la categoría de clase con los campos requeridos
    $sql_categoria = "
        SELECT
            id_categoria,
            nombre,
            descripcion
        FROM categorias_clases
        WHERE id_categoria = :id_categoria
    ";

    $query_categoria = $pdo->prepare($sql_categoria);
    $query_categoria->bindParam(':id_categoria', $id_categoria_get, PDO::PARAM_INT);
    $query_categoria->execute();
    $categoria_datos = $query_categoria->fetch(PDO::FETCH_ASSOC);

    if ($categoria_datos) {
        // Asignar los datos de la categoría a variables
        $id_categoria = $categoria_datos['id_categoria'];
        $nombre = $categoria_datos['nombre'];
        $descripcion = $categoria_datos['descripcion'];
        // Nota: El campo 'capacidad' del script original no existe en categorias_clases, se elimina.

    } else {
        // Si no se encuentra la categoría
        echo "<script>alert('Categoría de clase no encontrada'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    // Si el ID no es válido o está vacío
    echo "<script>alert('ID de categoría de clase no válido'); window.location.href='index.php';</script>";
    exit();
}
?>
