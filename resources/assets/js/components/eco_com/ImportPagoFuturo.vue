<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Calcular Pago a Futuro</h2>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <label>Gestion</label>
          <select v-model="form.ecoComProcedureId" :disabled="loadingButton">
            <option v-for="r in ecoComProcedures" :value="r.id" :key="r.id">{{r.full_name}}</option>
          </select>
          <!--
          <input class="form-control" type="file" id="file-upload-pago-futuro" :disabled="loadingButton" />
          -->
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
            {{ loadingButton ? 'Procesando...' : 'Calcular' }}
          </button>
          <!--
          <button
            type="button"
            class="btn btn-primary"
            @click="refreshData()"
            :disabled="loadingButton"
            title="verificar nuevamente"
          >
          
            <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
            <i v-else class="fa fa-refresh"></i>
            -->
            &nbsp;
            {{ loadingButton ? 'Procesando...' : '' }}
          <!--
          </button>
          -->
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
            <h2>Total Casos de Descuentos en Proceso: {{ import_eco_com }}</h2>
            <h2>Total de Aportes registrados en Proceso: {{ import_contribution }}</h2>
          </div>
          <br>
          <!-- 
          <div class="row">
            <div class="text-center m-sm">
              <button class="btn btn-danger" type="button" @click="confirm()">
                <i class="fa fa-check-circle"></i>&nbsp;Sobreescribir Informacion
              </button>
            </div>
          </div>-->
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
      found: 0,
      found2: 0,
      import_eco_com: 0,
      import_contribution: 0,
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
    // async confirm(){
    //   this.override = true;
    //   await this.$swal({
    //     title: "¿Está seguro de sobreescribir la informacion?",
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#59B75C",
    //     cancelButtonColor: "#EC4758",
    //     confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
    //     cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
    //     showLoaderOnConfirm: true,
    //     preConfirm: () => {
    //       return axios
    //         .post("eco_com_import_rents_aps", {override: this.override})
    //         .then(response => {
    //           if (!response.data) {
    //             throw new Error(response.errors)
    //           }
    //           return response.status;
    //         })
    //         .catch(error => {
    //           this.$swal.showValidationError(
    //             `Solicitud fallida: ${error.response.data.errors}`
    //           );
    //         });
    //     },
    //     allowOutsideClick: () => !this.$swal.isLoading()
    //   }).then(result => {
    //     if (result.value) {
    //       this.$swal({
    //         type: "success",
    //         title: "Actualizacion realizada correctamente.",
    //         showConfirmButton: false,
    //         timer: 1000
    //       });
    //     }
    //   });
    // },
    async refreshData() {
      this.refresh = true;
      this.sendForm()
    },
    async sendForm() {
      this.showResults = false;
      this.override = false;
      // const fileInput = document.querySelector("#file-upload-pago-futuro");
      const formData = new FormData();
      // formData.append("image", fileInput.files[0]);
      // formData.append("override", this.override);
      // formData.append("refresh", this.refresh);
      formData.append("ecoComProcedureId", this.form.ecoComProcedureId);
      this.loadingButton = true;
      await axios
        .post("eco_com_import_pago_futuro", formData)
        .then(response => {
          // console.log(response.data);
          // this.found = response.data.found;
          // this.found2 = response.data.found2;
          // this.fails = response.data.not_found;
          this.import_contribution = response.data.import_contribution;
          this.import_eco_com = response.data.import_eco_com;
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
