<?php
include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Filtro de fechas
$where = [];
$params = [];

if (!empty($_GET['fecha_inicio'])) {
    $where[] = "DATE(r.fecha) >= :fecha_inicio";
    $params[':fecha_inicio'] = $_GET['fecha_inicio'];
}
if (!empty($_GET['fecha_fin'])) {
    $where[] = "DATE(r.fecha) <= :fecha_fin";
    $params[':fecha_fin'] = $_GET['fecha_fin'];
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $pdo->prepare("
    SELECT r.*, 
           me.nombres AS nombre_miembro, 
           me.apellidos AS apellido_miembro,
           me.numero_documento,
           mp.nombre AS metodo_pago, 
           t.nombre AS tipo_membresia, 
           t.precio,
           CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario_renovo
    FROM renovaciones r
    JOIN miembros me ON r.id_miembro = me.id_miembro
    JOIN metodos_pago mp ON r.id_metodo_pago = mp.id_metodo
    JOIN membresias m ON r.id_membresia = m.id_membresia
    JOIN tiposmembresia t ON m.id_tipo_membresia = t.id_tipo_membresia
    LEFT JOIN usuariossistema u ON r.renovado_por = u.id_usuario
    $where_sql
    ORDER BY r.fecha DESC
");
$stmt->execute($params);
$renovaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($renovaciones as $r) {
    $total += floatval($r['precio']);
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-list"></i> Historial General de Renovaciones
                    </h1>
                    <p class="text-muted mt-2">Consulta todas las renovaciones realizadas por los miembros.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <form method="get" class="mb-3">
                        <div class="form-row align-items-end">
                            <div class="col-auto">
                                <label for="fecha_inicio" class="mb-0">Desde</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                    value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? '') ?>">
                            </div>
                            <div class="col-auto">
                                <label for="fecha_fin" class="mb-0">Hasta</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                    value="<?= htmlspecialchars($_GET['fecha_fin'] ?? '') ?>">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                                <a href="" class="btn btn-outline-secondary">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-history"></i> Todas las renovaciones</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Miembro</th>
                                            <th>Documento</th>
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
                                            <td>
                                                <?php echo htmlspecialchars($r['nombre_usuario_renovo'] ?? ''); ?>
                                            </td>
                                            <td><?php echo nl2br(htmlspecialchars($r['observaciones'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="mt-3 text-right">
                                    <span class="h5">
                                        <strong>Total:</strong>
                                        <span class="badge badge-primary" id="total-renovaciones-js">
                                            $<?= number_format($total, 2, ',', '.') ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="../pagos/index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>
<!-- DataTables -->
<script>
var nombreEmpleado = "<?php echo strtoupper($nombre_usuario); ?>";

fetch('/SysGym/public/images/base64.txt')
    .then(response => response.text())
    .then(base64Image => {
        $(function() {
            // Declarar table en ámbito accesible para usar dentro de customize
            var table = $('#example1').DataTable({
                dom: 'Bfrtip',
                pageLength: 10,
                language: {
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
                    infoEmpty: "Mostrando 0 a 0 de 0 Usuarios",
                    infoFiltered: "(Filtrado de _MAX_ total Usuarios)",
                    infoPostFix: "",
                    thousands: ",",
                    lengthMenu: "Mostrar _MENU_ Usuarios",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscador:",
                    zeroRecords: "Sin resultados encontrados",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior",
                    },
                },
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: [{
                        extend: "collection",
                        text: '<i class="fas fa-file-export"></i> Reportes',
                        className: "btn btn-success",
                        buttons: [{
                                extend: "copy",
                                text: '<i class="fas fa-copy"></i> Copiar',
                                className: "btn btn-secondary",
                                exportOptions: {
                                    columns: ":not(:last-child)"
                                },
                            },
                            {
                                extend: "pdf",
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: "btn btn-danger",
                                filename: "Reporte_Renovaciones_Usuarios",
                                title: "Lista de Renovaciones de Usuarios",
                                orientation: "landscape",
                                customize: function(doc) {
                                    // Usar la variable table, no this.api()
                                    var data = table.rows({
                                        search: 'applied'
                                    }).data();
                                    var totalPDF = 0;
                                    for (var i = 0; i < data.length; i++) {
                                        var precioText = $('<div>').html(data[i][6])
                                            .text();
                                        var precioNum = parseFloat(precioText.replace(
                                                /[^0-9,.-]+/g, '').replace('.', '')
                                            .replace(',', '.')) || 0;
                                        totalPDF += precioNum;
                                    }
                                    totalPDF = totalPDF.toLocaleString('es-ES', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });

                                    // Insertar total antes de la tabla
                                    doc.content.unshift({
                                        text: 'Total: $' + totalPDF,
                                        fontSize: 12,
                                        bold: true,
                                        margin: [0, 0, 0, 10],
                                        alignment: 'right'
                                    });

                                    if (doc.content[2] && doc.content[2].table) {
                                        doc.content[2].table.widths = Array(doc.content[
                                            2].table.body[0].length).fill("*");

                                        doc.styles.tableHeader.alignment = "center";
                                        doc.styles.tableHeader.fillColor = "#0066cc";
                                        doc.styles.tableHeader.color = "white";
                                        doc.styles.tableHeader.fontSize = 12;

                                        var body = doc.content[2].table.body;
                                        for (var i = 1; i < body.length; i++) {
                                            if (i % 2 === 0) {
                                                body[i].forEach(cell => cell.fillColor =
                                                    "#f2f2f2");
                                            }
                                        }
                                        body.forEach(row => {
                                            row.forEach(cell => {
                                                cell.alignment =
                                                    "center";
                                                cell.fontSize = 10;
                                            });
                                        });
                                        doc.content[2].margin = [0, 10, 0, 0];
                                    }

                                    doc.content.unshift({
                                        text: "Listado detallado de renovaciones",
                                        fontSize: 10,
                                        color: "#333",
                                        alignment: "center",
                                        margin: [0, 5, 0, 10],
                                    });
                                    doc.content.unshift({
                                        image: base64Image.trim(),
                                        width: 100,
                                        alignment: "center",
                                        margin: [0, -30, 0, 10],
                                    });
                                    var fechaActual = new Date().toLocaleDateString();
                                    doc.content.unshift({
                                        columns: [{
                                                text: "Fecha del reporte: " +
                                                    fechaActual,
                                                fontSize: 10,
                                                color: "#333"
                                            },
                                            {
                                                text: "Generado por: " +
                                                    nombreEmpleado,
                                                fontSize: 10,
                                                color: "#333",
                                                alignment: "right"
                                            }
                                        ],
                                        margin: [0, 0, 0, 10],
                                    });

                                    doc.footer = function(currentPage, pageCount) {
                                        return {
                                            margin: [20, 10, 20, 0],
                                            columns: [{
                                                    text: "Escuela de formacion Infanteria de Marina | Copyright © 2025 Mamba Code.",
                                                    alignment: "center",
                                                    style: "footer"
                                                },
                                                {
                                                    text: "Comentario (si es impreso): _________________________",
                                                    alignment: "center",
                                                    style: "footer"
                                                },
                                                {
                                                    text: "Página " +
                                                        currentPage + " de " +
                                                        pageCount,
                                                    alignment: "right",
                                                    fontSize: 10,
                                                    color: "#666"
                                                }
                                            ]
                                        };
                                    };

                                    doc.styles.footer = {
                                        fontSize: 10,
                                        italics: true,
                                        color: "#666"
                                    };

                                    doc.headers = function(currentPage, pageCount) {
                                        return {
                                            text: "Reporte de Usuarios | Página " +
                                                currentPage + " de " + pageCount,
                                            fontSize: 10,
                                            color: "#333",
                                            alignment: "center",
                                            margin: [0, 10, 0, 0],
                                        };
                                    };
                                },
                                exportOptions: {
                                    columns: ":not(:first-child):not(:eq(6))"

                                }
                            },
                            {
                                extend: "csv",
                                text: '<i class="fas fa-file-csv"></i> CSV',
                                className: "btn btn-info",
                                filename: "Reporte_Usuarios",
                                exportOptions: {
                                    columns: ":not(:last-child)"
                                }
                            },
                            {
                                extend: "excel",
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: "btn btn-success",
                                filename: "Reporte_Usuarios",
                                title: "Lista de Usuarios",
                                exportOptions: {
                                    columns: ":not(:last-child)"
                                }
                            },
                            {
                                extend: "print",
                                text: '<i class="fas fa-print"></i> Imprimir',
                                className: "btn btn-warning",
                                title: "Lista de Usuarios",
                                customize: function(win) {
                                    $(win.document.body).css("font-size", "12px");
                                    $(win.document.body).find("table").addClass(
                                        "display").css("font-size", "12px");
                                },
                                exportOptions: {
                                    columns: ":not(:last-child)"
                                }
                            }
                        ]
                    },
                    {
                        extend: "colvis",
                        text: '<i class="fas fa-columns"></i> Columnas',
                        className: "btn btn-primary",
                        collectionLayout: "fixed three-column"
                    }
                ]
            });

            function actualizarTotal() {
                var total = 0;
                table.rows({
                    search: 'applied'
                }).every(function() {
                    var data = this.data();
                    var precio = $(data[6]).text().replace(/[^0-9,.-]+/g, "").replace('.', '')
                        .replace(',', '.');
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
        });
    })
    .catch(error => {
        console.error("Error al cargar el archivo Base64:", error);
    });
</script>