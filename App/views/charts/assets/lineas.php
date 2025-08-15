<script>
 document.addEventListener("DOMContentLoaded", function () {
  fetch("http://localhost/sistransporte/App/controllers/ordenesServicios/lineas.php")
    .then(response => response.text()) // Obtener la respuesta en texto para depurar
    .then(text => {
      console.log("🔍 Respuesta sin procesar:", text); // Muestra el JSON crudo en consola
      return JSON.parse(text); // Convertir a JSON
    })
    .then(data => {
      if (!data.labels || !data.values) {
        console.error("❌ Estructura de datos incorrecta:", data);
        return;
      }

      console.log("✅ Datos recibidos para Chart.js:", data);

      var ctxLine = document.getElementById("lineChart");

      if (!ctxLine) {
        console.error("❌ No se encontró el elemento con id 'lineChart'");
        return;
      }

      ctxLine = ctxLine.getContext("2d");

      new Chart(ctxLine, {
        type: "line",
        data: {
          labels: data.labels.map(dia => traducirDia(dia)), // Traduce los días
          datasets: [
            {
              label: "Órdenes Completadas",
              data: data.values,
              borderColor: "rgba(75, 192, 192, 1)",
              backgroundColor: "rgba(75, 192, 192, 0.2)",
              fill: true,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
        },
      });
    })
    .catch(error => console.error("❌ Error al obtener los datos:", error));

  // Función para traducir los días de la semana
  function traducirDia(dia) {
    const traducciones = {
      "Monday": "Lunes",
      "Tuesday": "Martes",
      "Wednesday": "Miércoles",
      "Thursday": "Jueves",
      "Friday": "Viernes",
      "Saturday": "Sábado",
      "Sunday": "Domingo"
    };
    return traducciones[dia] || dia;
  }
});

</script>
