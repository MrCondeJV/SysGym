<script>
$(function () {
  // ========================
  // Gráfico interactivo con Flot
  // ========================
  var data = [];
  var totalPoints = 300;

  function getRandomData() {
    if (data.length > 0) {
      data = data.slice(1);
    }
    while (data.length < totalPoints) {
      var prev = data.length > 0 ? data[data.length - 1] : 50;
      var y = prev + Math.random() * 10 - 5;
      y = Math.max(0, Math.min(100, y));
      data.push(y);
    }
    return data.map((val, i) => [i, val]);
  }

  var interactivePlot = $.plot("#interactive-area-chart", [getRandomData()], {
    grid: {
      borderColor: "#f3f3f3",
      borderWidth: 1,
      tickColor: "#f3f3f3",
    },
    series: {
      shadowSize: 0,
      color: "#3c8dbc",
    },
    lines: {
      fill: true,
      color: "#3c8dbc",
    },
    yaxis: {
      min: 0,
      max: 100,
      show: true,
    },
    xaxis: {
      show: true,
    },
  });

  var updateInterval = 500;
  var realtime = "on";

  function update() {
    interactivePlot.setData([getRandomData()]);
    interactivePlot.draw();
    if (realtime === "on") {
      setTimeout(update, updateInterval);
    }
  }

  $("#realtime").on("change", function () {
    realtime = this.checked ? "on" : "off";
    if (realtime === "on") update();
  });

  update();
  
});
</script>
