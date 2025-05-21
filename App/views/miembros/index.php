<?php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/miembros/list_miembros.php');
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
                            <i class="fas fa-users fa-lg"></i> Listado de Miembros
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
                            <h3 class="card-title">Miembros Registrados</h3>
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
                                            <th class="align-middle" style="width: 15%;">Nombres</th>
                                            <th class="align-middle" style="width: 15%;">Apellidos</th>
                                            <th class="align-middle" style="width: 10%;">Género</th>
                                            <th class="align-middle" style="width: 15%;">Correo</th>
                                            <th class="align-middle" style="width: 10%;">Teléfono</th>
                                            <th class="align-middle" style="width: 15%;">Estado</th>
                                            <th class="align-middle" style="width: 15%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($miembros_datos as $miembro) {
                                            $id_miembro = $miembro['id_miembro']; ?>
                                            <tr id="row-<?php echo $id_miembro; ?>">
                                                <td class="align-middle"><?php echo ++$contador; ?></td>
                                                <td class="align-middle text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($miembro['nombres']); ?></td>
                                                <td class="align-middle"><?php echo htmlspecialchars($miembro['apellidos']); ?></td>
                                                <td class="align-middle"><?php echo htmlspecialchars($miembro['genero']); ?></td>
                                                <td class="align-middle"><?php echo htmlspecialchars($miembro['correo_electronico']); ?></td>
                                                <td class="align-middle"><?php echo htmlspecialchars($miembro['telefono']); ?></td>
                                                <td class="align-middle"><?php echo htmlspecialchars($miembro['estado']); ?></td>
                                                <td class="align-middle">
                                                    <div class="btn-group">
                                                        <a href="show.php?id=<?php echo $id_miembro; ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="update.php?id=<?php echo $id_miembro; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                         <!-- Botón para registrar huella digital -->
                                                        <a href="#" 
                                                           class="btn btn-success btn-sm btn-huella" 
                                                           data-id="<?php echo $id_miembro; ?>" 
                                                           title="Registrar huella digital">
                                                            <i class="fas fa-fingerprint"></i>
                                                        </a>
                                                        <!-- Botón de eliminación con SweetAlert2 -->
                                                        <button class="btn btn-danger btn-sm btn-eliminar"
                                                            data-id="<?php echo $id_miembro; ?>"
                                                            data-row-id="row-<?php echo $id_miembro; ?>">
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

<!-- Modal para registro de huella digital -->
<div class="modal fade" id="modalHuella" tabindex="-1" role="dialog" aria-labelledby="modalHuellaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalHuellaLabel">Registro de Huella Digital</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <p id="estadoHuella">Conectando con el lector de huellas...</p>
        <div id="spinnerHuella" class="mb-2">
          <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- /.content-wrapper -->

<script src="datatable.js"></script>
<script src="delete.js"></script>
<script>
$(document).ready(function() {
    const HUELLA_API_URL = 'http://localhost:5000/registrar_huella';
    const GUARDAR_HUELLA_URL = 'http://localhost/SysGym/App/controllers/huellasdigitales/guardar_huella.php';
    const MODAL_HUELLA = $('#modalHuella');
    const ESTADO_HUELLA = $('#estadoHuella');
    const SPINNER_HUELLA = $('#spinnerHuella');

    $('.btn-huella').on('click', async function(e) {
        e.preventDefault();
        const idMiembro = $(this).data('id');
        
        try {
            // Configurar modal
            ESTADO_HUELLA.text('Iniciando comunicación con el lector...');
            SPINNER_HUELLA.show();
            MODAL_HUELLA.modal('show');

            // Paso 1: Capturar huella con C#
            const responseCaptura = await fetch(`${HUELLA_API_URL}?id_miembro=${encodeURIComponent(idMiembro)}`);
            
            if (!responseCaptura.ok) {
                throw new Error(`Error HTTP: ${responseCaptura.status}`);
            }
            
            const dataCaptura = await responseCaptura.json();
            
            if (!dataCaptura.success || !dataCaptura.plantilla) {
                throw new Error(dataCaptura.message || 'Error al capturar la huella');
            }

            // Paso 2: Enviar a PHP
            const formData = new URLSearchParams();
            formData.append('id_miembro', idMiembro);
            formData.append('huella', dataCaptura.plantilla);
            formData.append('modo', 'registrar');

            const responseGuardar = await fetch(GUARDAR_HUELLA_URL, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: formData
            });

            if (!responseGuardar.ok) {
                throw new Error(`Error HTTP: ${responseGuardar.status}`);
            }

            const resultado = await responseGuardar.json();
            
            SPINNER_HUELLA.hide();
            ESTADO_HUELLA.html(resultado.success ? 
                `<span class="text-success">${resultado.message}</span>` :
                `<span class="text-danger">${resultado.message}</span>`
            );

            if (resultado.success) {
                setTimeout(() => MODAL_HUELLA.modal('hide'), 2000);
            }

        } catch (error) {
            console.error('Error en proceso:', error);
            SPINNER_HUELLA.hide();
            ESTADO_HUELLA.html(`<span class="text-danger">${error.message || 'Error desconocido'}</span>`);
            
            // Auto-cierre seguro del modal
            if (!MODAL_HUELLA.data('bs.modal')._isShown) {
                setTimeout(() => MODAL_HUELLA.modal('hide'), 3000);
            }
        }
    });

    // Reiniciar estado del modal
    MODAL_HUELLA.on('hidden.bs.modal', () => {
        ESTADO_HUELLA.text('Conectando con el lector de huellas...');
        SPINNER_HUELLA.show();
    });
});
</script>


<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>