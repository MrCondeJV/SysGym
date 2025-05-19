<?php
// filepath: c:\xampp\htdocs\SysGym\App\views\marcacion\index.php
include('../../config.php');
include('../layout/parte1.php');
?>

<style>
.marcacion-container {
    max-width: 420px;
    margin: 40px auto;
    background: rgba(255,255,255,0.97);
    border-radius: 18px;
    box-shadow: 0 6px 24px rgba(13,71,161,0.10);
    padding: 32px 24px 24px 24px;
    text-align: center;
}
.marcacion-title {
    font-size: 2rem;
    color: #1976d2;
    font-weight: bold;
    margin-bottom: 10px;
}
.marcacion-sub {
    color: #555;
    margin-bottom: 28px;
}
.fingerprint-btn {
    background: linear-gradient(90deg,#1976d2,#42a5f5);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 90px;
    height: 90px;
    font-size: 2.5rem;
    margin: 0 10px;
    box-shadow: 0 2px 10px rgba(25,118,210,0.15);
    transition: background 0.2s, transform 0.2s;
}
.fingerprint-btn:hover {
    background: linear-gradient(90deg,#42a5f5,#1976d2);
    transform: scale(1.08);
}
.manual-btn {
    background: #fff;
    color: #1976d2;
    border: 2px solid #1976d2;
    border-radius: 25px;
    padding: 10px 28px;
    font-size: 1.1rem;
    font-weight: bold;
    margin-top: 18px;
    transition: background 0.2s, color 0.2s;
}
.manual-btn:hover {
    background: #1976d2;
    color: #fff;
}
#marcacion-resultado {
    margin-top: 22px;
    font-size: 1.1rem;
}
</style>

<div class="marcacion-container">
    <div class="marcacion-title">
        <i class="fas fa-fingerprint"></i> Control de Acceso
    </div>
    <div class="marcacion-sub">
        Coloca tu huella en el lector<br>
        <span style="font-size:0.95rem;">o usa el ingreso manual si tienes problemas</span>
    </div>
    <button class="fingerprint-btn" id="btn-huella" title="Marcar con huella">
        <i class="fas fa-fingerprint"></i>
    </button>
    <br>
    <button class="manual-btn mt-3" id="btn-manual">
        <i class="fas fa-keyboard"></i> Ingreso Manual
    </button>

    <div id="manual-form" style="display:none; margin-top:18px;">
        <form id="form-manual" autocomplete="off">
            <input type="text" name="id_miembro" class="form-control mb-2" placeholder="ID o nombre del miembro" required>
            <select name="tipo_marcacion" class="form-control mb-2" required>
                <option value="">Seleccione acción</option>
                <option value="entrada">Registrar Entrada</option>
                <option value="salida">Registrar Salida</option>
            </select>
            <button type="submit" class="btn btn-success btn-block">Registrar</button>
        </form>
    </div>

    <div id="marcacion-resultado"></div>
</div>

<script>
document.getElementById('btn-huella').onclick = function() {
    // Simulación de lectura de huella
    document.getElementById('marcacion-resultado').innerHTML =
        '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Leyendo huella...</span>';
    setTimeout(function() {
        // Aquí deberías integrar con el lector real
        document.getElementById('marcacion-resultado').innerHTML =
            '<span class="text-success"><i class="fas fa-check-circle"></i> ¡Ingreso registrado con huella!</span>';
    }, 1800);
};

document.getElementById('btn-manual').onclick = function() {
    var form = document.getElementById('manual-form');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
};

document.getElementById('form-manual').onsubmit = function(e) {
    e.preventDefault();
    document.getElementById('marcacion-resultado').innerHTML =
        '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Registrando...</span>';
    setTimeout(function() {
        document.getElementById('marcacion-resultado').innerHTML =
            '<span class="text-success"><i class="fas fa-check-circle"></i> ¡Marcación manual registrada!</span>';
        document.getElementById('form-manual').reset();
    }, 1200);
};
</script>

<?php include('../layout/parte2.php'); ?>