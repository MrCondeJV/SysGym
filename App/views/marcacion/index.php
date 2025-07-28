<?php
// filepath: c:\xampp\htdocs\SysGym\App\views\marcacion\index.php
include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');

// Consulta campos disponibles en la tabla miembros
$campos_miembro = [
    'id_miembro',
    'tipo_documento',
    'numero_documento',
    'nombres',
    'apellidos',
    'fecha_nacimiento',
    'genero',
    'correo_electronico',
    'telefono',
    'direccion',
    'fecha_registro',
    'estado',
    'url_foto',
    'contacto_emergencia_nombre',
    'contacto_emergencia_telefono',
    'creado_por'
];

// Ejemplo: consulta para mostrar info de un miembro (aquí solo para demo, normalmente sería por huella o búsqueda)
$id_demo = 1;
$stmt = $pdo->prepare("SELECT * FROM miembros WHERE id_miembro = ?");
$stmt->execute([$id_demo]);
$miembro = $stmt->fetch(PDO::FETCH_ASSOC);

// Simulación de membresía y accesos
$membresia = [
    'fecha_inicio' => '',
    'fecha_fin' => '',
    'dias_vigentes' => '',
    'dias_total' => '',
    'estado' => ''
];
$accesos = [
    ['hora_entrada' => '', 'hora_salida' => '', 'fecha' => ''],

];
?>

<style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    .marcacion-fullpanel {
        min-height: 100vh;
        width: 100vw;
        background: #f4f8fb;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: stretch;
        padding: 0;
    }

    .marcacion-flexrow {
        display: flex;
        flex: 1 1 0;
        gap: 32px;
        padding: 32px 0 0 0;
    }

    .marcacion-col-left {
        flex: 0 0 340px;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(13, 71, 161, 0.08);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 32px 18px 24px 18px;
        min-width: 280px;
        max-width: 340px;
    }

    .marcacion-col-main {
        flex: 1 1 0;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(13, 71, 161, 0.08);
        padding: 32px 32px 24px 32px;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .foto-miembro {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #1976d2;
        background: #fff;
        margin-bottom: 18px;
    }

    .nombre-miembro {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1976d2;
        margin-bottom: 4px;
        text-align: center;
    }

    .estado-miembro {
        font-size: 1.1rem;
        font-weight: bold;
        color: #43e97b;
        margin-bottom: 10px;
        text-align: center;
    }

    .info-miembro-list {
        list-style: none;
        padding: 0;
        margin: 0 0 18px 0;
        font-size: 1.05rem;
    }

    .info-miembro-list li {
        margin-bottom: 6px;
        color: #333;
    }

    .fingerprint-img {
        width: 80px;
        height: 80px;
        margin: 18px 0 8px 0;
        border-radius: 12px;
        border: 1.5px dashed #1976d2;
        background: #f4f8fb;
        object-fit: contain;
    }

    .btn-lector {
        width: 120px;
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 8px;
        border-radius: 22px;
    }

    .btn-lector.start {
        background: #43e97b;
        color: #fff;
        border: none;
    }

    .btn-lector.stop {
        background: #ff4e50;
        color: #fff;
        border: none;
    }

    .marcacion-membresia {
        background: #f8fafc;
        border-radius: 10px;
        padding: 16px 18px;
        margin-bottom: 18px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 18px;
        font-size: 1.1rem;
    }

    .marcacion-membresia .estado {
        font-weight: bold;
        color: #388e3c;
    }

    .marcacion-membresia .vencida {
        color: #ff4e50;
    }

    .marcacion-actions {
        display: flex;
        gap: 16px;
        margin-bottom: 18px;
    }

    .btn-membresia {
        background: #43e97b;
        color: #fff;
        border-radius: 18px;
        border: none;
        padding: 10px 28px;
        font-weight: bold;
    }

    .btn-buscar {
        background: #2196f3;
        color: #fff;
        border-radius: 18px;
        border: none;
        padding: 10px 28px;
        font-weight: bold;
    }

    .table-marcacion {
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 10px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(25, 118, 210, 0.07);
    }

    .table-marcacion th {
        background: #2196f3;
        color: #fff;
        font-weight: bold;
        text-align: center;
    }

    .table-marcacion td {
        background: #f4f8fb;
        text-align: center;
    }

    @media (max-width: 1100px) {
        .marcacion-flexrow {
            flex-direction: column;
            gap: 18px;
            padding: 18px 0 0 0;
        }

        .marcacion-col-left,
        .marcacion-col-main {
            max-width: 100%;
            min-width: 0;
        }
    }

    .fingerprint-btn {
        background: linear-gradient(90deg, #1976d2, #42a5f5);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 90px;
        height: 90px;
        font-size: 2.5rem;
        margin: 0 10px;
        box-shadow: 0 2px 10px rgba(25, 118, 210, 0.15);
        transition: background 0.2s, transform 0.2s;
    }

    .fingerprint-btn:hover {
        background: linear-gradient(90deg, #42a5f5, #1976d2);
        transform: scale(1.08);
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid" ">
            <div class="marcacion-flexrow">
                <!-- Columna izquierda: Foto y lector -->
                <div class="marcacion-col-left">
                    <img src="<?= htmlspecialchars($miembro['url_foto'] ?? '/SysGym/public/images/avatar.png') ?>" alt="Foto" class="foto-miembro" id="foto-miembro">
                    <div class="nombre-miembro" id="nombre-miembro">
                        <?= htmlspecialchars(($miembro['nombres'] ?? '') . ' ' . ($miembro['apellidos'] ?? '')) ?>
                    </div>
                    <div class="estado-miembro" id="estado-miembro">
                        <?= htmlspecialchars($miembro['estado'] ?? '') ?>
                    </div>
                    <ul class="info-miembro-list">
                        <li><b>ID:</b> <span id="id-miembro"><?= htmlspecialchars($miembro['id_miembro'] ?? '-') ?></span></li>
                        <li><b>Tipo Documento:</b> <span id="tipo-documento-miembro"><?= htmlspecialchars($miembro['tipo_documento'] ?? '-') ?></span></li>
                        <li><b>Número Documento:</b> <span id="numero-documento-miembro"><?= htmlspecialchars($miembro['numero_documento'] ?? '-') ?></span></li>
                        <li><b>Correo:</b> <span id="correo-miembro"><?= htmlspecialchars($miembro['correo_electronico'] ?? '-') ?></span></li>
                        <li><b>Teléfono:</b> <span id="telefono-miembro"><?= htmlspecialchars($miembro['telefono'] ?? '-') ?></span></li>
                        <li><b>Dirección:</b> <span id="direccion-miembro"><?= htmlspecialchars($miembro['direccion'] ?? '-') ?></span></li>
                        <li><b>Fecha Nacimiento:</b> <span id="fecha-nacimiento"><?= htmlspecialchars($miembro['fecha_nacimiento'] ?? '-') ?></span></li>
                        <li><b>Género:</b> <span id="genero-miembro"><?= htmlspecialchars($miembro['genero'] ?? '-') ?></span></li>
                        <li><b>Contacto Emergencia:</b> <span id="contacto-emergencia"><?= htmlspecialchars($miembro['contacto_emergencia_nombre'] ?? '-') ?> (<?= htmlspecialchars($miembro['contacto_emergencia_telefono'] ?? '-') ?>)</span></li>
                    </ul>

                    <button class="fingerprint-btn" id="btn-huella" title="Marcar con huella">
                        <i class="fas fa-fingerprint"></i>
                    </button>
                    <br>

                </div>
                <!-- Columna principal: Info y acciones -->
                <div class="marcacion-col-main">
                    <div class="marcacion-membresia">
                        <span><b>Fecha de Inicio:</b> <span id="fecha-inicio"><?= htmlspecialchars($membresia['fecha_inicio']) ?></span></span>
                        <span><b>Fecha de Vencimiento:</b> <span id="fecha-fin"><?= htmlspecialchars($membresia['fecha_fin']) ?></span></span>
                        <span><b>Días Vigentes:</b> <span id="dias-vigentes"><?= htmlspecialchars($membresia['dias_vigentes']) ?></span></span>
                        <span><b>Días Totales:</b> <span id="dias"><?= htmlspecialchars($membresia['dias_total']) ?></span></span>
                        <span class="estado <?= $membresia['estado'] == 'Vencida' ? 'vencida' : '' ?>">
                            <?= htmlspecialchars($membresia['estado']) ?>
                        </span>
                    </div>
                    <div class="marcacion-actions">
                        <button class="btn btn-membresia" id="btn-pagar">
        <i class="fas fa-money-bill-wave"></i> Renovar Membresía
    </button>
    <button class="btn btn-buscar" id="btn-ver-historial">
        <i class="fas fa-history"></i> Ver Historial de Ingresos
    </button>
                    </div>
                    <div>
                        <h5 class="mb-2" style="color:#1976d2;"><i class="fas fa-door-open"></i> Últimos Ingresos</h5>
                        <table class="table table-bordered table-marcacion">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora Entrada</th>
                                    <th>Hora Salida</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-ingresos">
                                <?php if (!empty($accesos)): foreach ($accesos as $acc): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($acc['fecha']) ?></td>
                                            <td><?= htmlspecialchars($acc['hora_entrada']) ?></td>
                                            <td><?= htmlspecialchars($acc['hora_salida']) ?></td>
                                        </tr>
                                    <?php endforeach;
                                else: ?>
                                    <tr>
                                        <td colspan="3" style="background:#e3eaf4;">Sin registros</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="marcacion-resultado" class="mt-3"></div>
                </div>
            </div>
            <div style="margin-top:18px; width:100%;">
    <input type="text" id="manual-numero-documento" class="form-control" placeholder="Número de documento" style="margin-bottom:8px;">
    <button class="btn btn-buscar" id="btn-manual-ingreso" style="width:100%;">Ingreso Manual</button>
</div>
        </div>
    </section>
</div>

<!-- Modal de membresía vencida -->
<div id="modalMembresiaVencida" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
  <div style="background:#fff; padding:32px 24px; border-radius:12px; max-width:350px; margin:auto; text-align:center;">
    <h4 style="color:#d32f2f;"><i class="fas fa-exclamation-triangle"></i> Membresía vencida</h4>
    <p>El usuario debe renovar la membresía para poder ingresar.</p>
    <button onclick="cerrarModalMembresiaVencida()" style="margin-top:18px; background:#1976d2; color:#fff; border:none; border-radius:8px; padding:8px 24px;">Cerrar</button>
  </div>
</div>

<script>
    // Configuración global
    const HUELLA_API_URL = 'http://localhost:5000/verificar_huella';
    const RESULTADO_ELEMENTO = document.getElementById('marcacion-resultado');

    // Función para mostrar mensajes de estado
    function mostrarEstado(mensaje, tipo = 'info', icono = 'fa-spinner fa-spin') {
        const tipos = {
            info: 'text-info',
            success: 'text-success',
            danger: 'text-danger',
            warning: 'text-warning'
        };
        RESULTADO_ELEMENTO.innerHTML = `
        <span class="${tipos[tipo]}">
            <i class="fas ${icono}"></i> ${mensaje}
        </span>
    `;
    }

    // Función para manejar errores
    function manejarError(error) {
        console.error('Error:', error);
        mostrarEstado(error.message || 'Error desconocido', 'danger', 'fa-exclamation-triangle');

    }

    // Función principal para verificar huella
    async function verificarHuella() {
        try {
            mostrarEstado('Iniciando lectura y verificación de huella...', 'info');
            // Llama directamente al endpoint de C#
            const response = await fetch(HUELLA_API_URL);
            if (!response.ok) {
                throw new Error(`Error en el servidor: ${response.status}`);
            }
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Error al verificar la huella');
            }

            if (data.success && data.id_miembro) {
                mostrarEstado(
                    `¡Huella verificada! Acceso permitido.<br>
                Usuario ID: ${escape(data.id_miembro)}`,
                    'success',
                    'fa-check-circle'
                );

                // --- NUEVO BLOQUE: Consultar info del usuario y mostrarla ---
                fetch('https://gimnasio.esfim.edu.co/SysGym/App/controllers/miembros/get_miembro.php?id=' + encodeURIComponent(data.id_miembro))
                    .then(resp => resp.json())
                    .then(usuario => {
                        if (usuario.success && usuario.miembro) {
                            // ...actualizas los datos del usuario...
                            document.getElementById('foto-miembro').src = usuario.miembro.url_foto || '/sysgym/public/images/avatar_default.png';
                            document.getElementById('nombre-miembro').textContent = usuario.miembro.nombres + ' ' + usuario.miembro.apellidos;
                            document.getElementById('estado-miembro').textContent = usuario.miembro.estado || '';
                            document.getElementById('id-miembro').textContent = usuario.miembro.id_miembro;
                            document.getElementById('tipo-documento-miembro').textContent = usuario.miembro.tipo_documento || '-';
                            document.getElementById('numero-documento-miembro').textContent = usuario.miembro.numero_documento || '-';
                            document.getElementById('correo-miembro').textContent = usuario.miembro.correo_electronico || '';
                            document.getElementById('telefono-miembro').textContent = usuario.miembro.telefono || '';
                            document.getElementById('direccion-miembro').textContent = usuario.miembro.direccion || '';
                            document.getElementById('fecha-nacimiento').textContent = usuario.miembro.fecha_nacimiento || '';
                            document.getElementById('genero-miembro').textContent = usuario.miembro.genero || '';
                            document.getElementById('contacto-emergencia').textContent =
                                (usuario.miembro.contacto_emergencia_nombre || '-') +
                                ' (' + (usuario.miembro.contacto_emergencia_telefono || '-') + ')';

                            // Mostrar solo los datos de membresía solicitados
                            document.getElementById('fecha-inicio').textContent = usuario.miembro.fecha_inicio || '-';
                            document.getElementById('fecha-fin').textContent = usuario.miembro.fecha_fin || '-';
                            document.getElementById('dias-vigentes').textContent = usuario.miembro.dias_vigentes || '-';
                            document.getElementById('dias').textContent = usuario.miembro.dias_total || '-';

                            // Validar días vigentes antes de registrar acceso
                            const diasVigentes = parseInt(usuario.miembro.dias_vigentes, 10) || 0;
                            if (diasVigentes <= 0) {
                                mostrarModalMembresiaVencida();
                                return; // No registrar acceso
                            }

                            // Actualizar historial de accesos
                            actualizarHistorialAccesos(data.id_miembro);

                            // Registrar acceso
                            fetch('https://gimnasio.esfim.edu.co/SysGym/App/controllers/accesos/registrar_acceso.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'id_miembro=' + encodeURIComponent(data.id_miembro) + '&metodo_acceso=huella'
                            })
                            .then(resp => resp.json())
                            .then(res => {
                                if (res.success) {
                                    mostrarEstado(res.message, 'success', 'fa-door-open');
                                } else {
                                    mostrarEstado(res.message, 'danger', 'fa-exclamation-triangle');
                                }
                            });
                        }
                    });
                // --- FIN NUEVO BLOQUE ---

            } else {
                mostrarEstado(
                    'Huella no registrada o no coincide. Acceso denegado.',
                    'danger',
                    'fa-times-circle'
                );
            }
        } catch (error) {
            manejarError(error);
        }
    }

    // Función para actualizar el historial de accesos
    function actualizarHistorialAccesos(idMiembro) {
        fetch('https://gimnasio.esfim.edu.co/SysGym/App/controllers/accesos/get_historial.php?id_miembro=' + encodeURIComponent(idMiembro))
            .then(resp => resp.json())
            .then(data => {
                const tbody = document.getElementById('tabla-ingresos');
                tbody.innerHTML = '';
                if (data.success && data.accesos.length > 0) {
                    data.accesos.forEach(acc => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${acc.fecha || '-'}</td>
                        <td>${acc.fecha_entrada ? acc.fecha_entrada.substring(11, 19) : '-'}</td>
                        <td>${acc.fecha_salida ? acc.fecha_salida.substring(11, 19) : '-'}</td>
                    `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="3" style="background:#e3eaf4;">Sin registros</td></tr>`;
                }
            });
    }

    // Event Listeners
    document.getElementById('btn-huella').addEventListener('click', verificarHuella);

    document.getElementById('btn-pagar').addEventListener('click', () => {
        window.location.href = '../membresias/create.php';
    });
    document.getElementById('btn-ver-historial').addEventListener('click', () => {
        window.location.href = '../marcacion/history.php';
    });
   
    document.getElementById('btn-manual-ingreso').addEventListener('click', async () => {
    const numeroDocumento = document.getElementById('manual-numero-documento').value.trim();
    if (!numeroDocumento) {
        mostrarEstado('Ingrese un número de documento.', 'warning', 'fa-exclamation-triangle');
        return;
    }

    // Buscar miembro por número de documento
    const resp = await fetch('https://gimnasio.esfim.edu.co/SysGym/App/controllers/miembros/get_miembro_doc.php?numero_documento=' + encodeURIComponent(numeroDocumento));
    const usuario = await resp.json();

    if (!usuario.success || !usuario.miembro) {
        mostrarEstado('No se encontró un miembro con ese documento.', 'danger', 'fa-times-circle');
        return;
    }

    // Verificar si hay advertencias
    if (usuario.miembro.advertencias && usuario.miembro.advertencias.length > 0) {
        const advertenciasTexto = usuario.miembro.advertencias.join(', ');
        const confirmar = confirm(`ADVERTENCIA: ${advertenciasTexto}.\n\n¿Desea continuar con el ingreso manual?`);
        if (!confirmar) {
            mostrarEstado('Ingreso cancelado por el usuario.', 'warning', 'fa-exclamation-triangle');
            return;
        }
    }

    // Mostrar datos del usuario
    document.getElementById('foto-miembro').src = usuario.miembro.url_foto || '/SysGym/public/images/avatar_default.png';
    document.getElementById('nombre-miembro').textContent = usuario.miembro.nombres + ' ' + usuario.miembro.apellidos;
    document.getElementById('estado-miembro').textContent = usuario.miembro.estado || '';
    document.getElementById('id-miembro').textContent = usuario.miembro.id_miembro;
    document.getElementById('tipo-documento-miembro').textContent = usuario.miembro.tipo_documento || '-';
    document.getElementById('numero-documento-miembro').textContent = usuario.miembro.numero_documento || '-';
    document.getElementById('correo-miembro').textContent = usuario.miembro.correo_electronico || '';
    document.getElementById('telefono-miembro').textContent = usuario.miembro.telefono || '';
    document.getElementById('direccion-miembro').textContent = usuario.miembro.direccion || '';
    document.getElementById('fecha-nacimiento').textContent = usuario.miembro.fecha_nacimiento || '';
    document.getElementById('genero-miembro').textContent = usuario.miembro.genero || '';
    document.getElementById('contacto-emergencia').textContent =
        (usuario.miembro.contacto_emergencia_nombre || '-') +
        ' (' + (usuario.miembro.contacto_emergencia_telefono || '-') + ')';

    // Mostrar datos de membresía si existen
   
        document.getElementById('fecha-inicio').textContent = usuario.miembro.fecha_inicio || '-';
        document.getElementById('fecha-fin').textContent = usuario.miembro.fecha_fin || '-';
        document.getElementById('dias-vigentes').textContent = usuario.miembro.dias_vigentes || '-';
        document.getElementById('dias').textContent = usuario.miembro.dias_total || '-';
    

    // Los días vigentes ahora se manejan como advertencias, no como bloqueo

    // Actualizar Historial de accesos
    actualizarHistorialAccesos(usuario.miembro.id_miembro);

    // Registrar acceso manual
    fetch('https://gimnasio.esfim.edu.co/SysGym/App/controllers/accesos/registrar_acceso.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_miembro=' + encodeURIComponent(usuario.miembro.id_miembro) + '&metodo_acceso=manual'
    })
    .then(resp => resp.json())
    .then(res => {
        if (res.success) {
            mostrarEstado(res.message, 'success', 'fa-door-open');
        } else {
            mostrarEstado(res.message, 'danger', 'fa-exclamation-triangle');
        }
    });
});

    // Función para cerrar el modal de membresía vencida
    function cerrarModalMembresiaVencida() {
        document.getElementById('modalMembresiaVencida').style.display = 'none';
    }

    function mostrarModalMembresiaVencida() {
        document.getElementById('modalMembresiaVencida').style.display = 'flex';
    }
</script>

<?php include('../layout/parte2.php'); ?>