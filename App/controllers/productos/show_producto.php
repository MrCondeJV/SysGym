<?php


include('../../config.php');

// Verificar si se recibe un ID válido
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id_producto_get = $_GET['id'];

    // Consulta para obtener los datos del producto con los nombres de la categoría y el proveedor
    $sql_producto = "
        SELECT 
            p.id_producto,
            p.codigo_producto,
            p.nombre,
            p.descripcion,
            p.precio,
            p.cantidad_stock,
            c.nombre AS categoria_nombre,
            p.codigo_barras,
            p.url_imagen,
            p.precio_compra,
            p.margen_ganancia,
            p.stock_minimo,
            p.activo,
            pr.nombre AS proveedor_nombre
        FROM productos p
        LEFT JOIN categoriasproductos c ON p.id_categoria = c.id_categoria
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
        WHERE p.id_producto = :id_producto
    ";

    $query_producto = $pdo->prepare($sql_producto);
    $query_producto->bindParam(':id_producto', $id_producto_get, PDO::PARAM_INT);
    $query_producto->execute();
    $producto_datos = $query_producto->fetch(PDO::FETCH_ASSOC);

    if ($producto_datos) {
        // Asignar los datos del producto a variables
        $id_producto = $producto_datos['id_producto'];
        $codigo_producto = $producto_datos['codigo_producto'];
        $nombre = $producto_datos['nombre'];
        $descripcion = $producto_datos['descripcion'];
        $precio = $producto_datos['precio'];
        $cantidad_stock = $producto_datos['cantidad_stock'];
        $categoria_nombre = $producto_datos['categoria_nombre'];
        $codigo_barras = $producto_datos['codigo_barras'];
        $url_imagen = !empty($producto_datos['url_imagen']) ? $producto_datos['url_imagen'] : 'img/default.png'; // Ruta por defecto si no hay imagen
        $precio_compra = $producto_datos['precio_compra'];
        $margen_ganancia = $producto_datos['margen_ganancia'];
        $stock_minimo = $producto_datos['stock_minimo'];
        $activo = $producto_datos['activo'];
        $proveedor_nombre = $producto_datos['proveedor_nombre'];
    } else {
        echo "<script>alert('Producto no encontrado'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de producto no válido'); window.location.href='index.php';</script>";
    exit();
}
?>