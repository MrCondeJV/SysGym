<?php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/categorias_clases/list_categorias_clases.php');
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
                            <i class="fas fa-door-open fa-lg"></i> Listado de Categorias de Clases
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
                            <h3 class="card-title">Categorias Registradas</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>

                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="mb-3 text-right">
                                    <a href="create_categoria.php" class="btn btn-success">
                                        <i class="fas fa-plus-circle"></i> Nueva Categoría
                                    </a>
                                </div>
                                <table id="example1" class="table table-bordered table-striped table-sm text-center" style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="align-middle" style="width: 10%;">Nro</th>
                                            <th class="align-middle" style="width: 30%;">Nombre</th>
                                            <th class="align-middle" style="width: 30%;">Descripción</th>
                                            <th class="align-middle" style="width: 10%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($categorias_clases_datos as $categorias_clases_dato) {
                                            $id_categoria = $categorias_clases_dato['id_categoria']; ?>
                                            <tr id="row-<?php echo $id_categoria; ?>">
                                                <td class="align-middle"><?php echo ++$contador; ?></td>
                                                <td class="align-middle text-truncate" style="max-width: 250px;"><?php echo $categorias_clases_dato['nombre']; ?></td>
                                                <td class="align-middle text-truncate" style="max-width: 300px;"><?php echo $categorias_clases_dato['descripcion']; ?></td>
                                                <td class="align-middle">
                                                    <div class="btn-group">
                                                    
                                                        <a href="update_categoria.php?id=<?php echo $id_categoria; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

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