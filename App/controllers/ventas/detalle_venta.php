<?php
// --- Clase para obtener el detalle de la venta ---
class VentaDetalle {
    private $pdo;
    public $venta = [];
    public $detalles = [];

    public function __construct($pdo, $id_venta) {
        $this->pdo = $pdo;
        $this->cargarVenta($id_venta);
        $this->cargarDetalles($id_venta);
    }

    private function cargarVenta($id_venta) {
        $sql = "SELECT v.id_venta, v.fecha_venta, v.total, v.numero_factura, v.descuento_total,
                       CONCAT(u.nombres, ' ', u.apellidos) AS usuario, 
                       m.nombre AS metodo_pago
                FROM ventas v
                LEFT JOIN usuariossistema u ON v.id_usuario = u.id_usuario
                LEFT JOIN metodos_pago m ON v.id_metodo_pago = m.id_metodo
                WHERE v.id_venta = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_venta]);
        $this->venta = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function cargarDetalles($id_venta) {
        $sql = "SELECT d.cantidad, d.precio_unitario, d.subtotal, p.nombre
                FROM detallesventa d
                LEFT JOIN productos p ON d.id_producto = p.id_producto
                WHERE d.id_venta = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_venta]);
        $this->detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// --- Obtener el id de la venta y cargar datos ---
$id_venta = $_GET['id'] ?? null;
$detalleVenta = null;
if ($id_venta) {
    $detalleVenta = new VentaDetalle($pdo, $id_venta);
}