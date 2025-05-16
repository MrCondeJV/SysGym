<?php

include('../../config.php');
include('../layout/parte1.php');

// Incluir controlador para obtener los datos de la clase a actualizar
// Asumiendo que hay un controlador para obtener los detalles de una clase por su ID
include('../../controllers/clases/show_clase.php');
// Incluir controladores para obtener listas de datos para los desplegables (Entrenadores, Salas, Categorías)
// Similar a como se hizo en la vista de creación de clases
include('../../controllers/entrenadores/list_entrenadores.php');
include('../../controllers/salas/list_salas.php');
include('../../controllers/categorias_clases/list_categorias_clases.php');
include('../layout/sesion.php'); // Si necesitas control de sesión, descomenta esta línea

// Asumiendo que el controlador show.php carga los datos de la clase en una variable $clase
// y el ID de la clase en $id_clase (obtenido de la URL o POST)

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(5, 99, 32),rgb(51, 240, 114)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-dumbbell fa-lg"></i> Actualizar Clase
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Actualizar Información de la Clase
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Datos de la Clase</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- El formulario ahora apunta al controlador de actualización de clases -->
                            <form action="../../controllers/clases/update.php" method="post">
                                <!-- Campo oculto para el ID de la clase -->
                                <input type="hidden" name="id_clase" value="<?= $id_clase; ?>">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre de la Clase</label>
                                        <!-- Pre-llenar con el nombre actual de la clase -->
                                        <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre de la clase..." value="<?= htmlspecialchars($clase_datos['nombre'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="id_entrenador">Entrenador</label>
                                        <select name="id_entrenador" class="form-control" required>
                                            <option value="">Seleccione un entrenador</option>
                                            <?php foreach ($entrenadores_datos as $entrenador_dato) : ?>
                                                <option value="<?= $entrenador_dato['id_entrenador']; ?>"
                                                    <?= (($clase_datos['id_entrenador'] ?? '') == $entrenador_dato['id_entrenador']) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($entrenador_dato['nombres'] . ' ' . $entrenador_dato['apellidos']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <!-- Pre-llenar con la descripción actual -->
                                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Escriba la descripción de la clase..." required><?= htmlspecialchars($clase_datos['descripcion'] ?? ''); ?></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="horario">Horario</label>
                                        <!-- Pre-llenar con el horario actual -->
                                        <input type="text" name="horario" class="form-control" placeholder="Ej: 10:00 AM - 11:00 AM" value="<?= htmlspecialchars($clase_datos['horario'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="dia_semana">Día de la Semana</label>
                                        <select name="dia_semana" class="form-control" required>
                                            <option value="">Seleccione un día</option>
                                            <?php
                                            $dia_semanas = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                            foreach ($dia_semanas as $dia) : ?>
                                                <option value="<?= htmlspecialchars($dia); ?>"
                                                    <?= (($clase_datos['dia_semana'] ?? '') === $dia) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($dia); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="duracion">Duracion (minutos)</label>
                                        <!-- Pre-llenar con la duración actual -->
                                        <input type="number" name="duracion_minutos" class="form-control" placeholder="Ej: 60" value="<?= htmlspecialchars($clase_datos['duracion_minutos'] ?? ''); ?>" required min="1">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="capacidad_maxima">Capacidad Máxima</label>
                                        <!-- Pre-llenar con la capacidad actual -->
                                        <input type="number" name="capacidad_maxima" class="form-control" placeholder="Ej: 20" value="<?= htmlspecialchars($clase_datos['capacidad_maxima'] ?? ''); ?>" required min="1">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="id_sala">Sala</label>
                                        <select name="id_sala" class="form-control" required>
                                            <option value="">Seleccione una sala</option>
                                            <?php foreach ($salas_datos as $sala_dato) : ?>
                                                <option value="<?= $sala_dato['id_sala']; ?>"
                                                    <?= (($clase_datos['id_sala'] ?? '') == $sala_dato['id_sala']) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($sala_dato['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="precio">Precio</label>
                                        <!-- Pre-llenar con el precio actual -->
                                        <input type="number" name="precio" class="form-control" placeholder="Ej: 15.00" step="0.01" value="<?= htmlspecialchars($clase_datos['precio'] ?? ''); ?>" required min="0">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nivel">Nivel</label>
                                        <!-- Pre-llenar con el nivel actual -->
                                        <input type="text" name="nivel" class="form-control" placeholder="Ej: Principiante, Intermedio, Avanzado" value="<?= htmlspecialchars($clase_datos['nivel'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="id_categoria_clase">Categoría</label>
                                        <select name="id_categoria_clase" class="form-control" required>
                                            <option value="">Seleccione una categoría</option>
                                            <?php foreach ($categorias_clases_datos as $categoria_dato) : ?>
                                                <option value="<?= $categoria_dato['id_categoria']; ?>"
                                                    <?= (($clase_datos['id_categoria_clase'] ?? '') == $categoria_dato['id_categoria']) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($categoria_dato['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="cancelada">Clase Cancelada</label>
                                        <select name="cancelada" class="form-control" required>
                                            <option value="0" <?= (($clase_datos['cancelada'] ?? '0') == '0') ? 'selected' : ''; ?>>No</option>
                                            <option value="1" <?= (($clase_datos['cancelada'] ?? '0') == '1') ? 'selected' : ''; ?>>Sí</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="requisitos">Requisitos (Opcional)</label>
                                        <!-- Pre-llenar con los requisitos actuales -->
                                        <input type="text" name="requisitos" class="form-control" placeholder="Ej: Traer toalla" value="<?= htmlspecialchars($clase_datos['requisitos'] ?? ''); ?>">
                                    </div>
                                </div>


                                <hr>
                                <div class="form-group text-center">
                                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Actualizar Clase</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>