<?php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/productos/list_productos.php');
include('../layout/sesion.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(14, 148, 160),rgb(11, 191, 251)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-box fa-lg"></i> Listado de Productos
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
                            <h3 class="card-title">Productos Registrados</h3>
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
                                            <th class="align-middle" style="width: 20%;">Nombre</th>
                                            <th class="align-middle" style="width: 20%;">Descripción</th>
                                            <th class="align-middle" style="width: 10%;">Precio</th>
                                            <th class="align-middle" style="width: 10%;">Stock</th>
                                            <th class="align-middle" style="width: 15%;">Categoría</th>
                                            <th class="align-middle" style="width: 20%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($productos_datos as $producto) {
                                            $id_producto = $producto['id_producto']; ?>
                                            <tr id="row-<?php echo $id_producto; ?>">
                                                <td class="align-middle"><?php echo ++$contador; ?></td>
                                                <td class="align-middle text-truncate" style="max-width: 250px;"><?php echo $producto['nombre']; ?></td>
                                                <td class="align-middle text-truncate" style="max-width: 250px;"><?php echo $producto['descripcion']; ?></td>
                                                <td class="align-middle"><?php echo number_format($producto['precio'], 2); ?></td>
                                                <td class="align-middle"><?php echo $producto['cantidad_stock']; ?></td>
                                                <td class="align-middle"><?php echo $producto['id_categoria']; ?></td>
                                                <td class="align-middle">
                                                    <div class="btn-group">
                                                        <a href="show.php?id=<?php echo $id_producto; ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="update.php?id=<?php echo $id_producto; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <!-- Botón de eliminación con SweetAlert2 -->
                                                        <button class="btn btn-danger btn-sm btn-eliminar"
                                                            data-id="<?php echo $id_producto; ?>"
                                                            data-row-id="row-<?php echo $id_producto; ?>">
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