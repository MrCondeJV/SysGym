<link rel="stylesheet" href="/sistransporte/layout/notificaciones_navbar.css">
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark bg-dark shadow-sm mb-0">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Menú lateral">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <!-- Notifications -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-light navbar-badge" id="notification-counter">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-height: 500px; overflow-y: auto;">
                    <span class="dropdown-header" id="notification-header">0 Notificaciones</span>
                    <div class="dropdown-divider"></div>
                    <div id="notification-list" class="px-3">
                        <!-- Notificaciones dinámicas -->
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer" id="mark-all-read">Marcar todas como leídas</a>
                </div>
            </li>

            <!-- Sonido -->
            <li class="nav-item">
                <button id="activar-sonido" class="btn btn-warning btn-sm ml-2" style="display: none;">
                    <i class="fas fa-volume-up"></i> Activar sonido
                </button>
                <audio id="notificacion-sound" src="/sistransporte/uploads/notificacion/notificacion.mp3"></audio>
            </li>

            <!--Mantenimiento-->

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-tools"></i> <!-- Ícono diferente para mantenimiento -->
                    <span class="badge badge-warning navbar-badge" id="maintenance-counter">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-height: 500px; overflow-y: auto;">
                    <span class="dropdown-header" id="maintenance-header">0 Recordatorios</span>
                    <div class="dropdown-divider"></div>
                    <div id="maintenance-list" class="px-3">
                        <!-- Recordatorios dinámicos -->
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer" id="mark-maintenance-read">Marcar todos como leídos</a>
                </div>
            </li>

            <!-- Dark Mode Toggle -->
            <li class="nav-item d-flex align-items-center">
                <span class="mr-2 text-dark" aria-label="Modo oscuro">🌙</span>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="darkModeSwitch" aria-label="Activar/desactivar modo oscuro">
                    <label class="custom-control-label" for="darkModeSwitch"></label>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script>
function cargarRecordatorios() {
    $.ajax({
        url: '/sistransporte/app/controllers/mantenimientos/recordatorios.php',
        method: 'GET',
        dataType: 'json',
        success: function(recordatorios) {
            const hoy = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
            const notificacionesHoy = recordatorios.filter(r => r.fecha_recordatorio === hoy);
            const badge = $('#maintenance-counter');
            const header = $('#maintenance-header');
            const menu = $('#maintenance-list').empty();

            if (notificacionesHoy.length) {
                badge.text(notificacionesHoy.length).show();
                header.text(`${notificacionesHoy.length} Recordatorios`);
                
                notificacionesHoy.forEach(recordatorio => {
                    let mensajeTruncado = recordatorio.mensaje;
                    if (mensajeTruncado.length > 100) {
                        mensajeTruncado = mensajeTruncado.substring(0, 50) + '...';  // Trunca y agrega "..."
                    }

                    const notificacion = $(` 
                        <a href="#" class="dropdown-item text-wrap ${recordatorio.leido ? 'notification-read' : 'notification-unread'}" 
                           data-id="${recordatorio.id}" 
                           data-mensaje="${escapeHtml(recordatorio.mensaje)}"
                           data-fecha-recordatorio="${recordatorio.fecha_recordatorio}">
                            <div class="d-flex justify-content-between">
                                <span>${mensajeTruncado}</span>  <!-- Mensaje truncado aquí -->
                                <small class="text-muted">${recordatorio.fecha_recordatorio}</small>
                            </div>
                        </a>
                    `).click(function(e) {
                        e.preventDefault();
                        mostrarDetalleNotificacion($(this).data());
                        if ($(this).data('leido') == 0) marcarComoLeido($(this).data('id'), $(this));
                    });
                    
                    menu.append(notificacion);
                });
            } else {
                badge.hide();
                header.text('0 Recordatorios');
                menu.append('<span class="dropdown-item">Sin recordatorios para hoy</span>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar recordatorios:", status, error);
            $('#maintenance-list').html('<span class="dropdown-item text-danger">Error cargando notificaciones</span>');
        }
    });
}


function mostrarDetalleNotificacion(datos) {
   Swal.fire({
    title: 'Detalle del Recordatorio',
    html: `
        <div style="font-size: 1.1rem; color: #333;">
            <p><strong>Mensaje:</strong> ${datos.mensaje}</p>
            <p><strong>Fecha:</strong> ${datos.fechaRecordatorio}</p>
        </div>
    `,
    icon: 'info',
    confirmButtonText: 'Cerrar',
    showCloseButton: true,
    customClass: {
        popup: 'swal-wide',
        title: 'swal-title',
        content: 'swal-content',
        confirmButton: 'swal-btn-confirm',
        closeButton: 'swal-btn-close'
    },
    didOpen: () => {
        Swal.getPopup().classList.add('animated', 'fadeIn');
    },
    // Estilo directo en SweetAlert
    width: '500px',  // Ancho mayor para el contenido
    padding: '20px',  // Relleno interno
    background: '#fff',  // Fondo blanco
    backdrop: true,  // Fondo oscuro detrás del popup
    allowEnterKey: true,  // Permitir cerrar con Enter
    allowOutsideClick: false  // No permitir cerrar al hacer clic fuera
});

}



function marcarComoLeido(id, elemento) {
    $.ajax({
        url: '/sistransporte/app/controllers/mantenimientos/marcar_leido.php',
        method: 'POST',
        data: { id },
        success: function() {
            elemento.removeClass('notification-unread').addClass('notification-read').data('leido', 1);
            const noLeidas = $('.notification-unread').length;
            $('#maintenance-counter').text(noLeidas);
            $('#maintenance-header').text(`${$('.notification-item').length} Recordatorios (${noLeidas} nuevos)`);
        },
        error: () => Swal.fire('Error', 'No se pudo marcar como leída', 'error')
    });
}

function marcarComoLeidos() {
    const noLeidas = $('.notification-unread');
    if (!noLeidas.length) return Swal.fire('Info', 'No hay notificaciones nuevas', 'info');

    $.ajax({
        url: '/sistransporte/app/controllers/mantenimientos/marcar_leidos.php',
        method: 'POST',
        data: { ids: noLeidas.map((_, el) => $(el).data('id')).get() },
        success: function() {
            noLeidas.removeClass('notification-unread').addClass('notification-read').data('leido', 1);
            $('#maintenance-counter').text(0);
            $('#maintenance-header').text(`${$('.notification-item').length} Recordatorios (0 nuevos)`);
            Swal.fire('Éxito', 'Notificaciones marcadas como leídas', 'success');
        },
        error: () => Swal.fire('Error', 'No se pudieron marcar como leídas', 'error')
    });
}

function escapeHtml(unsafe) {
    return unsafe.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

$(document).ready(() => {
    cargarRecordatorios();
    setInterval(cargarRecordatorios, 30000);
    $('#mark-maintenance-read').click(e => (e.preventDefault(), marcarComoLeidos()));
});
</script>