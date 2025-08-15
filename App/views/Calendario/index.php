<?php
include('../../config.php');
include('../layout/parte1.php');
// include('../layout/sesion.php');



?>

<div class="content-wrapper">
    <!-- Encabezado -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg"
                        style="background: linear-gradient(90deg, #15297C, #1E3FA9); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-user-circle fa-lg"></i> Solicitudes de Salas
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido -->
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <!-- Calendario -->
                <div class="col-lg-8">
                    <div class="card card-primary shadow">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Calendario de Reservas</h3>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>

                <!-- Lista de próximos eventos -->
                <div class="col-lg-4">
                    <div class="card shadow">
                        <div class="card-header bg-gradient-info text-white">
                            <h3 class="card-title"><i class="fas fa-list"></i> Próximos eventos</h3>
                        </div>
                        <div class="card-body p-2" style="max-height: 600px; overflow-y: auto;">
                            <div id="lista-eventos" class="d-flex flex-column gap-2">
                                <!-- Se llenará con JS -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detalles Evento -->
<div class="modal fade" id="modalEvento" tabindex="-1" role="dialog" aria-labelledby="modalEventoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalEventoLabel">Detalles del Evento</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="evento-detalle" class="container-fluid"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- FullCalendar -->
<link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fullcalendar/main.min.css">
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fullcalendar/main.min.js"></script>

<!-- Estilos personalizados -->
<style>
    .evento-card {
        border: 1px solid #ddd;
        border-left: 5px solid #1E3FA9;
        border-radius: 6px;
        padding: 10px;
        background: #fff;
        box-shadow: 0px 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 8px;
    }
    .evento-fecha {
        font-weight: bold;
        font-size: 0.9rem;
        color: #1E3FA9;
    }
    .evento-titulo {
        font-size: 1rem;
        margin: 3px 0;
    }
    .evento-meta {
        font-size: 0.85rem;
        color: #666;
    }
    .badge-aprobada { background-color: #28a745; }
    .badge-pendiente { background-color: #ffc107; color: #000; }
    .badge-rechazada { background-color: #dc3545; }
    #calendar {
        min-height: 400px;
        max-height: 60vh;
        overflow-y: auto;
    }
    .fc {
        font-size: 0.95rem;
    }
    #lista-eventos {
        max-height: 60vh;
        overflow-y: auto;
    }
    @media (max-width: 991px) {
        #calendar {
            max-height: 40vh;
        }
        #lista-eventos {
            max-height: 40vh;
        }
    }
</style>

<!-- Script para calendario y listado -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var listaEventosEl = document.getElementById('lista-eventos');
    var eventosData = [];

    // Cargar eventos vía AJAX
    fetch('eventos.php')
        .then(response => response.json())
        .then(data => {
            eventosData = data;
            // Inicializar calendario
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: eventosData,
                eventClick: function(info) {
                    var evento = info.event.extendedProps;
                    var html = `
                        <div class=\"row\">
                            <div class=\"col-md-8\">
                                <h4>${info.event.title}</h4>
                                <p><b>Descripción:</b> ${evento.description || ''}</p>
                                <p><b>Fecha y hora:</b> ${new Date(info.event.start).toLocaleString('es-CO')} - ${new Date(info.event.end).toLocaleString('es-CO')}</p>
                                <p><b>Aula:</b> ${evento.aula || ''}</p>
                                <p><b>Usuario:</b> ${evento.usuario || ''}</p>
                                <span class=\"badge ${evento.estado === 'aprobada' ? 'badge-success' : (evento.estado === 'rechazada' ? 'badge-danger' : 'badge-warning')}\">${evento.estado}</span>
                            </div>
                        </div>
                    `;
                    document.getElementById('evento-detalle').innerHTML = html;
                    $('#modalEvento').modal('show');
                }
            });
            calendar.render();

            // Llenar próximos eventos (solo futuros, ordenados)
            var ahora = new Date();
            var proximos = eventosData.filter(ev => new Date(ev.start) >= ahora);
            proximos.sort((a, b) => new Date(a.start) - new Date(b.start));
            listaEventosEl.innerHTML = '';
            proximos.slice(0, 8).forEach(ev => {
                var card = document.createElement('div');
                card.classList.add('evento-card');
                var fecha = document.createElement('div');
                fecha.classList.add('evento-fecha');
                fecha.textContent = new Date(ev.start).toLocaleDateString('es-CO', { day: '2-digit', month: 'short' }) +
                    ' ' + new Date(ev.start).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' });
                var titulo = document.createElement('div');
                titulo.classList.add('evento-titulo');
                titulo.textContent = ev.title;
                var meta = document.createElement('div');
                meta.classList.add('evento-meta');
                meta.textContent = (ev.aula ? '📍 ' + ev.aula + ' | ' : '') + (ev.usuario ? '👤 ' + ev.usuario : '');
                if (ev.estado) {
                    let badge = document.createElement('span');
                    badge.classList.add('badge');
                    if (ev.estado.toLowerCase() === 'aprobada') badge.classList.add('badge-aprobada');
                    if (ev.estado.toLowerCase() === 'pendiente') badge.classList.add('badge-pendiente');
                    if (ev.estado.toLowerCase() === 'rechazada') badge.classList.add('badge-rechazada');
                    badge.textContent = ev.estado;
                    meta.appendChild(document.createTextNode(' '));
                    meta.appendChild(badge);
                }
                card.appendChild(fecha);
                card.appendChild(titulo);
                card.appendChild(meta);
                listaEventosEl.appendChild(card);
            });
        });
});
</script>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
