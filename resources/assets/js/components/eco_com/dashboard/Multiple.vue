<template>
  <div class="ibox">
    <div class="ibox-title">
      <h2 class="pull-left">{{ title }} {{getProcedureName}}</h2>
      <div class="ibox-tools">
        <button @click="draw()" class="btn">
          <i class="fa fa-refresh"></i>
        </button>
        <select v-model="ecoComProcedureId" @change="draw()" v-if="ecoComProcedures.length > 0">
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
  props: ["title", "ecoComProcedures", "type", "url"],
  data() {
    return {
      ecoComProcedureId:
        this.ecoComProcedures.length > 0
          ? this.ecoComProcedures[0].id
          : null,
      dataSet: []
    };
  },
  methods: {
    async draw() {
      await axios
        .get(`/${this.url}`, {
          params: {
            id: this.ecoComProcedureId
          }
        })
        .then(response => {
          this.dataSet = response.data;
        })
        .catch(error => {
          console.log(error);
        });
      let data, options;
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
      var myChart = new Chart(this.$refs.canvas.getContext("2d"), {
        type: this.type,
        data: data,
        options: options
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
