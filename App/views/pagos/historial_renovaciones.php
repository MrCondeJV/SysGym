<?php
include('../../config.php');
include('../layout/parte1.php');

$id_miembro = isset($_GET['id_miembro']) ? intval($_GET['id_miembro']) : 0;
if ($id_miembro <= 0) {
    echo "<div class='alert alert-danger'>Usuario no válido.</div>";
    include('../layout/parte2.php');
    exit;
}

// Consulta renovaciones del usuario
$stmt = $pdo->prepare("
    SELECT r.*, 
           mp.nombre AS metodo_pago, 
           t.nombre AS tipo_membresia, 
           t.precio,
           me.nombres AS nombre_miembro,
           me.apellidos AS apellido_miembro,
           me.numero_documento,
           CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario_renovo
    FROM renovaciones r
    JOIN metodos_pago mp ON r.id_metodo_pago = mp.id_metodo
    JOIN membresias m ON r.id_membresia = m.id_membresia
    JOIN tiposmembresia t ON m.id_tipo_membresia = t.id_tipo_membresia
    JOIN miembros me ON r.id_miembro = me.id_miembro
    LEFT JOIN usuariossistema u ON r.renovado_por = u.id_usuario
    WHERE r.id_miembro = ?
    ORDER BY r.fecha DESC
");
$stmt->execute([$id_miembro]);
$renovaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-list"></i> Historial de Renovaciones
                    </h1>
                    <p class="text-muted mt-2">Renovaciones realizadas por el usuario seleccionado.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <a href="index.php" class="btn btn-secondary btn-sm float-right">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <h3 class="card-title"><i class="fas fa-history"></i> Renovaciones realizadas</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Miembro</th>
                                            <th>Número de Documento</th>
                                            <th>Fecha</th>
                                            <th>N° Factura</th>
                                            <th>Membresía</th>
                                            <th>Precio</th>
                                            <th>Método de Pago</th>
                                            <th>Renovado por</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($renovaciones as $i => $r): ?>
                                        <tr>
                                            <td><?php echo $i + 1; ?></td>
                                            <td>
                                                <i class="fas fa-user text-info"></i>
                                                <?php echo htmlspecialchars($r['nombre_miembro'] . ' ' . $r['apellido_miembro']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($r['numero_documento']); ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?php echo date('d/m/Y H:i', strtotime($r['fecha'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($r['numero_factura']); ?></td>
                                            <td><?php echo htmlspecialchars($r['tipo_membresia']); ?></td>
                                            <td>
                                                <span class="badge badge-success">
                                                    $<?php echo number_format($r['precio'], 2, ',', '.'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($r['metodo_pago']); ?></td>
                                            <td><?php echo htmlspecialchars($r['nombre_usuario_renovo'] ?? ''); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($r['observaciones'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- Mostrar total de renovaciones de este usuario -->
                                <!-- Reemplaza el bloque del total por este, justo después de la tabla -->
                                <div class="mt-3 text-right">
                                    <span class="h5">
                                        <strong>Total:</strong>
                                        <span class="badge badge-primary" id="total-renovaciones-js">
                                            $<?php
                                                $total = 0;
                                                foreach ($renovaciones as $r) {
                                                    $total += floatval($r['precio']);
                                                }
                                                echo number_format($total, 2, ',', '.');
                                                ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    $("#example1").DataTable({
        "pageLength": 10,
        "language": {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
            "infoEmpty": "Mostrando 0 a 0 de 0 Usuarios",
            "infoFiltered": "(Filtrado de _MAX_ total Usuarios)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Usuarios",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscador:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        buttons: [{
                extend: 'collection',
                text: 'Reportes',
                orientation: 'landscape',
                buttons: [{
                    text: 'Copiar',
                    extend: 'copy',
                }, {
                    extend: 'pdf'
                }, {
                    extend: 'csv'
                }, {
                    extend: 'excel'
                }, {
                    text: 'Imprimir',
                    extend: 'print'
                }]
            },
            {
                extend: 'colvis',
                text: 'Visor de columnas',
                collectionLayout: 'fixed three-column'
            }
        ],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});

function actualizarTotal() {
    var total = 0;
    // La columna de precio es la 6 (índice 6, empieza en 0)
    table.rows({
        search: 'applied'
    }).every(function() {
        var data = this.data();
        // Extrae el número del badge
        var precio = $(data[6]).text().replace(/[^0-9,.-]+/g, "").replace('.', '').replace(',',
            '.');
        total += parseFloat(precio) || 0;
    });
    $('#total-renovaciones-js').text('$' + total.toLocaleString('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
}

table.on('draw', actualizarTotal);
table.on('search', actualizarTotal);
actualizarTotal();
</script>

<?php include('../layout/parte2.php'); ?>