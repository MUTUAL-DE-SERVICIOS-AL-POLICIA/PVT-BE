<template>
  <div>
    <div class="btn-group">
      <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
        <i class="fa fa-puzzle-piece"></i>
      </button>
      <ul class="dropdown-menu">
        <li v-for="ecoComProcedure in ecoComProcedures" :key="ecoComProcedure.id">
          <a class="dropdown-item" :href="`/eco_com_process/${ecoComProcess.id}/economic_complement/create/${ecoComProcedure.id}`">
            <i class="fa" :class="{'fa-plus': !ecoComProcedure.has, 'fa-eye': ecoComProcedure.has }"></i> {{ ecoComProcedure.has ? 'Ver': 'Crear' }} Tramite
            <strong>{{ getProcedureName(ecoComProcedure)}}</strong>
          </a>
        </li>
        <li class="dropdown-divider"></li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ecoComProcess", "ecoComProcedures"],
  data() {
    return {};
  },
  methods: {
    getProcedureName(ecoComProcedure) {
      return `${ecoComProcedure.semester}/${moment(ecoComProcedure.year).year()}`;
    }
  }
};
</script>

<style>
</style>
