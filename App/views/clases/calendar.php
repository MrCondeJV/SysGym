<?php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/clases/list_clases.php');
// include('../layout/sesion.php');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(14, 148, 160),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-dumbbell fa-lg"></i> Calendario de Clases
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
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Clases Programadas</h3>
                            <div class="card-tools">
                                <a href="create.php" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus-circle"></i> Nueva Clase
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal para mostrar la información de la clase -->
<div class="modal fade" id="modalClase" tabindex="-1" role="dialog" aria-labelledby="modalClaseLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalClaseLabel">Información de la Clase</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Aquí se carga la info de la clase -->
      </div>
      <div class="modal-footer">
        <a href="#" id="btnVerClase" class="btn btn-info" style="display:none;" target="_blank">
            <i class="fas fa-eye"></i> Ver Detalles
        </a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- SCRIPT DE EVENTOS EN EL CALENDARIO -->
<script>
var eventos = [
<?php
$dias = [
    'Lunes' => 1,
    'Martes' => 2,
    'Miércoles' => 3,
    'Miercoles' => 3,
    'Jueves' => 4,
    'Viernes' => 5,
    'Sábado' => 6,
    'Sabado' => 6,
    'Domingo' => 0
];
$startRecur = date('Y-m-d', strtotime('monday this week'));
$eventos = [];

foreach ($clases_datos as $clase) {
    $diaSemana = $dias[$clase['dia_semana']] ?? null;
    if ($diaSemana !== null) {
        $eventos[] = [
            'id' => $clase['id_clase'],
            'title' => $clase['nombre'],
            'daysOfWeek' => [$diaSemana],
            'startTime' => substr($clase['horario'], 0, 5),
            'startRecur' => $startRecur,
            'extendedProps' => [
                'descripcion' => $clase['descripcion'],
                'duracion' => $clase['duracion_minutos'],
                'capacidad' => $clase['capacidad_maxima'],
                'precio' => $clase['precio'],
                'nivel' => $clase['nivel'],
                'sala' => $clase['id_sala'],
                'entrenador' => $clase['id_entrenador'],
                'dia' => $clase['dia_semana'],
                'id_clase' => $clase['id_clase']
            ]
        ];
    }
}
echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
?>
];
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: eventos,
        eventClick: function(info) {
            var props = info.event.extendedProps;
            var start = info.event.start;
            var hora = start ? start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
            var fecha = start ? start.toLocaleDateString() : '';
            $('#modalClase .modal-title').text(info.event.title);
            $('#modalClase .modal-body').html(
                '<b>Descripción:</b> ' + (props.descripcion ?? '') + '<br>' +
                '<b>Día:</b> ' + props.dia + '<br>' +
                '<b>Horario:</b> ' + hora + '<br>' +
                '<b>Duración:</b> ' + props.duracion + ' min<br>' +
                '<b>Capacidad:</b> ' + props.capacidad + '<br>' +
                '<b>Precio:</b> $' + props.precio + '<br>' +
                '<b>Nivel:</b> ' + props.nivel + '<br>' +
                '<b>Sala:</b> ' + props.sala + '<br>' +
                '<b>Entrenador:</b> ' + props.entrenador
            );
            $('#btnVerClase').attr('href', 'show.php?id=' + props.id_clase).show();
            $('#modalClase').modal('show');
        }
    });

    // 🔍 Imprimir eventos en consola
    console.log("Eventos cargados en el calendario:", eventos);

    calendar.render();

    $('#modalClase').on('hidden.bs.modal', function () {
        $('#btnVerClase').hide();
    });
});

</script>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
