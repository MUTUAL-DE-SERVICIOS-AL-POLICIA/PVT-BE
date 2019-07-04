<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Importar Senasir</h2>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <input class="form-control" type="file" id="file-upload" :disabled="loadingButton"/>
          <br>
          <button
            type="button"
            class="btn btn-primary"
            @click="sendForm()"
            :disabled="loadingButton"
          >
            <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
            <i v-else class="fa fa-save"></i>
            &nbsp;
            {{ loadingButton ? 'Procesando...' : 'Subir Archivo' }}
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
            <h2>Importados Satisfactoriamente: {{ found }}</h2>
          </div>
          <br>
          <div class="row">
            <h2>Datos que no se encontraron: {{notFound.length}}</h2>
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>CI</th>
                  <th>Paterno</th>
                  <th>Materno</th>
                  <th>Primer Nombre</th>
                  <th>Segundo Nombre</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in notFound" :key="index" >
                  <td>{{ nf.ci }}</td>
                  <td>{{ nf.paterno }}</td>
                  <td>{{ nf.materno }}</td>
                  <td>{{ nf.p_nombre }}</td>
                  <td>{{ nf.s_nombre }}</td>
                </tr>
              </tbody>
            </table>
          </div>
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
      notFound: [],
      showResults: false
    };
  },
  methods: {
    async sendForm() {
      this.showResults = false;
      const fileInput = document.querySelector("#file-upload");
      const formData = new FormData();
      formData.append("image", fileInput.files[0]);
      this.loadingButton = true;
      await axios
        .post("eco_com_import_rents", formData)
        .then(response => {
          console.log(response.data);
          this.found = response.data.found;
          this.notFound = response.data.not_found;
        })
        .catch(error => {
          console.log(error);
        });
      this.showResults = true;
      this.loadingButton = false
    }
  }
};
</script>

<style>
</style>
