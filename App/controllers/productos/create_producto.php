<?php

include ('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = trim($_POST['precio'] ?? '');
$cantidad_stock = trim($_POST['cantidad_stock'] ?? '');
$id_categoria = trim($_POST['id_categoria'] ?? '');
$codigo_barras = trim($_POST['codigo_barras'] ?? '');
$codigo_producto = trim($_POST['codigo_producto'] ?? '');
$precio_compra = trim($_POST['precio_compra'] ?? '');
$stock_minimo = trim($_POST['stock_minimo'] ?? '');
$activo = trim($_POST['activo'] ?? '');
$id_proveedor = trim($_POST['id_proveedor'] ?? '');

// Calcular el margen de ganancia
$margen_ganancia = $precio - $precio_compra;

// Validar campos obligatorios
$errores = [];
if (empty($nombre)) $errores[] = "El campo 'Nombre' es obligatorio.";
if (empty($descripcion)) $errores[] = "El campo 'Descripción' es obligatorio.";
if (empty($precio) || !is_numeric($precio)) $errores[] = "El campo 'Precio' es obligatorio y debe ser numérico.";
if (empty($cantidad_stock) || !is_numeric($cantidad_stock)) $errores[] = "El campo 'Cantidad en Stock' es obligatorio y debe ser numérico.";
if (empty($id_categoria) || !is_numeric($id_categoria)) $errores[] = "El campo 'ID Categoría' es obligatorio y debe ser numérico.";
if (empty($codigo_barras)) $errores[] = "El campo 'Código de Barras' es obligatorio.";
if (empty($codigo_producto)) $errores[] = "El campo 'Código del Producto' es obligatorio.";
if (empty($precio_compra) || !is_numeric($precio_compra)) $errores[] = "El campo 'Precio de Compra' es obligatorio y debe ser numérico.";
if ($margen_ganancia < 0) $errores[] = "El margen de ganancia no puede ser negativo.";
if (empty($stock_minimo) || !is_numeric($stock_minimo)) $errores[] = "El campo 'Stock Mínimo' es obligatorio y debe ser numérico.";
if (empty($activo)) $errores[] = "El campo 'Activo' es obligatorio.";
if (empty($id_proveedor) || !is_numeric($id_proveedor)) $errores[] = "El campo 'ID Proveedor' es obligatorio y debe ser numérico.";

// Manejar la carga de la imagen
if (isset($_FILES['url_imagen']) && $_FILES['url_imagen']['error'] === UPLOAD_ERR_OK) {
    $imagen = $_FILES['url_imagen'];
    $tipo_mime = mime_content_type($imagen['tmp_name']);
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($tipo_mime, $tipos_permitidos)) {
        $errores[] = "El archivo debe ser una imagen válida (JPEG, PNG, GIF).";
    } else {
        $nombre_imagen = uniqid('producto_') . '.' . pathinfo($imagen['name'], PATHINFO_EXTENSION);
        $ruta_destino = '../../img/productos/' . $nombre_imagen;

        // Crear la carpeta si no existe
        if (!is_dir('../../img/productos')) {
            mkdir('../../img/productos', 0777, true);
        }

        if (!move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
            $errores[] = "Error al mover la imagen al destino.";
        } else {
            $url_imagen = 'img/productos/' . $nombre_imagen;
        }
    }
} else {
    $errores[] = "Debe cargar una imagen válida.";
}

// Si hay errores, redirigir con mensajes
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/productos/create.php');
    exit;
}

// Insertar producto en la base de datos
$sentencia = $pdo->prepare("INSERT INTO productos 
    (nombre, descripcion, precio, cantidad_stock, id_categoria, codigo_barras, codigo_producto, url_imagen, precio_compra, margen_ganancia, stock_minimo, activo, id_proveedor) 
    VALUES (:nombre, :descripcion, :precio, :cantidad_stock, :id_categoria, :codigo_barras, :codigo_producto, :url_imagen, :precio_compra, :margen_ganancia, :stock_minimo, :activo, :id_proveedor)");

$sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
$sentencia->bindParam(':precio', $precio, PDO::PARAM_STR);
$sentencia->bindParam(':cantidad_stock', $cantidad_stock, PDO::PARAM_INT);
$sentencia->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
$sentencia->bindParam(':codigo_barras', $codigo_barras, PDO::PARAM_STR);
$sentencia->bindParam(':codigo_producto', $codigo_producto, PDO::PARAM_STR);
$sentencia->bindParam(':url_imagen', $url_imagen, PDO::PARAM_STR);
$sentencia->bindParam(':precio_compra', $precio_compra, PDO::PARAM_STR);
$sentencia->bindParam(':margen_ganancia', $margen_ganancia, PDO::PARAM_STR);
$sentencia->bindParam(':stock_minimo', $stock_minimo, PDO::PARAM_INT);
$sentencia->bindParam(':activo', $activo, PDO::PARAM_STR);
$sentencia->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Producto registrado correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'App/views/productos/index.php');
} else {
    $_SESSION['mensaje'] = "Error al registrar el producto.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'App/views/productos/create.php');
}
?>