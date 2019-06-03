<template>
  <div class="ibox">
    <div class="ibox-title">
      <h3 class="pull-left">{{ title }} {{getProcedureName}}</h3>
      <div class="ibox-tools">
        <button @click="reload()" class="btn">
          <i class="fa fa-refresh"></i>
        </button>
        <select v-model="ecoComProcedureId" @change="reload()" class>
          <option :value="null"></option>
          <option
            v-for="e in ecoComProcedures"
            :key="e.id"
            :value="e.id"
          >{{e.year | year }} {{ e.semester }}</option>
        </select>
      </div>
    </div>
    <div class="ibox-content">
      <canvas ref="canvas"></canvas>
    </div>
  </div>
</template>

<script>
import Chart from "chart.js";
export default {
  props: [
    "title",
    "ecoComProcedures",
    "type",
    "url",
    "years",
    "options",
    "load"
  ],
  data() {
    return {
      ecoComProcedureId:
        this.ecoComProcedures.length > 0
          ? this.ecoComProcedures[this.ecoComProcedures.length - 1].id
          : null,
      dataSet: [],
      data: [],
      chart: ''
    };
  },
  mounted() {
    if (this.load == false) {
    } else {
      this.draw();
    }
  },
  methods: {
    async draw() {
      await axios
        .get(`/${this.url}`, {
          params: {
            id: this.ecoComProcedureId,
            years: this.years
          }
        })
        .then(response => {
          this.dataSet = response.data;
          this.data = {
            labels: this.dataSet.map(item => item.name),
            datasets: [
              {
                data: this.dataSet.map(item => item.quantity),
                backgroundColor: [
                  "rgba(255, 99, 132, 0.6)",
                  "rgba(54, 162, 235, 0.6)",
                  "rgba(255, 206, 86, 0.6)",
                  "rgba(75, 192, 192, 0.6)",
                  "rgba(153, 102, 255, 0.6)",
                  "rgba(255, 159, 64, 0.6)"
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
          this.render();
        })
        .catch(error => {
          console.log(error);
        });
    },
    reload(){
        if (this.chart) {
            this.chart.destroy();
        }
        this.draw()
    },
    render() {
      this.chart = new Chart(this.$refs.canvas.getContext("2d"), {
        type: this.type,
        data: this.data,
        options: this.options
      });
    }
  },
  computed: {
    getProcedureName() {
      if (this.ecoComProcedureId) {
        let p = this.ecoComProcedures.find(x => x.id == this.ecoComProcedureId);
        return `${p.semester} semestre ${p.year}`;
      }
      return null;
    }
  }
};
</script>
<style>
</style>
