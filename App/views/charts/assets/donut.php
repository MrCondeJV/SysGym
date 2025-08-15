<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("http://localhost/sistransporte/App/controllers/rutas/get_rutas.php")
        .then(response => response.text()) // Leer como texto primero
        .then(text => {
            try {
                console.log("Respuesta sin procesar:", text); // <-- Verifica la respuesta en consola
                const data = JSON.parse(text); // Intentar convertir a JSON
                if (!data.labels || !Array.isArray(data.labels) || !data.data || !Array.isArray(data.data)) {
                    throw new Error("Estructura de datos inválida en la respuesta");
                }
                return data;
            } catch (error) {
                throw new Error("Error al parsear JSON: " + error.message + "\nRespuesta: " + text);
            }
        })
        .then(data => {
            console.log("Datos recibidos para el gráfico:", data);

            var donutChartCanvas = $("#donutChart").get(0).getContext("2d");
            new Chart(donutChartCanvas, {
                type: "doughnut",
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: [
                            "#f56954", "#00a65a", "#f39c12",
                            "#00c0ef", "#3c8dbc", "#d2d6de"
                        ]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true
                }
            });
        })
        .catch(error => console.error("Error en fetch:", error));
    });
</script>
