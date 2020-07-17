<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Actualizar Pagados en Banco</h2>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <input class="form-control" type="file" id="file-upload-paid-bank" :disabled="loadingButton" />
          <br />
          <button
            type="button"
            class="btn btn-primary"
            @click="sendForm()"
            :disabled="loadingButton"
            title="Esta accion sobreescribira el archivo que se cargo anteriormente"
          >
            <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
            <i v-else class="fa fa-save"></i>
            &nbsp;
            {{ loadingButton ? 'Procesando...' : 'Subir Archivo Nuevo' }}
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="refreshData()"
            :disabled="loadingButton"
            title="verificar nuevamente"
          >
            <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
            <i v-else class="fa fa-refresh"></i>
            &nbsp;
            {{ loadingButton ? 'Procesando...' : '' }}
          </button>
        </div>
      </div>
    </div>
    <div class="col-lg-12" v-if="showResults">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Resultados</h2>
          <div class="ibox-tools"></div>
        </div>
         <div class="ibox-content">
          <div class="row">
            <h2>Trámites actualizados  : {{ found }} </h2>
          </div>
          <br />
           <div class="row">
            <h2>NUP no encontrados: {{fails.length}}</h2>
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Numero Unico de Afiliado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in fails" :key="index">
                  <td>
                   {{ nf }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <br>
        </div> 
      </div>
    </div>
  </div>
</template>

<script>
export default {
   props: ["permissions"],
  data() {
    return {
      loadingButton: false,
      found: 0,
      fails: [],
      showResults: false,
      refresh: false,
      override: false,
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
      const fileInput = document.querySelector("#file-upload-paid-bank");
      const formData = new FormData();
      formData.append("image", fileInput.files[0]);
      formData.append("override", this.override);
      formData.append("refresh", this.refresh);
      this.loadingButton = true;
      await axios
        .post("eco_com_update_paid_bank", formData)
        .then(response => {
          console.log(response.data);
          this.found = response.data.found;
          this.fails = response.data.not_found;
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
