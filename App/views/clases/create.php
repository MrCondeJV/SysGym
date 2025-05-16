<?php

include('../../config.php');
include('../layout/parte1.php');
// Asegúrate de incluir los controladores que listan los datos necesarios
include('../../controllers/categorias_clases/list_categorias_clases.php'); // Asumo que carga $categorias_clases_datos
include('../../controllers/salas/list_salas.php'); // Asumo que carga $salas_datos
include('../../controllers/entrenadores/list_entrenadores.php'); // Asumo que carga $entrenadores_datos
include('../layout/sesion.php'); // Asegúrate de incluir esto si usas sesiones para autenticación

if (isset($_SESSION['mensaje'])): ?>
    <script>
        $(document).ready(function() {
            const swalConfig = {
                icon: '<?php echo $_SESSION['icono']; ?>',
                title: '<?php echo $_SESSION['mensaje']; ?>',
                showConfirmButton: true,
                confirmButtonText: '<?php echo $_SESSION['icono'] == 'success' ? 'Continuar' : 'Reintentar'; ?>',
                confirmButtonColor: '<?php echo $_SESSION['icono'] == 'success' ? '#28a745' : '#dc3545'; ?>'
            };

            Swal.fire(swalConfig).then((result) => {
                <?php if ($_SESSION['icono'] == 'success'): ?>
                    // Redirigir solo si el icono es 'success' y el usuario confirma
                    if (result.isConfirmed) {
                         // Asumiendo que la vista de índice de clases está en App/views/clases/index.php
                        window.location.href = '<?php echo $URL; ?>App/views/clases/index.php';
                    } else {
                         // Si es success pero no confirma, o si es error, quedarse en la página de creación
                         // No hacer nada, el usuario ya está en la página correcta
                    }
                <?php else: ?>
                     // Si el icono es 'error', quedarse en la página de creación
                     // No hacer nada, el usuario ya está en la página correcta
                <?php endif; ?>
            });
        });
    </script>
<?php
    unset($_SESSION['mensaje']);
    unset($_SESSION['icono']);
    // Opcional: Si usas $_SESSION['errores'] para mostrar errores específicos por campo
    // unset($_SESSION['errores']);
endif;
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-dumbbell"></i> Registro de una <span class="font-weight-bold">Nueva Clase</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para registrar una nueva clase en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos con cuidado</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Formulario para crear una Clase -->
                            <form action="../../controllers/clases/create_clase.php" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre de la Clase</label>
                                        <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre de la clase..." required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="id_entrenador">Entrenador</label>
                                        <select name="id_entrenador" class="form-control" required>
                                            <option value="">Seleccione un entrenador</option>
                                            <?php
                                            // Asumo que $entrenadores_datos contiene los datos de los entrenadores
                                            // y que cada entrenador tiene 'id_entrenador' y 'nombre' (o similar)
                                            if (!empty($entrenadores_datos)) {
                                                foreach ($entrenadores_datos as $entrenador) {
                                                    // Ajusta 'nombre' si el nombre de la columna es diferente (ej: 'nombre_completo')
                                                    echo '<option value="' . htmlspecialchars($entrenador['id_entrenador']) . '">' . htmlspecialchars($entrenador['nombres']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Escriba una descripción de la clase..." required></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="horario">Horario</label>
                                        <input type="text" name="horario" class="form-control" placeholder="Ej: 10:00 - 11:00" required>
                                        <!-- O podrías usar type="time" si solo necesitas la hora -->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="dia_semana">Día de la Semana</label>
                                        <select name="dia_semana" class="form-control" required>
                                            <option value="">Seleccione un día</option>
                                            <option value="Lunes">Lunes</option>
                                            <option value="Martes">Martes</option>
                                            <option value="Miércoles">Miércoles</option>
                                            <option value="Jueves">Jueves</option>
                                            <option value="Viernes">Viernes</option>
                                            <option value="Sábado">Sábado</option>
                                            <option value="Domingo">Domingo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="duracion_minutos">Duración (minutos)</label>
                                        <input type="number" name="duracion_minutos" class="form-control" placeholder="Ej: 60" required min="1">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="capacidad_maxima">Capacidad Máxima</label>
                                        <input type="number" name="capacidad_maxima" class="form-control" placeholder="Ej: 20" required min="1">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="id_sala">Sala</label>
                                        <select name="id_sala" class="form-control" required>
                                            <option value="">Seleccione una sala</option>
                                            <?php
                                            // Asumo que $salas_datos contiene los datos de las salas
                                            // y que cada sala tiene 'id_sala' y 'nombre' (o similar)
                                            if (!empty($salas_datos)) {
                                                foreach ($salas_datos as $sala) {
                                                    // Ajusta 'nombre' si el nombre de la columna es diferente (ej: 'numero_sala')
                                                    echo '<option value="' . htmlspecialchars($sala['id_sala']) . '">' . htmlspecialchars($sala['nombre']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="precio">Precio</label>
                                        <input type="number" name="precio" class="form-control" placeholder="Ej: 15.00" required step="0.01" min="0">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="nivel">Nivel</label>
                                        <input type="text" name="nivel" class="form-control" placeholder="Ej: Principiante, Intermedio, Avanzado" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                     <div class="form-group col-md-6">
                                        <label for="id_categoria_clase">Categoría de Clase</label>
                                        <select name="id_categoria_clase" class="form-control" required>
                                            <option value="">Seleccione una categoría</option>
                                            <?php
                                            // Asumo que $categorias_clases_datos contiene los datos de las categorías
                                            // y que cada categoría tiene 'id_categoria_clase' y 'nombre' (o similar)
                                            if (!empty($categorias_clases_datos)) {
                                                foreach ($categorias_clases_datos as $categoria) {
                                                    // Ajusta 'nombre' si el nombre de la columna es diferente (ej: 'nombre_categoria')
                                                    echo '<option value="' . htmlspecialchars($categoria['id_categoria']) . '">' . htmlspecialchars($categoria['nombre']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cancelada">Cancelada</label>
                                        <select name="cancelada" class="form-control" required>
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="requisitos">Requisitos (Opcional)</label>
                                    <textarea name="requisitos" class="form-control" rows="2" placeholder="Escriba los requisitos para la clase..."></textarea>
                                </div>


                                <hr>
                                <div class="form-group text-center">
                                    <!-- Asumiendo que la vista de índice de clases está en App/views/clases/index.php -->
                                    <a href="<?php echo $URL; ?>App/views/clases/index.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Guardar Clase</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<!-- Asegúrate de que estos scripts estén incluidos en parte2.php o aquí -->
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

<?php include('../layout/parte2.php'); ?>
