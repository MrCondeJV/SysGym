<?php

include('../../config.php');
include('../layout/parte1.php');
// Incluir el controlador que lista las clases
include('../../controllers/clases/list_clases.php'); // Asumo que este script carga los datos en $clases_datos
//include('../layout/sesion.php'); // Asegúrate de incluir esto si usas sesiones para autenticación
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(14, 148, 160),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-dumbbell fa-lg"></i> Listado de Clases
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-10 col-sm-12 mx-auto">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Clases Registradas</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-sm text-center" style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="align-middle" style="width: 5%;">Nro</th>
                                            <th class="align-middle" style="width: 15%;">Nombre</th>
                                            <th class="align-middle" style="width: 10%;">Horario</th>
                                            <th class="align-middle" style="width: 10%;">Día</th>
                                            <th class="align-middle" style="width: 10%;">Duración (min)</th>
                                            <th class="align-middle" style="width: 10%;">Capacidad</th>
                                            <th class="align-middle" style="width: 10%;">Precio</th>
                                            <th class="align-middle" style="width: 10%;">Nivel</th>
                                            <th class="align-middle" style="width: 10%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        // Iterar sobre los datos de las clases
                                        // Asumo que $clases_datos contiene los datos de las clases obtenidos por list_clases.php
                                        if (!empty($clases_datos)) {
                                            foreach ($clases_datos as $clase) {
                                                $id_clase = $clase['id_clase']; ?>
                                                <tr id="row-<?php echo $id_clase; ?>">
                                                    <td class="align-middle"><?php echo ++$contador; ?></td>
                                                    <td class="align-middle text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($clase['nombre']); ?></td>
                                                    <td class="align-middle"><?php echo htmlspecialchars($clase['horario']); ?></td>
                                                    <td class="align-middle"><?php echo htmlspecialchars($clase['dia_semana']); ?></td>
                                                    <td class="align-middle"><?php echo htmlspecialchars($clase['duracion_minutos']); ?></td>
                                                    <td class="align-middle"><?php echo htmlspecialchars($clase['capacidad_maxima']); ?></td>
                                                    <td class="align-middle"><?php echo htmlspecialchars($clase['precio']); ?></td>
                                                    <td class="align-middle"><?php echo htmlspecialchars($clase['nivel']); ?></td>
                                                    <td class="align-middle">
                                                        <div class="btn-group">
                                                            <!-- Enlace para ver detalles de la clase -->
                                                            <a href="show.php?id=<?php echo htmlspecialchars($id_clase); ?>" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <!-- Enlace para editar la clase -->
                                                            <a href="update.php?id=<?php echo htmlspecialchars($id_clase); ?>" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <!-- Botón de eliminación con SweetAlert2 -->
                                                            <button class="btn btn-danger btn-sm btn-eliminar"
                                                                data-id="<?php echo htmlspecialchars($id_clase); ?>"
                                                                data-row-id="row-<?php echo htmlspecialchars($id_clase); ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="9" class="text-center">No hay clases registradas.</td>
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

<!-- Asegúrate de que estos scripts estén incluidos en parte2.php o aquí -->
<!-- <script src="datatable.js"></script> -->
<!-- <script src="delete.js"></script> -->
<!-- Asegúrate de que SweetAlert2 esté incluido -->
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<script src="delete.js"></script>
<script src="datatable.js"></script>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
