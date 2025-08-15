// solicitudes.js
$(document).ready(function() {
    $('.btn-aceptar-solicitud').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Aceptar solicitud?',
            text: '¿Estás seguro de aprobar esta solicitud de aula?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                        url: '/Dashboard_2.0/App/controllers/solicitudes/acciones_solicitud.php',
                        type: 'POST',
                        data: {
                            accion: 'aprobar',
                            id: id
                        },
                        success: function(data) {
                            let res = {};
                            try { res = typeof data === 'object' ? data : JSON.parse(data); } catch(e) {}
                            if (res.success) {
                                Swal.fire('¡Aprobada!', res.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error', res.message || 'No se pudo aprobar la solicitud.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error de red', 'No se pudo contactar al servidor.\n' + error, 'error');
                        }
                    });
            }
        });
    });

    $('.btn-rechazar-solicitud').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Rechazar solicitud?',
            text: '¿Estás seguro de rechazar esta solicitud de aula?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                        url: '/Dashboard_2.0/App/controllers/solicitudes/acciones_solicitud.php',
                        type: 'POST',
                        data: {
                            accion: 'rechazar',
                            id: id
                        },
                        success: function(data) {
                            let res = {};
                            try { res = typeof data === 'object' ? data : JSON.parse(data); } catch(e) {}
                            if (res.success) {
                                Swal.fire('¡Rechazada!', res.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error', res.message || 'No se pudo rechazar la solicitud.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error de red', 'No se pudo contactar al servidor.\n' + error, 'error');
                        }
                    });
            }
        });
    });
});
