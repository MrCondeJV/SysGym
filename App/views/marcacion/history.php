<?php
// filepath: c:\xampp\htdocs\SysGym\App\views\marcacion\history.php
include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');
include('../../controllers/marcacion/list_historial.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg"
                        style="background: linear-gradient(90deg,rgb(14, 148, 160),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-history fa-lg"></i> Historial de Entradas y Salidas
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-10 col-sm-12 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Registros de Acceso</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">

                                <div class="col-md-4 offset-md-8 text-end">
                                   <label for="fechaInicio" class="form-label mb-0 me-2" ">
                                        <i></i> Filtrar por fecha:
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" id="fechaInicio" class="form-control" placeholder="Desde">
                                        <input type="date" id="fechaFin" class="form-control" placeholder="Hasta">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-sm text-center"
                                    style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">Nro</th>
                                            <th style="width: 10%;">Documento</th>
                                            <th style="width: 18%;">Nombre</th>
                                            <th style="width: 16%;">Fecha y Hora Entrada</th>
                                            <th style="width: 16%;">Fecha y Hora Salida</th>
                                            <th style="width: 10%;">Método</th>
                                            <th style="width: 15%;">Registrado por</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        if ($registros):
                                            foreach ($registros as $r):
                                        ?>
                                                <tr>
                                                    <td><?= ++$contador ?></td>
                                                    <td><?= htmlspecialchars($r['numero_documento']) ?></td>
                                                    <td><?= htmlspecialchars($r['nombres'] . ' ' . $r['apellidos']) ?></td>
                                                    <td><?= htmlspecialchars($r['fecha_entrada']) ?></td>
                                                    <td><?= $r['fecha_salida'] ? htmlspecialchars($r['fecha_salida']) : '<span class="text-muted">-</span>' ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($r['metodo_acceso'] === 'huella') {
                                                            echo '<span class="badge badge-success"><i class="fas fa-fingerprint"></i> Huella</span>';
                                                        } else {
                                                            echo '<span class="badge badge-secondary"><i class="fas fa-keyboard"></i> Manual</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($r['usuario'] ?? '-') ?></td>
                                                </tr>
                                            <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">No hay registros para
                                                    mostrar.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
                    </div>
                </div>
            </div> <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<?php include('../layout/parte2.php'); ?>
<script>
    var nombreEmpleado = "<?php echo strtoupper($nombre_usuario); ?>";

    fetch("/SysGym/public/images/base64.txt")
        .then((response) => response.text())
        .then((base64Image) => {
            $(function() {
                var table = $("#example1").DataTable({
                    dom: "Bfrtip",
                    pageLength: 10,
                    language: {
                        emptyTable: "No hay información",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ marcaciones",
                        infoEmpty: "Mostrando 0 a 0 de 0 Usuarios",
                        infoFiltered: "(Filtrado de _MAX_ total Marcaciones)",
                        infoPostFix: "",
                        thousands: ",",
                        lengthMenu: "Mostrar _MENU_ marcacion",
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
                                    filename: "Reporte_marcacion",
                                    title: "Lista de accesos",
                                    orientation: "landscape",
                                    customize: function(doc) {
                                        // Aquí NO se calcula ni inserta el total

                                        if (doc.content[1] && doc.content[1].table) {
                                            doc.content[1].table.widths = Array(
                                                doc.content[1].table.body[0].length
                                            ).fill("*");

                                            doc.styles.tableHeader.alignment = "center";
                                            doc.styles.tableHeader.fillColor = "#0066cc";
                                            doc.styles.tableHeader.color = "white";
                                            doc.styles.tableHeader.fontSize = 12;

                                            var body = doc.content[1].table.body;
                                            for (var i = 1; i < body.length; i++) {
                                                if (i % 2 === 0) {
                                                    body[i].forEach((cell) => (cell
                                                        .fillColor = "#f2f2f2"));
                                                }
                                            }
                                            body.forEach((row) => {
                                                row.forEach((cell) => {
                                                    cell.alignment =
                                                        "center";
                                                    cell.fontSize = 10;
                                                });
                                            });
                                            doc.content[1].margin = [0, 10, 0, 0];
                                        }

                                        doc.content.unshift({
                                            text: "Listado detallado de accesos por marcacion",
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
                                                    color: "#333",
                                                },
                                                {
                                                    text: "Generado por: " +
                                                        nombreEmpleado,
                                                    fontSize: 10,
                                                    color: "#333",
                                                    alignment: "right",
                                                },
                                            ],
                                            margin: [0, 0, 0, 10],
                                        });

                                        doc.footer = function(currentPage, pageCount) {
                                            return {
                                                margin: [20, 10, 20, 0],
                                                columns: [{
                                                        text: "Escuela de formacion Infanteria de Marina | DITIC ESFIM",
                                                        alignment: "center",
                                                        style: "footer",
                                                    },
                                                    {
                                                        text: "Comentario (si es impreso): _________________________",
                                                        alignment: "center",
                                                        style: "footer",
                                                    },
                                                    {
                                                        text: "Página " +
                                                            currentPage + " de " +
                                                            pageCount,
                                                        alignment: "right",
                                                        fontSize: 10,
                                                        color: "#666",
                                                    },
                                                ],
                                            };
                                        };

                                        doc.styles.footer = {
                                            fontSize: 10,
                                            italics: true,
                                            color: "#666",
                                        };

                                        doc.headers = function(currentPage, pageCount) {
                                            return {
                                                text: "Reporte de Usuarios | Página " +
                                                    currentPage +
                                                    " de " +
                                                    pageCount,
                                                fontSize: 10,
                                                color: "#333",
                                                alignment: "center",
                                                margin: [0, 10, 0, 0],
                                            };
                                        };
                                    },
                                    exportOptions: {
                                        columns: ":not(:first-child):not(:last-child)",
                                    },
                                },
                                {
                                    extend: "csv",
                                    text: '<i class="fas fa-file-csv"></i> CSV',
                                    className: "btn btn-info",
                                    filename: "Reporte_Usuarios",
                                    exportOptions: {
                                        columns: ":not(:last-child)"
                                    },
                                },
                                {
                                    extend: "excel",
                                    text: '<i class="fas fa-file-excel"></i> Excel',
                                    className: "btn btn-success",
                                    filename: "Reporte_Usuarios",
                                    title: "Lista de Usuarios",
                                    exportOptions: {
                                        columns: ":not(:last-child)"
                                    },
                                },
                                {
                                    extend: "print",
                                    text: '<i class="fas fa-print"></i> Imprimir',
                                    className: "btn btn-warning",
                                    title: "Lista de Usuarios",
                                    customize: function(win) {
                                        $(win.document.body).css("font-size", "12px");
                                        $(win.document.body)
                                            .find("table")
                                            .addClass("display")
                                            .css("font-size", "12px");
                                    },
                                    exportOptions: {
                                        columns: ":not(:last-child)"
                                    },
                                },
                            ],
                        },
                        {
                            extend: "colvis",
                            text: '<i class="fas fa-columns"></i> Columnas',
                            className: "btn btn-primary",
                            collectionLayout: "fixed three-column",
                        },
                    ],
                });

                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var min = $('#fechaInicio').val();
                        var max = $('#fechaFin').val();
                        var fecha = data[3]; // Columna "Fecha y Hora Entrada" (índice base 0)
                        if (!min && !max) return true;
                        if (!fecha) return false;
                        var fechaEntrada = fecha.split(' ')[0];
                        if (min && fechaEntrada < min) return false;
                        if (max && fechaEntrada > max) return false;
                        return true;
                    }
                );

                $('#fechaInicio, #fechaFin').on('change', function() {
                    $('#example1').DataTable().draw();
                });
            });
        })
        .catch((error) => {
            console.error("Error al cargar el archivo Base64:", error);
        });
</script>