<template>
  <div class="row">
     <div class="col-lg-12">
        <div class="ibox">
              <div class="ibox-title">
                <h2 class="pull-left">Calcular Pago a Futuro</h2>
                <div class="ibox-tools"></div>
              </div>
              <div class="col-md-6 ibox-content">
                <label>Gestion</label>
                <select v-model="form.ecoComProcedureId" :disabled="loadingButton">
                  <option v-for="r in ecoComProcedures" :value="r.id" :key="r.id">{{r.full_name}}</option>
                </select>
                <br />
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="sendForm()"
                  :disabled="loadingButton"
                  title="Esta accion registrará los aportes de Complemento Económico"
                >
                  <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
                  <i v-else class="fa fa-save"></i>
                  &nbsp;
                  {{ loadingButton ? 'Procesando...' : 'Calcular' }}
                </button>
                <br />
                  {{ loadingButton ? 'Procesando...' : '' }}
                  <br />
              </div>
              <div  class="col-md-6 alert alert-info text-left">
                      Consideraciones para el calculo:<br />
                      - El trámite se debe encontrar en el Área Técnica.<br />
                      - El trámite debe tener el estado en Proceso.<br />
                      - La renta total debe ser Mayor a 0.<br />
              </div>
        </div>
      </div>

    <div class="col-lg-12" v-if="showResults">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Resultados</h2>
          <div class="ibox-tools">
          </div>
        </div>
        <div class="ibox-content">
            <div>
              <div class="alert alert-danger" v-if="is_error">
              <ul>
                <li class="alert-danger" >{{ result_error }}</li>
              </ul>
            </div>
              </div>
          <div class="row">
            <h2>Total Número de Trámites: {{ tramit_number }}</h2>
            <h2>Total de Aportes: {{ total_contribution }}</h2>
            <h2>Número de Aportes Creados: {{ contribution_created }}</h2>
            <h2>Número de Aportes Actualizados: {{ contribution_updated }}</h2>
            <template v-if="not_updated.length > 0">
              <h2>Trámites sin renta actualizada o con renta total cero:</h2>
              <h3 v-for="item in not_updated ">{{ item }}</h3>
            </template>
          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["permissions","ecoComProcedures"],
  data() {
    return {
      loadingButton: false,
      tramit_number: 0,
      contribution_created: 0,
      contribution_updated: 0,
      total_contribution: 0,
      not_updated: [],
      result_error:'',
      is_error: false,
      fails: [],
      showResults: false,
      refresh: false,
      override: false,
      form: {
        ecoComProcedureId:
          this.ecoComProcedures.length > 0 ? this.ecoComProcedures[0].id : null
      },
    };
  },
  methods: {
    async refreshData() {
      this.refresh = true;
      this.sendForm()
    },
    async sendForm() {
      this.showResults = false;
      this.override = false;
      const formData = new FormData();
      formData.append("ecoComProcedureId", this.form.ecoComProcedureId);
      this.loadingButton = true;
      await axios
        .post("eco_com_import_pago_futuro", formData)
        .then(response => {
          // console.log(response.data);
          this.tramit_number = response.data.tramit_number;
          this.contribution_updated = response.data.contribution_updated;
          this.contribution_created = response.data.contribution_created;
          this.total_contribution = response.data.total_contribution;
          this.not_updated = response.data.not_updated;
          this.is_error = false;
        })
        .catch(error => {
          console.log(error.response.data.errors[0]);
          if(error.response.data.errors[0]){
            this.result_error = error.response.data.errors[0];
            this.is_error = true;
            this.tramit_number = error.response.data.data.tramit_number;
            this.contribution_updated = error.response.data.data.contribution_updated;
            this.contribution_created = error.response.data.data.contribution_created;
            this.total_contribution = error.response.data.data.total_contribution;
          }
        });
      this.showResults = true;
      this.loadingButton = false
      this.refresh = false
    }
  }
};
</script>

<style>
</style>
