<template>
  <div class="row">
    <div class="alert alert-info">
      <div class="row">
        <div class="col-md-4">
          <strong>Modalidad:</strong>
          {{ ecoCom.procedureModalityName }}
        </div>
        <div class="col-md-4">
          <strong>Tipo de Recepcion:</strong>
          {{ ecoCom.receptionType.name }}
        </div>
        <div class="col-md-4">
          <strong>Regional:</strong>
          {{ ecoCom.city }}
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <strong>Ente Gestor:</strong>
          {{ ecoCom.pensionEntityName }}
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState, mapGetters, mapMutations } from "vuex";
export default {
  computed: {
    ...mapGetters("ecoComForm", {
      ecoCom: "getData"
    })
  }
};
</script>
