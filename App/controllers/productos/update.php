<?php

include ('../../config.php');
session_start();

// Guardar datos del formulario para repoblación en caso de error
$_SESSION['old_data'] = $_POST;

// Obtener datos del formulario
$id_producto = $_POST['id_producto'] ?? null;
$codigo_producto = trim($_POST['codigo_producto'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = trim($_POST['precio'] ?? '');
$cantidad_stock = trim($_POST['cantidad_stock'] ?? '');
$id_categoria = trim($_POST['id_categoria'] ?? '');
$codigo_barras = trim($_POST['codigo_barras'] ?? '');
$precio_compra = trim($_POST['precio_compra'] ?? '');
$stock_minimo = trim($_POST['stock_minimo'] ?? '');
$activo = trim($_POST['activo'] ?? '');
$id_proveedor = trim($_POST['id_proveedor'] ?? '');

// Calcular el margen de ganancia
$margen_ganancia = $precio - $precio_compra;

// Validar ID del producto
if (!$id_producto || !is_numeric($id_producto)) {
    $_SESSION['mensaje'] = "Error: ID de producto no válido.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/productos/index.php");
    exit();
}

// Validar campos obligatorios
$errores = [];
if (empty($codigo_producto)) $errores[] = "El campo 'Código del Producto' es obligatorio.";
if (empty($nombre)) $errores[] = "El campo 'Nombre' es obligatorio.";
if (empty($descripcion)) $errores[] = "El campo 'Descripción' es obligatorio.";
if (empty($precio) || !is_numeric($precio)) $errores[] = "El campo 'Precio' es obligatorio y debe ser numérico.";
if (empty($cantidad_stock) || !is_numeric($cantidad_stock)) $errores[] = "El campo 'Cantidad en Stock' es obligatorio y debe ser numérico.";
if (empty($id_categoria) || !is_numeric($id_categoria)) $errores[] = "El campo 'ID Categoría' es obligatorio y debe ser numérico.";
if (empty($codigo_barras)) $errores[] = "El campo 'Código de Barras' es obligatorio.";
if (empty($precio_compra) || !is_numeric($precio_compra)) $errores[] = "El campo 'Precio de Compra' es obligatorio y debe ser numérico.";
if ($margen_ganancia < 0) $errores[] = "El margen de ganancia no puede ser negativo. Verifique el precio y el precio de compra.";
if (empty($stock_minimo) || !is_numeric($stock_minimo)) $errores[] = "El campo 'Stock Mínimo' es obligatorio y debe ser numérico.";
if (empty($activo)) $errores[] = "El campo 'Activo' es obligatorio.";
if (empty($id_proveedor) || !is_numeric($id_proveedor)) $errores[] = "El campo 'ID Proveedor' es obligatorio y debe ser numérico.";

// Validar que el código de barras sea único (excluyendo el producto actual)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE codigo_barras = ? AND id_producto != ?");
$stmt->execute([$codigo_barras, $id_producto]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El código de barras ya está en uso.";
}

// Manejar la carga de la imagen
if (isset($_FILES['url_imagen']) && $_FILES['url_imagen']['error'] === UPLOAD_ERR_OK) {
    $imagen = $_FILES['url_imagen'];
    $nombre_imagen = uniqid('producto_') . '.' . pathinfo($imagen['name'], PATHINFO_EXTENSION);
    $ruta_destino = '../../img/productos/' . $nombre_imagen;

    // Mover la imagen a la carpeta de destino
    if (!move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
        $errores[] = "Error al subir la imagen. Intente nuevamente.";
    } else {
        // Obtener la imagen actual del producto
        $stmt = $pdo->prepare("SELECT url_imagen FROM productos WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $producto_actual = $stmt->fetch(PDO::FETCH_ASSOC);

        // Eliminar la imagen anterior si existe
        if (!empty($producto_actual['url_imagen']) && file_exists('../../' . $producto_actual['url_imagen'])) {
            unlink('../../' . $producto_actual['url_imagen']);
        }

        // Guardar la nueva URL relativa de la imagen
        $url_imagen = 'img/productos/' . $nombre_imagen;
    }
} else {
    // Mantener la imagen actual si no se sube una nueva
    $stmt = $pdo->prepare("SELECT url_imagen FROM productos WHERE id_producto = ?");
    $stmt->execute([$id_producto]);
    $producto_actual = $stmt->fetch(PDO::FETCH_ASSOC);
    $url_imagen = $producto_actual['url_imagen'];
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/productos/update.php?id_producto=$id_producto");
    exit();
}

// Construir consulta SQL
$sql = "UPDATE productos SET 
    nombre = :nombre,
    codigo_producto = :codigo_producto,
    descripcion = :descripcion,
    precio = :precio,
    cantidad_stock = :cantidad_stock,
    id_categoria = :id_categoria,
    codigo_barras = :codigo_barras,
    url_imagen = :url_imagen,
    precio_compra = :precio_compra,
    margen_ganancia = :margen_ganancia,
    stock_minimo = :stock_minimo,
    activo = :activo,
    id_proveedor = :id_proveedor
    WHERE id_producto = :id_producto";

$params = [
    ':codigo_producto' => $codigo_producto,
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':precio' => $precio,
    ':cantidad_stock' => $cantidad_stock,
    ':id_categoria' => $id_categoria,
    ':codigo_barras' => $codigo_barras,
    ':url_imagen' => $url_imagen,
    ':precio_compra' => $precio_compra,
    ':margen_ganancia' => $margen_ganancia,
    ':stock_minimo' => $stock_minimo,
    ':activo' => $activo,
    ':id_proveedor' => $id_proveedor,
    ':id_producto' => $id_producto
];

// Preparar y ejecutar consulta
try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        unset($_SESSION['old_data']);
        $_SESSION['mensaje'] = "Producto actualizado correctamente.";
        $_SESSION['icono'] = 'success';
        header("Location: $URL/App/views/productos/index.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el producto.";
        $_SESSION['icono'] = 'error';
        header("Location: $URL/App/views/productos/update.php?id_producto=$id_producto");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/productos/update.php?id_producto=$id_producto");
}
exit();