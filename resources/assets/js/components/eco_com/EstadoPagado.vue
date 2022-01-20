<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Cambiar de estado a pagado</h2>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <label>Gestion</label>
          <select v-model="form.ecoComProcedureId" :disabled="loadingButton" @change="cargarplanilla()">
            <option v-for="r in ecoComProcedures" :value="r.id" :key="r.id">{{r.full_name}}</option>
          </select>
          <br />
          <label for="sigep">Habilitado para pago mediante sigep</label>
          <select id="sigep" v-model="form.sigepgestion" :disabled="loadingButton">
            <option v-for="s in sigep" :value="s.procedure_date" :key="s.procedure_date">{{s.procedure_date}}</option>
          </select>
          <button
            type="button"
            class="btn btn-primary"
            @click="sigepEstado()"
            :disabled="loadingButton"
            title="Cambiar el estado a pagado"
          >
            <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
            <i v-else class="fa fa-save"></i>
            &nbsp;
            {{ loadingButton ? 'Procesando...' : 'Cambiar a pagado' }}
          </button>
          <br>
          <br>
          <label for="banco">Habilitado para pago en Banco Unión</label>
          <select id="banco" v-model="form.bancogestion" :disabled="loadingButton">
            <option v-for="b in banco" :value="b.procedure_date" :key="b.procedure_date">{{b.procedure_date}}</option>
          </select>
          <button
            type="button"
            class="btn btn-primary"
            @click="sigepBanco()"
            :disabled="loadingButton"
            title="Cambiar el estado a pagado"
          >
            <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
            <i v-else class="fa fa-save"></i>
            &nbsp;
            {{ loadingButton ? 'Procesando...' : 'Cambiar a pagado' }}
          </button>
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
      fails: [],
      form: {
        ecoComProcedureId:
          this.ecoComProcedures.length > 0 ? this.ecoComProcedures[0].id : null,
        sigepgestion:'',
        bancogestion:''
      },
      sigep: [],
      banco: [],
    };
  },
  mounted() {
    this.cargarplanilla();
  },
  methods: {
    async cargarplanilla() {
        console.log('Entro a cargar planilla');
        const formData = new FormData();
        formData.append("ecoComProcedureId", this.form.ecoComProcedureId);
        await axios
            .post("eco_com_import_planilla", formData)
            .then(response => {
                this.sigep = response.data['eco_com_sigep'];
                this.banco = response.data['eco_com_banco'];
            //    console.log(this.sigep);
            //    console.log(this.banco);
            })
            .catch(error => {
               console.log(error);
        });
    },
    async sigepEstado() {
      const formData = new FormData();
      formData.append("ecoComProcedureId", this.form.ecoComProcedureId);
      formData.append("procedureDate", this.form.sigepgestion);
      formData.append("ecoComState", 25);
      this.loadingButton = true;
      await axios
        .post("eco_com_cambiar_estado", formData)
        .then(response => {
          console.log(response.data);
          this.cargarplanilla();
        })
        .catch(error => {
          console.log(error);
        });
      this.loadingButton = false
    },
    async sigepBanco() {
      const formData = new FormData();
      formData.append("ecoComProcedureId", this.form.ecoComProcedureId);
      formData.append("procedureDate", this.form.sigepgestion);
      formData.append("ecoComState", 24);
      this.loadingButton = true;
      await axios
        .post("eco_com_cambiar_estado", formData)
        .then(response => {
          console.log(response.data);
          this.cargarplanilla();
        })
        .catch(error => {
          console.log(error);
        });
      this.loadingButton = false
    }
  }
};
</script>
<style>
</style>
