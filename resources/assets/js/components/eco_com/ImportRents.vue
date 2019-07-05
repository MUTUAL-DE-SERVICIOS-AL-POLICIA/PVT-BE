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
            <h2>Importados Satisfactoriamente: {{ found }}</h2>
          </div>
          <br>
          <div class="row">
            <h2>Datos que no se encontraron: {{notFound.length}}</h2>
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Nro Tramite</th>
                  <th>CI Beneficiario</th>
                  <th>Paterno Beneficiario</th>
                  <th>Materno Beneficiario</th>
                  <th>Primer Nombre Beneficiario</th>
                  <th>Segundo Nombre Beneficiario</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in notFound" :key="index" >
                  <td><a :href="`/eco_com/${nf.id}`">{{ nf.code }}</a></td>
                  <td>{{ nf.eco_com_beneficiary.identity_card }}</td>
                  <td>{{ nf.eco_com_beneficiary.last_name }}</td>
                  <td>{{ nf.eco_com_beneficiary.mothers_last_name }}</td>
                  <td>{{ nf.eco_com_beneficiary.first_name }}</td>
                  <td>{{ nf.eco_com_beneficiary.second_name }}</td>
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
      showResults: false,
      refresh: false,
    };
  },
  methods: {
    async refreshData() {
      this.refresh = true;
      this.sendForm()
    },
    async sendForm() {
      this.showResults = false;
      const fileInput = document.querySelector("#file-upload");
      const formData = new FormData();
      formData.append("image", fileInput.files[0]);
      formData.append("refresh", this.refresh);
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
      this.loadingButton = false;
      this.refresh = false
    }
  }
};
</script>

<style>
</style>
