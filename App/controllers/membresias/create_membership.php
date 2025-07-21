<?php
session_start();
$creado_por = $_SESSION['id_usuario'] ?? null; // Ajusta según tu sistema de login
require_once '../../config.php'; // Ajusta la ruta a tu conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $id_miembro = isset($_POST['id_miembro']) ? intval($_POST['id_miembro']) : 0;
    $id_tipo_membresia = isset($_POST['id_tipo_membresia']) ? intval($_POST['id_tipo_membresia']) : 0;
    $numero_factura = isset($_POST['numero_factura']) ? trim($_POST['numero_factura']) : '';

    if ($id_miembro <= 0 || $id_tipo_membresia <= 0 || $numero_factura === '') {
        $_SESSION['error'] = "Datos inválidos o falta el número de factura.";
        header("Location: ../../views/membresias/create.php");
        exit();
    }

    try {
        // Verificar existencia de miembro
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM miembros WHERE id_miembro = ?");
        $stmt->execute([$id_miembro]);
        if ($stmt->fetchColumn() == 0) {
            $_SESSION['error'] = "El miembro seleccionado no existe.";
            header("Location: ../../views/membresias/create.php");
            exit();
        }

        // Validar que no tenga membresía activa
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM membresias WHERE id_miembro = ? AND fecha_fin >= CURDATE()");
        $stmt->execute([$id_miembro]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = "El miembro ya tiene una membresía activa.";
            header("Location: ../../views/membresias/create.php");
            exit();
        }

        // Obtener duracion_dias de tipo membresia
        $stmt = $pdo->prepare("SELECT duracion_dias FROM tiposmembresia WHERE id_tipo_membresia = ?");
        $stmt->execute([$id_tipo_membresia]);
        $duracion = $stmt->fetchColumn();

        if (!$duracion) {
            $_SESSION['error'] = "Tipo de membresía no válido.";
            header("Location: ../../views/membresias/create.php");
            exit();
        }

        // Calcular fechas
        $fecha_inicio = date('Y-m-d');
        $fecha_fin = date('Y-m-d', strtotime("+$duracion days"));

        // Insertar membresía (agregando numero_factura)
        $insert = $pdo->prepare("INSERT INTO membresias (id_miembro, id_tipo_membresia, creado_por, fecha_inicio, fecha_fin, numero_factura)
            VALUES (?, ?, ?, ?, ?, ?)");
        $insert->execute([$id_miembro, $id_tipo_membresia, $creado_por, $fecha_inicio, $fecha_fin, $numero_factura]);

        // Obtener el id de la membresía recién creada
        $id_membresia = $pdo->lastInsertId();

        // Insertar registro en la tabla renovaciones
        $insertRenovacion = $pdo->prepare("INSERT INTO renovaciones 
            (id_membresia, id_miembro, numero_factura, id_metodo_pago, renovado_por, observaciones, fecha)
            VALUES (?, ?, ?, ?, ?, ?, NOW())");
        // Puedes ajustar id_metodo_pago y observaciones según tu formulario, aquí se ponen valores por defecto
        $id_metodo_pago = 1; // Por defecto, o cámbialo según tu lógica/formulario
        $observaciones = 'Registro de nueva membresía';
        $insertRenovacion->execute([
            $id_membresia,
            $id_miembro,
            $numero_factura,
            $id_metodo_pago,
            $creado_por,
            $observaciones
        ]);

        $_SESSION['success'] = "Membresía registrada correctamente.";
        header("Location: ../../views/membresias/index.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al registrar la membresía: " . $e->getMessage();
        header("Location: ../../views/membresias/create.php");
        exit();
    }
} else {
    // Método no permitido
    http_response_code(405);
    echo "Método no permitido";
    exit();
}
