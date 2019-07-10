<template>
  <div>
    <label>Gestion</label>
    <select v-model="form.ecoComProcedureId" :disabled="loadingButton">
      <option v-for="r in ecoComProcedures" :value="r.id" :key="r.id">{{r.full_name}}</option>
    </select>
    <br />
    <label for="override-total">Sobreescribir tramites ya calificados:</label>
    <input type="checkbox" v-model="form.overrideTotal" id="override-total" :disabled="loadingButton" />
    <br />
    <div class="col-md-12">
      <div class="text-center m-sm">
        <button class="btn btn-primary" type="button" @click="send()" :disabled="loadingButton">
          <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
          <i v-else class="fa fa-check-circle"></i>
          &nbsp;
          {{ loadingButton ? 'Calificando...' : 'Calificar' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ecoComProcedures"],
  data() {
    return {
      loadingButton: false,
      form: {
        ecoComProcedureId:
          this.ecoComProcedures.length > 0 ? this.ecoComProcedures[0].id : null
      }
    };
  },
  methods: {
    async send() {
      this.loadingButton = true;

      await axios({
        method: "post",
        url: "/eco_com_automatic_qualification",
        timeout: 300000, // Let's say you want to wait at least 180 seconds
        data: this.form
      })
        // .post("eco_com_automatic_qualification", this.form)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
      this.loadingButton = false;
    }
  }
};
</script>

<style>
</style>
