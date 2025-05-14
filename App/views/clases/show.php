<?php

include('../../config.php');
//include('../layout/sesion.php'); // Asegúrate de incluir esto si usas sesiones para autenticación
include('../layout/parte1.php');
// Incluir el controlador que obtiene los datos de una clase específica (ahora con JOINs)
include('../../controllers/clases/show_clase.php'); // Asumo que este script carga los datos, incluyendo los nombres
// Los siguientes includes no son estrictamente necesarios para mostrar solo el nombre de la clase actual,
// pero si ya los tienes, no hay problema.
include('../../controllers/categorias_clases/show_categoria.php'); // Asumo que carga $categorias_clases_datos
include('../../controllers/salas/show_sala.php'); // Asumo que carga $salas_datos
 include('../../controllers/entrenadores/show_entrenador.php'); // Asumo que carga $entrenadores_datos
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(19, 108, 182),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-dumbbell fa-lg"></i> Información de la Clase
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Consulta detallada de los datos registrados
                        </p>
                        <div class="mt-3">
                            <span class="badge badge-light text-dark p-2" style="font-size: 1rem; border-radius: 20px;">
                                <i class="fas fa-calendar-alt"></i> Fecha de Consulta: <?= date('d/m/Y'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="card card-primary">
                        <div class="card-header text-center">
                            <h3 class="card-title" style="font-family: 'Arial', sans-serif; font-size: 1.5rem;">
                                <i class="fas fa-info-circle"></i> Detalles de la Clase
                            </h3>
                        </div>

                        <div class="card-body" style="font-family: 'Arial', sans-serif; font-size: 1rem;">
                            <div class="row">
                                <!-- ID Clase -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-id-badge"></i> ID Clase:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($id_clase ?? ''); ?></p>
                                </div>
                                <!-- Nombre -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-tag"></i> Nombre:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($nombre ?? ''); ?></p>
                                </div>
                                <!-- Entrenador (Ahora muestra el nombre) -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-user"></i> Entrenador:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($entrenador_datos['nombres'] ?? 'Desconocido'); ?></p>
                                </div>
                                <!-- Horario -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-clock"></i> Horario:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($horario ?? ''); ?></p>
                                </div>
                                <!-- Día de la Semana -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-calendar-day"></i> Día de la Semana:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($dia_semana ?? ''); ?></p>
                                </div>
                                <!-- Duración Minutos -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-hourglass-half"></i> Duración (minutos):</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($duracion_minutos ?? ''); ?></p>
                                </div>
                                <!-- Capacidad Máxima -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-users"></i> Capacidad Máxima:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($capacidad_maxima ?? ''); ?></p>
                                </div>
                                <!-- Sala (Ahora muestra el nombre) -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-door-open"></i> Sala:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($sala_datos['nombre'] ?? 'Desconocida'); ?></p>
                                </div>
                                <!-- Precio -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-dollar-sign"></i> Precio:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($precio ?? ''); ?></p>
                                </div>
                                <!-- Nivel -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-chart-line"></i> Nivel:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($nivel ?? ''); ?></p>
                                </div>
                                <!-- Categoría Clase (Ahora muestra el nombre) -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-tags"></i> Categoría Clase:</strong>
                                    <p class="text-muted"><?php echo htmlspecialchars($categoria_datos['nombre'] ?? 'Desconocida'); ?></p>
                                </div>
                                <!-- Cancelada -->
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <strong><i class="fas fa-ban"></i> Cancelada:</strong>
                                    <p class="text-muted">
                                        <?php
                                        // Mostrar "Sí" o "No" en lugar de 1 o 0
                                        echo htmlspecialchars(($cancelada ?? '0') == '1' ? 'Sí' : 'No');
                                        ?>
                                    </p>
                                </div>
                                <!-- Descripción (ocupa más espacio) -->
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <strong><i class="fas fa-align-left"></i> Descripción:</strong>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($descripcion ?? '')); ?></p>
                                    <!-- nl2br() para mostrar saltos de línea si los hay en la descripción -->
                                </div>
                                <!-- Requisitos (ocupa más espacio) -->
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <strong><i class="fas fa-clipboard-list"></i> Requisitos:</strong>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($requisitos ?? 'Sin requisitos especificados')); ?></p>
                                     <!-- nl2br() para mostrar saltos de línea si los hay en los requisitos -->
                                </div>
                            </div>
                            <hr>

                            <div class="text-right mt-3">
                                <!-- Enlace para regresar al listado de clases -->
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Regresar
                                </a>
                            </div>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
