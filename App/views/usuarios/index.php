<?php
include('../../config.php');
include('../layout/parte1.php');
//include('../layout/sesion.php');

require_once __DIR__ . '/../../controllers/usuariosController.php';
// Listar solo activos para la tabla principal
$usuariosController = new UsuariosController();
$usuarios_datos = $usuariosController->listar(true);
// Listar todos y filtrar inactivos para el modal
$usuarios_inactivos = array_filter($usuariosController->listar(false), function($u) { return isset($u['activo']) && $u['activo'] == 0; });
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg"
                        style="background: linear-gradient(90deg,rgb(21, 41, 124),rgb(21, 41, 124)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-user-circle fa-lg"></i> Listado de usuarios
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Botones superiores -->
    <div class="mb-4 d-flex justify-content-end pr-3 gap-2">
        <a href="create.php" class="btn btn-success btn-lg shadow-lg px-4 py-2 agregar-usuario-btn">
            <i class="fas fa-user-plus fa-lg"></i>
            <span>Agregar Usuario</span>
        </a>
        <button type="button" class="btn btn-secondary btn-lg shadow-lg px-4 py-2 ml-2" data-toggle="modal" data-target="#modalInactivos">
            <i class="fas fa-user-slash fa-lg"></i> <span>Ver inactivos</span>
        </button>
    </div>
<!-- Modal Usuarios Inactivos -->
<div class="modal fade" id="modalInactivos" tabindex="-1" role="dialog" aria-labelledby="modalInactivosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="modalInactivosLabel"><i class="fas fa-user-slash"></i> Usuarios Inactivos</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm text-center" id="tablaInactivos">
                        <thead>
                            <tr>
                                <th>Nombres</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Correo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios_inactivos as $u): ?>
                                <tr id="row-inactivo-<?php echo $u['id']; ?>">
                                    <td><?php echo htmlspecialchars($u['nombre_completo']); ?></td>
                                    <td><?php echo htmlspecialchars($u['usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($u['rol']); ?></td>
                                    <td><?php echo htmlspecialchars($u['correo']); ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-reactivar" data-id="<?php echo $u['id']; ?>" data-row-id="row-inactivo-<?php echo $u['id']; ?>">
                                            <i class="fas fa-user-check"></i> Reactivar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
    <style>
    .agregar-usuario-btn {
        font-size: 1.25rem;
        border-radius: 0.5rem 0.5rem 0.5rem 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        box-shadow: 0 4px 18px 0 rgba(11,191,251,0.12);
        transition: background 0.2s, box-shadow 0.2s;
    }
    .agregar-usuario-btn:hover {
        background: linear-gradient(90deg, #0bbffb 60%, #0e94a0 100%);
        color: #fff;
        box-shadow: 0 6px 24px 0 rgba(11,191,251,0.18);
    }
    </style>

    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 mx-auto">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Usuarios Registrados</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-sm text-center"
                                    style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="align-middle" style="width: 28%;">Nombres</th>
                                            <th class="align-middle" style="width: 18%;">Apellidos</th>
                                            <th class="align-middle" style="width: 20%;">Usuario</th>
                                            <th class="align-middle" style="width: 14%;">Rol</th>
                                            <th class="align-middle" style="width: 10%;">Estado</th>
                                            <th class="align-middle" style="width: 10%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($usuarios_datos as $usuarios_dato) {
                                            $id_usuario = $usuarios_dato['id'];
                                            $nombre_completo = $usuarios_dato['nombre_completo'];
                                            $usuario = $usuarios_dato['usuario'];
                                            $rol = $usuarios_dato['rol'];
                                            $estado = isset($usuarios_dato['activo']) ? $usuarios_dato['activo'] : 1;
                                            $badge = ($estado == 1) ? 'success' : 'secondary';
                                            $estado_texto = ($estado == 1) ? 'Activo' : 'Inactivo';
                                            $correo = $usuarios_dato['correo'];
                                            $dependencia = isset($usuarios_dato['dependencia']) ? $usuarios_dato['dependencia'] : '';
                                            $telefono = $usuarios_dato['telefono'];
                                        ?>
                                            <tr id="row-<?php echo $id_usuario; ?>">
                                                <td class="align-middle text-truncate" style="max-width: 180px;">
                                                    <?php echo htmlspecialchars($nombre_completo); ?>
                                                </td>
                                                <td class="align-middle text-truncate" style="max-width: 120px;">
                                                    <?php echo htmlspecialchars($correo); ?>
                                                </td>
                                                <td class="align-middle text-truncate" style="max-width: 120px;">
                                                    <?php echo htmlspecialchars($usuario); ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?php echo htmlspecialchars($rol); ?>
                                                </td>
                                                <td class="align-middle">
                                                    <span class="badge badge-<?php echo $badge; ?>"><?php echo $estado_texto; ?></span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="btn-group">
                                                        <a href="show.php?id=<?php echo $id_usuario; ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="update.php?id=<?php echo $id_usuario; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-danger btn-sm btn-eliminar"
                                                            data-id="<?php echo $id_usuario; ?>"
                                                            data-row-id="row-<?php echo $id_usuario; ?>">
                                                            <i class="fas fa-user-slash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
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

<!-- /.content-wrapper -->


</script>
<script src="datatable.js"></script>
<script src="desactivar.js"></script>
<script>
// Desactivar usuario (mueve la fila al modal de inactivos)
document.querySelectorAll('.btn-eliminar').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const rowId = this.dataset.rowId;
        Swal.fire({
            title: '¿Desactivar usuario?',
            text: "El usuario no podrá acceder hasta ser reactivado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                fetch('../../controllers/usuarios/desactivar_user.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mover la fila al modal de inactivos
                        const row = document.getElementById(rowId);
                        if (row) {
                            // Obtener datos de la fila
                            const tds = row.querySelectorAll('td');
                            const nombre = tds[0].innerText;
                            const correo = tds[1].innerText;
                            const usuario = tds[2].innerText;
                            const rol = tds[3].innerText;
                            // Crear nueva fila para el modal
                            const newRow = document.createElement('tr');
                            newRow.id = 'row-inactivo-' + id;
                            newRow.innerHTML = `
                                <td>${nombre}</td>
                                <td>${usuario}</td>
                                <td>${rol}</td>
                                <td>${correo}</td>
                                <td><button class="btn btn-success btn-sm btn-reactivar" data-id="${id}" data-row-id="row-inactivo-${id}"><i class="fas fa-user-check"></i> Reactivar</button></td>
                            `;
                            document.querySelector('#tablaInactivos tbody').appendChild(newRow);
                            row.remove();
                            Swal.fire('Desactivado!', data.message, 'success');
                            // Agregar evento al nuevo botón de reactivar
                            newRow.querySelector('.btn-reactivar').addEventListener('click', reactivarHandler);
                        }
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error!', 'Ocurrió un error inesperado.', 'error');
                });
            }
        });
    });
});

// Reactivar usuario (mueve la fila a la tabla principal)
function reactivarHandler() {
    const id = this.dataset.id;
    const rowId = this.dataset.rowId;
    Swal.fire({
        title: '¿Reactivar usuario?',
        text: 'El usuario podrá volver a acceder al sistema.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, reactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            fetch('../../controllers/usuarios/reactivar_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mover la fila a la tabla principal
                    const row = document.getElementById(rowId);
                    if (row) {
                        const tds = row.querySelectorAll('td');
                        const nombre = tds[0].innerText;
                        const usuario = tds[1].innerText;
                        const rol = tds[2].innerText;
                        const correo = tds[3].innerText;
                        // Crear nueva fila para la tabla principal
                        const newRow = document.createElement('tr');
                        newRow.id = 'row-' + id;
                        newRow.innerHTML = `
                            <td class="align-middle text-truncate" style="max-width: 180px;">${nombre}</td>
                            <td class="align-middle text-truncate" style="max-width: 120px;">${correo}</td>
                            <td class="align-middle text-truncate" style="max-width: 120px;">${usuario}</td>
                            <td class="align-middle">${rol}</td>
                            <td class="align-middle"><span class="badge badge-success">Activo</span></td>
                            <td class="align-middle">
                                <div class="btn-group">
                                    <a href="show.php?id=${id}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="update.php?id=${id}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-danger btn-sm btn-eliminar" data-id="${id}" data-row-id="row-${id}"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        `;
                        document.querySelector('#example1 tbody').appendChild(newRow);
                        row.remove();
                        Swal.fire('Reactivado!', data.message, 'success');
                        // Agregar evento al nuevo botón de desactivar
                        newRow.querySelector('.btn-eliminar').addEventListener('click', desactivarHandler);
                    }
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error!', 'Ocurrió un error inesperado.', 'error');
            });
        }
    });
}

// Delegar eventos para los nuevos botones
function desactivarHandler() {
    const id = this.dataset.id;
    const rowId = this.dataset.rowId;
    Swal.fire({
        title: '¿Desactivar usuario?',
        text: "El usuario no podrá acceder hasta ser reactivado.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            fetch('../../controllers/usuarios/desactivar_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mover la fila al modal de inactivos
                    const row = document.getElementById(rowId);
                    if (row) {
                        const tds = row.querySelectorAll('td');
                        const nombre = tds[0].innerText;
                        const correo = tds[1].innerText;
                        const usuario = tds[2].innerText;
                        const rol = tds[3].innerText;
                        const newRow = document.createElement('tr');
                        newRow.id = 'row-inactivo-' + id;
                        newRow.innerHTML = `
                            <td>${nombre}</td>
                            <td>${usuario}</td>
                            <td>${rol}</td>
                            <td>${correo}</td>
                            <td><button class="btn btn-success btn-sm btn-reactivar" data-id="${id}" data-row-id="row-inactivo-${id}"><i class="fas fa-user-check"></i> Reactivar</button></td>
                        `;
                        document.querySelector('#tablaInactivos tbody').appendChild(newRow);
                        row.remove();
                        Swal.fire('Desactivado!', data.message, 'success');
                        newRow.querySelector('.btn-reactivar').addEventListener('click', reactivarHandler);
                    }
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error!', 'Ocurrió un error inesperado.', 'error');
            });
        }
    });
}

// Inicializar eventos al cargar
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-reactivar').forEach(btn => btn.addEventListener('click', reactivarHandler));
    document.querySelectorAll('.btn-eliminar').forEach(btn => btn.addEventListener('click', desactivarHandler));
});
</script>
</script>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>