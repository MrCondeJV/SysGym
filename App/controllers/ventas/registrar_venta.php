<?php
// filepath: c:\xampp\htdocs\SysGym\App\controllers\ventas\registrar_venta.php

include('../../config.php');
session_start();

$productos_json = $_POST['productos'] ?? '';
$productos = json_decode($productos_json, true);

if (!$productos || !is_array($productos) || count($productos) == 0) {
    $_SESSION['mensaje'] = "No se recibieron productos para la venta.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/ventas/index.php");
    exit();
}

try {
    $pdo->beginTransaction();

    // Insertar la venta principal
    $sql_venta = "INSERT INTO ventas (fecha_venta, total) VALUES (NOW(), 0)";
    $stmt_venta = $pdo->prepare($sql_venta);
    $stmt_venta->execute();
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
    $_SESSION['mensaje'] = "Venta registrada correctamente.";
    $_SESSION['icono'] = "success";
    header("Location: $URL/App/views/ventas/index.php");
    exit();
} catch (Exception $e) {
    $pdo->rollBack();


    $_SESSION['mensaje'] = "Error al registrar la venta: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/ventas/index.php");
    exit();
}
?>