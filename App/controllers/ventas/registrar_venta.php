<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$productos_json = $_POST['productos'] ?? '';
$productos = json_decode($productos_json, true);

// Obtener el método de pago
$id_metodo_pago = $_POST['metodo_pago'] ?? null;

if (!$productos || !is_array($productos) || count($productos) == 0) {
    $_SESSION['mensaje'] = "No se recibieron productos para la venta.";
    $_SESSION['icono'] = "error";
    header("Location: {$URL}App/views/ventas/index.php");
    exit();
}

if (!$id_metodo_pago) {
    $_SESSION['mensaje'] = "Debe seleccionar un método de pago.";
    $_SESSION['icono'] = "error";
    header("Location: {$URL}App/views/ventas/index.php");
    exit();
}

try {
    $pdo->beginTransaction();

    // Obtener el usuario de la sesión
    $id_usuario = $_SESSION['id_usuario'] ?? null;
    if (!$id_usuario) {
        throw new Exception("No se encontró el usuario en sesión.");
    }

    // Generar número de factura único (ejemplo: F20240516-00001)
    $fecha_factura = date('Ymd');
    $sql_last = "SELECT numero_factura FROM ventas WHERE numero_factura LIKE ? ORDER BY id_venta DESC LIMIT 1";
    $like_factura = "F{$fecha_factura}-%";
    $stmt_last = $pdo->prepare($sql_last);
    $stmt_last->execute([$like_factura]);
    $last_factura = $stmt_last->fetchColumn();

    if ($last_factura) {
        $last_num = (int)substr($last_factura, -5);
        $nuevo_num = str_pad($last_num + 1, 5, '0', STR_PAD_LEFT);
    } else {
        $nuevo_num = '00001';
    }
    $numero_factura = "F{$fecha_factura}-{$nuevo_num}";

    // Insertar la venta principal con id_usuario, id_metodo_pago y numero_factura
    $sql_venta = "INSERT INTO ventas (fecha_venta, total, id_usuario, id_metodo_pago, numero_factura) VALUES (NOW(), 0, ?, ?, ?)";
    $stmt_venta = $pdo->prepare($sql_venta);
    $stmt_venta->execute([$id_usuario, $id_metodo_pago, $numero_factura]);
    $id_venta = $pdo->lastInsertId();

    $total_venta = 0;

    // Insertar los productos vendidos y actualizar stock
    foreach ($productos as $prod) {
        $id_producto = $prod['id'];
        $cantidad = $prod['cantidad'];
        $precio = $prod['precio'];
        $subtotal = $precio * $cantidad;
        $total_venta += $subtotal;

        // Insertar detalle de venta
        $sql_detalle = "INSERT INTO detallesventa (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
        $stmt_detalle = $pdo->prepare($sql_detalle);
        $stmt_detalle->execute([$id_venta, $id_producto, $cantidad, $precio, $subtotal]);

        // Actualizar stock del producto
        $sql_stock = "UPDATE productos SET cantidad_stock = cantidad_stock - ? WHERE id_producto = ? AND cantidad_stock >= ?";
        $stmt_stock = $pdo->prepare($sql_stock);
        $stmt_stock->execute([$cantidad, $id_producto, $cantidad]);
        if ($stmt_stock->rowCount() == 0) {
            throw new Exception("Stock insuficiente para el producto ID $id_producto.");
        }
    }

    // Actualizar el total de la venta
    $sql_update_total = "UPDATE ventas SET total = ? WHERE id_venta = ?";
    $stmt_update_total = $pdo->prepare($sql_update_total);
    $stmt_update_total->execute([$total_venta, $id_venta]);

    $pdo->commit();
    $_SESSION['mensaje'] = "Venta registrada correctamente. Factura: $numero_factura";
    $_SESSION['icono'] = "success";
    header("Location: {$URL}App/views/ventas/index.php");
    exit();
} catch (Exception $e) {
    $pdo->rollBack();

    // Guardar log de error
    $logFile = __DIR__ . '/errores_ventas.log';
    $errorMsg = "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $errorMsg, FILE_APPEND);

    $_SESSION['mensaje'] = "Error al registrar la venta: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header("Location: {$URL}App/views/ventas/index.php");
    exit();
}
?>