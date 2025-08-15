<?php
include(__DIR__ . '../assets/style.php');
?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Tarjeta de Interactive Area Chart -->
      <div class="col-lg-6 col-md-12">
        <div class="card card-primary">
          <div class="card-header">Mediciones de Kilometraje</div>
          <div class="card-body">
            <div id="interactive-area-chart"></div>
          </div>
        </div>
      </div>

      <!-- Tarjeta de Donut Chart -->
      <div class="col-lg-6 col-md-12">
        <div class="card card-danger">
          <div class="card-header">Rutas Más Usadas</div>
          <div class="card-body">
            <canvas id="donutChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Tarjeta de Bar Chart - Semestre 1 y 2 -->
      <div class="col-lg-6 col-md-12">
        <div class="card card-success">
          <div class="card-header">Órdenes Completadas</div>
          <div class="card-body">
            <canvas id="barChart1"></canvas>
          </div>
        </div>
      </div>

      <!-- Tarjeta de Line Chart -->
      <div class="col-lg-6 col-md-12">
        <div class="card card-indigo">
          <div class="card-header">Estadística Semanal - Semana <span id="currentWeek"></span></div>

          <script>
            function getWeekNumber(date) {
              const startOfYear = new Date(date.getFullYear(), 0, 1);
              const pastDays = (date - startOfYear) / (1000 * 60 * 60 * 24) + 1;
              return Math.ceil(pastDays / 7);
            }

            document.getElementById("currentWeek").textContent = getWeekNumber(new Date());
          </script>

          <div class="card-body  custom-height-semanal">
            <canvas id="lineChart" class="custom-week-chart"></canvas>
          </div>
        </div>
      </div>

       <!-- Tarjeta de Órdenes por Estado -->
       <div class="col-lg-12 col-md-12">
        <div class="card card-maroon">
          <div class="card-header">Órdenes por Estado - Año <span id="currentYear"></span></div>

          <script>
            document.getElementById("currentYear").textContent = new Date().getFullYear();
          </script>

          <div class="card-body">
          <canvas id="lineChart2" class="custom-tall-chart"></canvas>
          </div>
        </div>
      </div>


  </div>
</section>


<!-- Scripts necesarios -->
<!-- jQuery (requerido para Bootstrap y Flot) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS y dependencias -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- Flot y su plugin de redimensionamiento -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.resize.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<?php
include(__DIR__ . '../assets/conficharts.php');
include(__DIR__ . '../assets/donut.php');
include(__DIR__ . '../assets/lineas.php');
include(__DIR__ . '../assets/barchart.php');
include(__DIR__ . '../assets/multilinea.php');
?>