<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("http://localhost/sistransporte/App/controllers/ordenesServicios/barChart.php")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Error en la respuesta:", data.error);
                    return;
                }

                console.log("📊 Datos recibidos:", data);

                var ctxBar1 = document.getElementById("barChart1").getContext("2d");

                // Aseguramos que las etiquetas representen todos los meses (de Enero a Diciembre)
                var allMonths = [
                    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                ];

                // Si las etiquetas de los meses no están completas, completamos el resto
                var labels = allMonths;

                // Aseguramos que los datos tengan 12 valores (para cada mes del año)
                var values = data.values.length === 12 ? data.values : Array(12).fill(0);

                // Colores diferentes para cada barra
                var colors = [
                    "rgba(54, 162, 235, 0.8)", "rgba(255, 99, 132, 0.8)", "rgba(75, 192, 192, 0.8)",
                    "rgba(153, 102, 255, 0.8)", "rgba(255, 159, 64, 0.8)", "rgba(255, 205, 86, 0.8)",
                    "rgba(255, 99, 132, 0.8)", "rgba(54, 162, 235, 0.8)", "rgba(75, 192, 192, 0.8)",
                    "rgba(153, 102, 255, 0.8)", "rgba(255, 159, 64, 0.8)", "rgba(255, 205, 86, 0.8)"
                ];

                // **Gráfico combinado para todos los meses**
                new Chart(ctxBar1, {
                    type: "bar",
                    data: {
                        labels: labels,  // Usamos las etiquetas de los 12 meses
                        datasets: [{
                            label: "Órdenes Completadas por Mes",
                            data: values,  // Datos de todas las órdenes por mes
                            backgroundColor: colors,  // Colores diferentes para cada barra
                            borderColor: colors.map(color => color.replace("0.8", "1")), // Hacemos los bordes más fuertes
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error("❌ Error al obtener los datos:", error));
    });
</script>
