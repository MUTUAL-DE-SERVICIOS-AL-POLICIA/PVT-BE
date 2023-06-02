<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <pie-bar
          title="Modalidades"
          url="chart_eco_com_modalities_general"
          type="pie"
          :eco-com-procedures="ecoComProcedures"
        ></pie-bar>
      </div>
      <div class="col-md-6">
        <pie-bar
          title="Tipos de Modalidad"
          url="chart_eco_com_modalities"
          type="pie"
          :eco-com-procedures="ecoComProcedures"
        ></pie-bar>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <pie-bar
          title="Regionales"
          url="chart_eco_com_cities"
          type="pie"
          :eco-com-procedures="ecoComProcedures"
        ></pie-bar>
      </div>
      <div class="col-md-6">
        <pie-bar
          title="Tipo de Recepcion"
          url="chart_eco_com_reception_type"
          type="doughnut"
          :eco-com-procedures="ecoComProcedures"
        ></pie-bar>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <pie-bar
          title="Ente Gestor"
          url="chart_eco_com_pension_entity"
          type="doughnut"
          :eco-com-procedures="ecoComProcedures"
        ></pie-bar>
      </div>
      <div class="col-md-6">
        <pie-bar
          title="Estado de los Tramites"
          url="chart_eco_com_states"
          type="doughnut"
          :eco-com-procedures="ecoComProcedures"
        ></pie-bar>
      </div>
    </div>
    <div class="row">
      <!-- <div class="col-md-6">
        <pie-bar
          title="Estado de los Tramites General"
          url="chart_eco_com_state_types"
          type="doughnut"
          :eco-com-procedures="ecoComProcedures"
        ></pie-bar>
      </div> -->
      <div class="col-md-6">
        <pie-bar
          title="Creación de trámites"
          url="chart_eco_com_creation"
          type="doughnut"
          :eco-com-procedures="ecoComProcedures">
        </pie-bar>
      </div>
      <div class="col-md-6">
        <multiple
          title="Ubicacion de los Tramites"
          url="chart_eco_com_wf_states"
          type="bar"
          :eco-com-procedures="ecoComProcedures"
        ></multiple>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <pie-bar
          :title="`Trámites de los últimos ${years} semestres`"
          :years="years"
          url="chart_eco_com_last_eco_com"
          type="bar"
          :load="false"
          :eco-com-procedures="[]"
          :options="{responsive: true,
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true,
				}
			}]
		}}"
        ></pie-bar>
      </div>
      <div class="col-md-6">
        <pie-bar
          :title="`Total Montos de los Trámites últimos ${years} semestres`"
          :years="years"
          url="chart_eco_com_total_amount_last_eco_com"
          type="bar"
          :load="false"
          :eco-com-procedures="[]"
          :options="{
		legend:{
			display:false
		},
		tooltips: {
			enabled: true,
			mode: 'single',
			callbacks: {
				label: function(tooltipItems, data) {
					var n = tooltipItems.yLabel;
					n = n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')
					return n + ' Bs';
				}
			}
		},
		responsive: true,
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true,
				}
			}]
		}
	}"
        ></pie-bar>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <pie-bar
          title="Recepcion de los tramites (por semana)"
          url="chart_eco_com_reception_months"
          type="line"
          :eco-com-procedures="ecoComProcedures"
          :options="{scales: {
			yAxes: [{
				ticks: {
					beginAtZero: false,
				}
			}]
		}}"
        ></pie-bar>
      </div>
      <div class="col-md-6"></div>
    </div>
  </div>
</template>

<script>
import Chart from "chart.js";
import Piebar from "./dashboard/PieBar.vue";
import Multiple from "./dashboard/Multiple.vue";
export default {
  props: ["ecoComProcedures"],
  components: {
    Piebar,
    Multiple
  },
  data() {
    return {
      years: 5
    };
  }
};
</script>

<style>
</style>
