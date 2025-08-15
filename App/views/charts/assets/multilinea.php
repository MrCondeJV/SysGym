<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("http://localhost/sistransporte/App/controllers/ordenesServicios/multilinea.php")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Error en la respuesta:", data.error);
                    return;
                }

                var ctxLine = document.getElementById("lineChart2").getContext("2d");
                new Chart(ctxLine, {
                    type: "line",
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: "Pendiente",
                                data: data.pendiente,
                                borderColor: "rgba(255, 193, 7, 1)",
                                tension: 0.4,
                                fill: false,
                                borderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 5,
                            },
                            {
                                label: "Completado",
                                data: data.completado,
                                borderColor: "rgba(40, 167, 69, 1)",
                                tension: 0.4,
                                fill: false,
                                borderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 5,
                            },
                            {
                                label: "En Progreso",
                                data: data.en_progreso,
                                borderColor: "rgba(23, 162, 184, 1)",
                                tension: 0.4,
                                fill: false,
                                borderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 5,
                            },
                            {
                                label: "Cancelado",
                                data: data.cancelado,
                                borderColor: "rgba(220, 53, 69, 1)",
                                tension: 0.4,
                                fill: false,
                                borderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 5,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: "rgba(0,0,0,0.05)",
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: true,
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            },
                        },
                    },
                });
            })
            .catch(error => console.error("❌ Error al obtener los datos:", error));
    });
</script>
