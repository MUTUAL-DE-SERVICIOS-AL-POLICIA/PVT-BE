<template>
    <div class="row">
        <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
            <h2 class="pull-left">Cargar promedios</h2>
            <div class="ibox-tools"></div>
            </div>
            <div class="ibox-content">
            <label>Gestion</label>
            <select v-model="form.ecoComProcedureId" :disabled="true">
                <option v-for="r in ecoComProcedures" :value="r.id" :key="r.id">{{r.full_name}}</option>
            </select>
            <br />
            <label for="change-date">Fecha (yyyy-mm-dd)</label>
            <input type="text" id="change-date" v-model="form.changeDate"/>
            <br />
            <button
                type="button"
                class="btn btn-primary"
                @click="sendForm()"
                :disabled="loadingButton"
                title="Genera los promedios a la fecha indicada"
            >
                <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
                <i v-else class="fa fa-save"></i>
                &nbsp;
                {{ loadingButton ? 'Procesando...' : 'Cargar promedios' }}
            </button>
                &nbsp;
                {{ loadingButton ? 'Procesando...' : '' }}
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
    async sendForm() {
      this.showResults = false;
      this.override = false;
      const formData = new FormData();
      formData.append("ecoComProcedureId", this.form.ecoComProcedureId);
      formData.append("changeDate", this.form.changeDate);
      this.loadingButton = true;
      await axios
        .post("eco_com_load_promedio", formData)
        .then(response => {
           console.log(response.data);
        })
        .catch(error => {
          console.log(error);
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
