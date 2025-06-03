<?php
// filepath: c:\xampp\htdocs\SysGym\App\views\ventas\history.php

include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');
#include('../../controllers/ventas/historial_ventas.php');
include('../../controllers/ventas/listado_ventas_general.php');


$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-history"></i> Historial de Ventas
                    </h1>
                    <p class="text-muted mt-2">Consulta todas las ventas realizadas en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Filtro de fechas -->
            <form method="get" class="mb-3">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <label for="fecha_inicio">Desde:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                            value="<?= htmlspecialchars($fecha_inicio) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin">Hasta:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                            value="<?= htmlspecialchars($fecha_fin) ?>">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i>
                            Filtrar</button>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <a href="history.php" class="btn btn-secondary btn-block"><i class="fas fa-times"></i>
                            Limpiar</a>
                    </div>
                </div>
            </form>
            <!-- Fin filtro de fechas -->

            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Ventas Registradas</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm text-center" id="example1">
                                    <thead>
                                        <tr>
                                            <th>ID Venta</th>
                                            <th>Factura</th> <!-- Nueva columna -->
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Usuario</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($ventas)): ?>
                                        <?php foreach ($ventas as $venta): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($venta['id_venta']) ?></td>
                                            <td><?= htmlspecialchars($venta['numero_factura'] ?? '-') ?></td>
                                            <!-- Mostrar número de factura -->
                                            <td><?= htmlspecialchars($venta['fecha_venta']) ?></td>
                                            <td>$<?= number_format($venta['total'], 2) ?></td>
                                            <td><?= htmlspecialchars($venta['nombre_usuario'] ?? 'Desconocido') ?></td>
                                            <td>
                                                <a href="detalle.php?id=<?= $venta['id_venta'] ?>"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Ver Detalle
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="6">No hay ventas registradas.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <div class="mt-3 text-right">
                                    <span class="h5">
                                        <strong>Total:</strong>
                                        <span class="badge badge-primary" id="total-ventas-js">
                                            $<?= number_format($total_ventas, 2, ',', '.') ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<?php include('../layout/parte2.php'); ?>

<script>
var nombreEmpleado = "<?php echo strtoupper($nombre_usuario); ?>";

fetch('/SysGym/public/images/base64.txt')
    .then(response => response.text())
    .then(base64Image => {
        $(function() {
            var table = $('#example1').DataTable({
                dom: 'Bfrtip',
                pageLength: 10,
                language: {
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ Ventas",
                    infoEmpty: "Mostrando 0 a 0 de 0 Ventas",
                    infoFiltered: "(Filtrado de _MAX_ total Ventas)",
                    infoPostFix: "",
                    thousands: ",",
                    lengthMenu: "Mostrar _MENU_ Ventas",
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
                                }
                            },
                            {
                                extend: "pdf",
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: "btn btn-danger",
                                filename: "Reporte_ventas",
                                title: "Lista de ventas",
                                orientation: "landscape",
                                customize: function(doc) {
                                    var data = table.rows({
                                        search: 'applied'
                                    }).data();
                                    var totalPDF = 0;
                                    for (var i = 0; i < data.length; i++) {
                                        var precioText = data[i][3];
                                        precioText = precioText.replace(/\$/g, '');
                                        var precioNum = Number(precioText.replace(/,/g,
                                            '')) || 0;
                                        totalPDF += precioNum;
                                    }
                                    totalPDF = totalPDF.toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });

                                    doc.content.unshift({
                                        text: 'Total: $' + totalPDF,
                                        fontSize: 12,
                                        bold: true,
                                        margin: [0, 0, 0, 20],
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
                                        text: "Listado detallado de ventas",
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
                                                    text: "Escuela de formacion Infanteria de Marina | DITIC ESFIM",
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
                                    columns: ":not(:first-child):not(:last-child)"
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
                    var precio = data[3];
                    precio = precio.replace(/\$/g, '');
                    total += Number(precio.replace(/,/g, '')) || 0;
                });
                $('#total-ventas-js').text('$' + total.toLocaleString('en-US', {
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