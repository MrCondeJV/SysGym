<?php
// filepath: c:\xampp\htdocs\SysGym\App\views\productos\index.php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/productos/list_productos.php');
include('../layout/sesion.php');
?>

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
            <div class="row">
                <?php
                $contador = 0;
                foreach ($productos_datos as $producto) {
                    $id_producto = $producto['id_producto'];
                    $img = !empty($producto['url_imagen']) ? $producto['url_imagen'] : '../../img/productos/product_default.png';
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 shadow card-product">
                        <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="Imagen del producto" style="height:180px;object-fit:cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title font-weight-bold text-primary text-truncate"><?= htmlspecialchars($producto['nombre']) ?></h5>
                            <p class="card-text mb-1"><b>Descripción:</b> <?= htmlspecialchars($producto['descripcion']) ?></p>
                            <p class="card-text mb-1"><b>Precio:</b> $<?= number_format($producto['precio'], 2) ?></p>
                            <p class="card-text mb-1"><b>Stock:</b> <?= htmlspecialchars($producto['cantidad_stock']) ?></p>
                            <p class="card-text mb-2"><b>Categoría:</b> <?= htmlspecialchars($producto['nombre_categoria']) ?></p>
                            <div class="mt-auto">
                                <div class="btn-group btn-group-sm w-100">
                                    <a href="show.php?id=<?= $id_producto ?>" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="update.php?id=<?= $id_producto ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-danger btn-eliminar"
                                        data-id="<?= $id_producto ?>"
                                        data-row-id="row-<?= $id_producto ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

<style>
.card-product {
    transition: box-shadow 0.2s;
}
.card-product:hover {
    box-shadow: 0 8px 32px rgba(25,118,210,0.18);
}
.card-title {
    font-size: 1.15rem;
}
.card-product .card-img-top {
    padding: 12px;
    border-radius: 18px;
    transition: transform 0.25s cubic-bezier(.4,2,.6,1), box-shadow 0.2s;
}

.card-product:hover .card-img-top {
    transform: scale(1.06);
    box-shadow: 0 8px 32px rgba(25,118,210,0.18);
}
</style>

<script src="delete.js"></script>
<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>