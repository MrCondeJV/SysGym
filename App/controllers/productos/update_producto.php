<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID del producto a editar
$id_producto = $_POST['id_producto'] ?? null;

if (!$id_producto || !is_numeric($id_producto)) {
    $_SESSION['mensaje'] = "No se especificó un producto válido para editar.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/productos/index.php');
    exit;
}

// Obtener datos actuales del producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    $_SESSION['mensaje'] = "Producto no encontrado.";
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . 'App/views/productos/index.php');
    exit;
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

        // Mover la imagen a la carpeta de destino
        if (!move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
            $errores[] = "Error al mover la imagen al destino.";
        } else {
            // Eliminar la imagen anterior si existe
            if (!empty($producto['url_imagen']) && file_exists('../../' . $producto['url_imagen'])) {
                unlink('../../' . $producto['url_imagen']);
            }
            // Guardar la nueva URL relativa de la imagen
            $url_imagen = '../../img/productos/' . $nombre_imagen;
        }
    }
} else {
    // Mantener la imagen actual si no se sube una nueva
    $url_imagen = !empty($producto['url_imagen']) ? (strpos($producto['url_imagen'], '../../') === 0 ? $producto['url_imagen'] : '../../' . ltrim($producto['url_imagen'], '/')) : null;
}

// Función para registrar errores en un archivo de log en la carpeta actual
function log_error($mensaje) {
    $logFile = __DIR__ . '/productos_error.log'; // Guardar en la carpeta actual del controlador
    $fecha = date('Y-m-d H:i:s');
    $linea = "[$fecha] $mensaje\n";
    file_put_contents($logFile, $linea, FILE_APPEND);
}

// Si hay errores, registrar en el log y redirigir con mensajes
if (!empty($errores)) {
    log_error("Errores al actualizar producto ID $id_producto: " . implode(' | ', $errores));
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . 'App/views/productos/update.php?id=' . $id_producto);
    exit;
}

// Actualizar producto en la base de datos
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
    ':nombre' => $nombre,
    ':codigo_producto' => $codigo_producto,
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

$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    $_SESSION['mensaje'] = "Producto actualizado correctamente.";
    $_SESSION['icono'] = "success";
    header("Location: $URL/App/views/productos/index.php");
    exit();
} else {
    $errorInfo = $stmt->errorInfo();
    log_error("Error SQL al actualizar producto ID $id_producto: " . print_r($errorInfo, true));
    $_SESSION['mensaje'] = "Error al actualizar el producto.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/productos/update.php?id_producto=$id_producto");
    exit();
}
?>