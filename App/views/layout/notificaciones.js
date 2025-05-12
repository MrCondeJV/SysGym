let notificacionesNoLeidasPrevias = 0;
let idUltimaNotificacion = parseInt(localStorage.getItem('idUltimaNotificacion')) || 0;

function cargarNotificaciones() {
    fetch('/sistransporte/app/controllers/notificaciones/obtener.php')
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('notification-counter');
            const header = document.getElementById('notification-header');
            const lista = document.getElementById('notification-list');
            const sonidoNotificacion = document.getElementById('notificacion-sound');

            if (!badge || !header || !lista) return;

            lista.innerHTML = '';
            let noLeidas = 0;
            let maxIdActual = idUltimaNotificacion;

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(n => {
                    if (n.leido == 0) noLeidas++;
                    if (n.id > maxIdActual) maxIdActual = n.id;

                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'dropdown-item ' + (n.leido == 1 ? 'leida' : 'no-leida');
                    item.style.whiteSpace = 'nowrap';
                    item.style.overflow = 'hidden';
                    item.style.textOverflow = 'ellipsis';
                    item.style.maxWidth = '300px';
                    item.style.display = 'inline-block';

                    item.innerHTML = `
                        <i class="fas fa-bell mr-2"></i> ${n.mensaje}
                        <span class="float-right text-muted text-sm">${timeAgo(n.fecha_creacion)}</span>
                    `;

                    item.addEventListener('click', e => {
                        e.preventDefault();

                        fetch('/sistransporte/app/controllers/notificaciones/marcar_individual.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(n.id)
                        })
                        .then(res => res.json())
                        .then(response => {
                            if (response.status === 'ok') {
                                item.classList.remove('no-leida');
                                item.classList.add('leida');
                                noLeidas--;
                                badge.innerText = noLeidas > 0 ? noLeidas : '';
                                header.innerText = `${data.length} Notificación${data.length !== 1 ? 'es' : ''}`;
                            }
                        });

                        Swal.fire({
                            title: 'Notificación',
                            html: `
                                <p><strong>Tipo:</strong> ${n.tipo}</p>
                                <p>${n.mensaje}</p>
                                <p><small>${n.fecha_creacion}</small></p>
                            `,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    });

                    lista.appendChild(item);
                    lista.appendChild(document.createElement('div')).className = 'dropdown-divider';
                });

                // 🔔 Reproducir sonido solo si hay una nueva notificación no leída con ID mayor
                const nuevasNoLeidas = data.filter(n => n.leido == 0 && n.id > idUltimaNotificacion);
                if (nuevasNoLeidas.length > 0 && sonidoNotificacion) {
                    sonidoNotificacion.play().catch(e => {
                        console.warn("🔇 No se pudo reproducir el sonido (interacción requerida por el navegador)", e);
                    });
                    const maxNuevoId = Math.max(...nuevasNoLeidas.map(n => n.id));
                    idUltimaNotificacion = maxNuevoId;
                    localStorage.setItem('idUltimaNotificacion', maxNuevoId.toString());
                }

                notificacionesNoLeidasPrevias = noLeidas;
            } else {
                const item = document.createElement('a');
                item.className = 'dropdown-item';
                item.innerHTML = `<i class="fas fa-envelope mr-2"></i> No hay notificaciones nuevas`;
                lista.appendChild(item);
            }

            badge.innerText = noLeidas > 0 ? noLeidas : '';
            header.innerText = `${data.length} Notificación${data.length !== 1 ? 'es' : ''}`;
        })
        .catch(err => {
            console.error("❌ Error al cargar notificaciones:", err);
        });
}

function timeAgo(fechaStr) {
    const fecha = new Date(fechaStr);
    const ahora = new Date();
    const diff = Math.floor((ahora - fecha) / 1000);

    if (diff < 60) return 'ahora';
    if (diff < 3600) return `${Math.floor(diff / 60)} min`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} h`;
    return fecha.toLocaleDateString();
}

document.addEventListener('DOMContentLoaded', () => {
    cargarNotificaciones();
    setInterval(cargarNotificaciones, 1000);

    const sonido = document.getElementById('notificacion-sound');
    const botonActivarSonido = document.getElementById('activar-sonido');
    const permisoSonido = localStorage.getItem('permisoSonido');

    // ✅ Si ya se dio permiso previamente
    if (permisoSonido === 'true') {
        // 🧠 Esperar cualquier interacción para desbloquear el sonido
        const desbloqueoUsuario = () => {
            desbloquearSonido(sonido);
            document.removeEventListener('click', desbloqueoUsuario); // solo una vez
        };
        document.addEventListener('click', desbloqueoUsuario, { once: true });
    } else {
        // 🧠 Mostrar alerta para pedir permiso
        Swal.fire({
            title: '¿Activar sonido de notificaciones?',
            text: 'Recibirás una alerta sonora cuando llegue una nueva notificación.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'No',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Se autoriza el sonido: requerimos clic para habilitar
                const desbloqueoUsuario = () => {
                    desbloquearSonido(sonido);
                    document.removeEventListener('click', desbloqueoUsuario);
                };
                document.addEventListener('click', desbloqueoUsuario, { once: true });
                localStorage.setItem('permisoSonido', 'true');
            } else {
                localStorage.setItem('permisoSonido', 'false');
                console.log("🔇 El usuario no activó el sonido.");
                botonActivarSonido.style.display = 'block';
            }
        });
    }

    botonActivarSonido.addEventListener('click', () => {
        desbloquearSonido(sonido);
        localStorage.setItem('permisoSonido', 'true');
        botonActivarSonido.style.display = 'none';
    });

    const marcarBtn = document.getElementById('mark-all-read');
    if (marcarBtn) {
        marcarBtn.addEventListener('click', e => {
            e.preventDefault();
            fetch('/sistransporte/app/controllers/notificaciones/marcar_leido.php')
                .then(() => {
                    fetch('/sistransporte/app/controllers/notificaciones/obtener.php')
                        .then(res => res.json())
                        .then(data => {
                            if (Array.isArray(data) && data.length > 0) {
                                const maxId = Math.max(...data.map(n => n.id));
                                localStorage.setItem('idUltimaNotificacion', maxId.toString());
                                idUltimaNotificacion = maxId;
                            }
                            cargarNotificaciones();
                            const dropdownToggle = document.querySelector('.nav-item.dropdown .nav-link');
                            if (dropdownToggle) dropdownToggle.click();
                        });
                });
        });
    }
});

function desbloquearSonido(sonido) {
    if (!sonido) return;
    sonido.volume = 0;
    sonido.play()
        .then(() => {
            sonido.pause();
            sonido.currentTime = 0;
            sonido.volume = 1;
            console.log("🔊 Sonido habilitado");
        })
        .catch(err => {
            console.warn("❌ No se pudo autorizar el sonido:", err);
        });
}
