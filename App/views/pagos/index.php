<?php
include('../../config.php');
include('../layout/parte1.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario_actual = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario_actual) {
    die("No hay usuario autenticado.");
}

// Obtener id de rol del usuario
$stmtRol = $pdo->prepare("SELECT rol FROM usuariossistema WHERE id_usuario = :id_usuario");
$stmtRol->execute([':id_usuario' => $id_usuario_actual]);
$usuario = $stmtRol->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

$id_rol = $usuario['rol'];

// Obtener nombre del rol real
$stmtNombreRol = $pdo->prepare("SELECT nombre FROM roles WHERE id = :id_rol");
$stmtNombreRol->execute([':id_rol' => $id_rol]);
$rol_info = $stmtNombreRol->fetch(PDO::FETCH_ASSOC);

if (!$rol_info) {
    die("Rol no encontrado.");
}

$nombre_rol = $rol_info['nombre'];

// Ajustar lógica según rol real
if ($nombre_rol === 'Administrador') {
    // Usuario con rol Administrador ve todo
    $sql = "
        SELECT 
            me.id_miembro, 
            me.nombres, 
            me.apellidos, 
            me.numero_documento,
            COUNT(r.id_renovacion) AS total_renovaciones,
            CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario_renovo
        FROM miembros me
        JOIN renovaciones r ON me.id_miembro = r.id_miembro
        LEFT JOIN (
            SELECT r1.id_miembro, r1.renovado_por
            FROM renovaciones r1
            WHERE r1.fecha = (
                SELECT MAX(r2.fecha) 
                FROM renovaciones r2 
                WHERE r2.id_miembro = r1.id_miembro
            )
        ) ult ON ult.id_miembro = me.id_miembro
        LEFT JOIN usuariossistema u ON ult.renovado_por = u.id_usuario
        GROUP BY me.id_miembro, me.nombres, me.apellidos, me.numero_documento, nombre_usuario_renovo
        ORDER BY me.nombres
    ";
    $stmt = $pdo->query($sql);
} else {
    // Otros roles solo ven renovaciones hechas por ellos mismos
    $sql = "
        SELECT 
            me.id_miembro, 
            me.nombres, 
            me.apellidos, 
            me.numero_documento,
            COUNT(r.id_renovacion) AS total_renovaciones,
            CONCAT(u.nombres, ' ', u.apellidos) AS nombre_usuario_renovo
        FROM miembros me
        JOIN renovaciones r ON me.id_miembro = r.id_miembro AND r.renovado_por = ?
        LEFT JOIN (
            SELECT r1.id_miembro, r1.renovado_por
            FROM renovaciones r1
            WHERE r1.fecha = (
                SELECT MAX(r2.fecha) 
                FROM renovaciones r2 
                WHERE r2.id_miembro = r1.id_miembro
            )
        ) ult ON ult.id_miembro = me.id_miembro
        LEFT JOIN usuariossistema u ON ult.renovado_por = u.id_usuario
        GROUP BY me.id_miembro, me.nombres, me.apellidos, me.numero_documento, nombre_usuario_renovo
        ORDER BY me.nombres
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario_actual]);
}

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-history"></i> Historial de Pagos/Renovaciones
                    </h1>
                    <p class="text-muted mt-2">Consulta el historial de renovaciones de cada usuario.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-users"></i> Usuarios con renovaciones</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Documento</th>
                                            <th>Total Renovaciones</th>
                                            <th>Última renovación por</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($usuarios as $i => $u): ?>
                                        <tr>
                                            <td><?php echo $i + 1; ?></td>
                                            <td>
                                                <i class="fas fa-user text-info"></i>
                                                <?php echo htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($u['numero_documento']); ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">
                                                    <?php echo $u['total_renovaciones']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($u['nombre_usuario_renovo'] ?? ''); ?>
                                            </td>
                                            <td>
                                                <a href="historial_renovaciones.php?id_miembro=<?php echo $u['id_miembro']; ?>"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-list"></i> Ver historial
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="datatable.js"></script>

<?php include('../layout/parte2.php'); ?>