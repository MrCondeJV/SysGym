<?php

include('../../config.php');

// Verificar si se recibe un ID válido
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id_proveedor_get = $_GET['id'];

    // Consulta para obtener los datos del proveedor con los campos requeridos
    $sql_proveedor = "
        SELECT 
            id_proveedor,
            nombre,
            contacto,
            telefono,
            correo_electronico,
            direccion,
            notas
        FROM proveedores
        WHERE id_proveedor = :id_proveedor
    ";

    $query_proveedor = $pdo->prepare($sql_proveedor);
    $query_proveedor->bindParam(':id_proveedor', $id_proveedor_get, PDO::PARAM_INT);
    $query_proveedor->execute();
    $proveedor_datos = $query_proveedor->fetch(PDO::FETCH_ASSOC);

    if ($proveedor_datos) {
        // Asignar los datos del proveedor a variables
        $id_proveedor = $proveedor_datos['id_proveedor'];
        $nombre = $proveedor_datos['nombre'];
        $contacto = $proveedor_datos['contacto'];
        $telefono = $proveedor_datos['telefono'];
        $correo_electronico = $proveedor_datos['correo_electronico'];
        $direccion = $proveedor_datos['direccion'];
        $notas = $proveedor_datos['notas'];
    } else {
        echo "<script>alert('Proveedor no encontrado'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de proveedor no válido'); window.location.href='index.php';</script>";
    exit();
}
?>