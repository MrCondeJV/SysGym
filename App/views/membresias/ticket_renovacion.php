<?php
include('../../config.php');

$id_renovacion = isset($_GET['id_renovacion']) ? intval($_GET['id_renovacion']) : 0;
if ($id_renovacion <= 0) {
    echo "<div class='alert alert-danger'>Ticket no válido.</div>";
    exit;
}

// Consulta los datos de la renovación y la membresía
$stmt = $pdo->prepare("
    SELECT r.*, 
           m.id_tipo_membresia, 
           me.nombres AS nombre_miembro, 
           mp.nombre AS metodo_pago, 
           t.precio AS precio_membresia
    FROM renovaciones r
    JOIN membresias m ON r.id_membresia = m.id_membresia
    JOIN miembros me ON r.id_miembro = me.id_miembro
    JOIN metodos_pago mp ON r.id_metodo_pago = mp.id_metodo
    JOIN tiposmembresia t ON m.id_tipo_membresia = t.id_tipo_membresia
    WHERE r.id_renovacion = ?
    LIMIT 1
");
$stmt->execute([$id_renovacion]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    echo "<div class='alert alert-danger'>Ticket no encontrado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket de Renovación</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
    .ticket {
        max-width: 400px;
        margin: 30px auto;
        border: 1px dashed #333;
        padding: 20px;
        background: #fff;
    }

    .ticket h4 {
        margin-bottom: 20px;
    }

    .ticket .btn {
        margin-top: 20px;
    }

    @media print {
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            background: #fff !important;
        }

        .ticket {
            max-width: none;
            width: 58mm !important;
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 0 2mm !important;
            font-family: 'Courier New', Courier, monospace !important;
            font-size: 12px !important;
        }

        .ticket h4 {
            margin-bottom: 10px !important;
        }

        .ticket hr {
            margin: 8px 0 !important;
        }

        .btn,
        .btn-block,
        a.btn,
        .acciones {
            display: none !important;
        }
    }
    </style>
</head>

<body>
    <div class="ticket">
        <h4 class="text-center">Ticket de Renovación</h4>
        <p><strong>Miembro:</strong> <?php echo htmlspecialchars($ticket['nombre_miembro']); ?></p>
        <p><strong>N° Factura:</strong> <?php echo htmlspecialchars($ticket['numero_factura']); ?></p>
        <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($ticket['metodo_pago']); ?></p>
        <p><strong>Precio:</strong> $<?php echo number_format($ticket['precio_membresia'], 2, ',', '.'); ?></p>
        <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($ticket['fecha'])); ?></p>
        <hr>
        <p class="text-center mb-0"><small>¡Gracias por su renovación!</small></p>
        <button class="btn btn-primary btn-block" onclick="window.print()">Imprimir Ticket</button>
        <a href="index.php" class="btn btn-secondary btn-block">Volver</a>
    </div>
</body>

</html>