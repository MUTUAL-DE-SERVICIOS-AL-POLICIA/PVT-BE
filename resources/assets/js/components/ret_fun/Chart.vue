<template>
	<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h1>{{ title }}</h1>
        </div>
        <div class="ibox-content">
			<canvas :id="id"></canvas>
        </div>
    </div>
</template>

<script>
import Chart from "chart.js";
export default {
  props: ["id", "title", "type", "dataSet"],
  mounted() {
    this.draw();
  },
  data() {
    return {};
  },
  methods: {
    draw() {
      let ctx = document.getElementById(this.id);
      let data, options;
      switch (this.type) {
        case "pie":
          data = {
            labels: this.dataSet.map(item => item.name),
            datasets: [
              {
                data: this.dataSet.map(item => item.quantity),
                backgroundColor: [
                  "rgba(255, 99, 132, 0.9)",
                  "rgba(54, 162, 235, 0.9)",
                  "rgba(255, 206, 86, 0.9)",
                  "rgba(75, 192, 192, 0.9)",
                  "rgba(153, 102, 255, 0.9)",
                  "rgba(255, 159, 64, 0.9)"
                ],
                borderColor: [
                  "rgba(255,99,132,1)",
                  "rgba(54, 162, 235, 1)",
                  "rgba(255, 206, 86, 1)",
                  "rgba(75, 192, 192, 1)",
                  "rgba(153, 102, 255, 1)",
                  "rgba(255, 159, 64, 1)"
                ]
                //   borderWidth: 1
              }
            ]
          };
          break;
        case "bar":
          data = {
            labels: this.dataSet.map(item => item.name),
            datasets: [
              {
                label: "Pendientes",
                backgroundColor: "#8e5ea2",
                data: this.dataSet.map(item => item.pendientes)
              },
              {
                label: "Validados",
                backgroundColor: "#3e95cd",
                data: this.dataSet.map(item => item.validados)
              }
            ]
          };
          options = {
            tooltips: {
              mode: "index",
              intersect: false
            },
            responsive: true
          };
        default:
          break;
      }
      var myChart = new Chart(ctx, {
        type: this.type,
        data: data,
        options: options
      });
    }
  }
};
</script>
