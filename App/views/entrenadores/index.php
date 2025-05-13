<?php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/entrenadores/list_entrenadores.php');
//include('../layout/sesion.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(14, 148, 160),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-dumbbell fa-lg"></i> Listado de Entrenadores
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
                            <h3 class="card-title">Entrenadores Registrados</h3>
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
                                            <th class="align-middle" style="width: 20%;">Nombres</th>
                                            <th class="align-middle" style="width: 20%;">Apellidos</th>
                                            <th class="align-middle" style="width: 20%;">Especialidad</th>
                                            <th class="align-middle" style="width: 15%;">Teléfono</th>
                                            <th class="align-middle" style="width: 20%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($entrenadores_datos as $entrenador) {
                                            $id_entrenador = $entrenador['id_entrenador']; ?>
                                            <tr id="row-<?php echo $id_entrenador; ?>">
                                                <td class="align-middle"><?php echo ++$contador; ?></td>
                                                <td class="align-middle text-truncate" style="max-width: 250px;"><?php echo $entrenador['nombres']; ?></td>
                                                <td class="align-middle"><?php echo $entrenador['apellidos']; ?></td>
                                                <td class="align-middle"><?php echo $entrenador['especialidad']; ?></td>
                                                <td class="align-middle"><?php echo $entrenador['telefono']; ?></td>
                                                <td class="align-middle">
                                                    <div class="btn-group">
                                                        <a href="show.php?id=<?php echo $id_entrenador; ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="update.php?id=<?php echo $id_entrenador; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <!-- Botón de eliminación con SweetAlert2 -->
                                                        <button class="btn btn-danger btn-sm btn-eliminar"
                                                            data-id="<?php echo $id_entrenador; ?>"
                                                            data-row-id="row-<?php echo $id_entrenador; ?>">
                                                            <i class="fas fa-trash"></i>
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

<script src="datatable.js"></script>
<script src="delete.js"></script>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>