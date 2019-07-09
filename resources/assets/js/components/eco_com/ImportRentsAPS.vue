<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox" key="vejez">
        <div class="ibox-title">
          <h2 class="pull-left">Importar APS VEJEZ</h2>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <input class="form-control" type="file" id="file-upload-aps-vejez" :disabled="loadingButton" />
          <br />
          <button
            type="button"
            class="btn btn-primary"
            @click="sendForm('vejez')"
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
            @click="refreshData('vejez')"
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
      <div class="ibox" key="invalidez">
        <div class="ibox-title">
          <h2 class="pull-left">Importar APS INVALIDEZ</h2>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <input class="form-control" type="file" id="file-upload-aps-invalidez" :disabled="loadingButton" />
          <br />
          <button
            type="button"
            class="btn btn-primary"
            @click="sendForm('invalidez')"
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
            @click="refreshData('invalidez')"
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
      <div class="ibox" key="muerte">
        <div class="ibox-title">
          <h2 class="pull-left">Importar APS MUERTE</h2>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <input class="form-control" type="file" id="file-upload-aps-muerte" :disabled="loadingButton" />
          <br />
          <button
            type="button"
            class="btn btn-primary"
            @click="sendForm('muerte')"
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
            @click="refreshData('muerte')"
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
          <br />
          <div class="row">
            <h2>Datos Distintos: {{fails.length}}</h2>
            <table class="table table-striped table-bordered" v-if="type == 'vejez'">
              <thead>
                <tr>
                  <th>Nro Tramite</th>
                  <th>aps_total_cc TRAMITE</th>
                  <th>aps_total_cc APS</th>
                  <th>aps_total_fsa TRAMITE</th>
                  <th>aps_total_fsa APS</th>
                  <th>aps_total_fs TRAMITE</th>
                  <th>aps_total_fs APS</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in fails" :key="index">
                  <td>
                    <a :href="`/eco_com/${nf.id}`">{{ nf.code }}</a>
                  </td>
                  <td
                    :class="{'danger':  nf.aps_total_cc != nf.aps_total_cc_aps}"
                  >{{ nf.aps_total_cc}}</td>
                  <td
                    :class="{'danger':  nf.aps_total_cc != nf.aps_total_cc_aps}"
                  >{{ nf.aps_total_cc_aps}}</td>

                  <td
                    :class="{'danger':  nf.aps_total_fsa != nf.aps_total_fsa_aps}"
                  >{{ nf.aps_total_fsa}}</td>
                  <td
                    :class="{'danger':  nf.aps_total_fsa != nf.aps_total_fsa_aps}"
                  >{{ nf.aps_total_fsa_aps}}</td>

                  <td
                    :class="{'danger':  nf.aps_total_fs != nf.aps_total_fs_aps}"
                  >{{ nf.aps_total_fs}}</td>
                  <td
                    :class="{'danger':  nf.aps_total_fs != nf.aps_total_fs_aps}"
                  >{{ nf.aps_total_fs_aps}}</td>
                </tr>
              </tbody>
            </table>
            <table class="table table-striped table-bordered" v-if="type == 'invalidez'">
              <thead>
                <tr>
                  <th>Nro Tramite</th>
                  <th>aps_disability TRAMITE</th>
                  <th>aps_disability APS</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in fails" :key="index">
                  <td>
                    <a :href="`/eco_com/${nf.id}`">{{ nf.code }}</a>
                  </td>
                  <td
                    :class="{'danger':  nf.aps_disability != nf.aps_disability_aps}"
                  >{{ nf.aps_disability}}</td>
                  <td
                    :class="{'danger':  nf.aps_disability != nf.aps_disability_aps}"
                  >{{ nf.aps_disability_aps}}</td>
                </tr>
              </tbody>
            </table>
            <table class="table table-striped table-bordered" v-if="type == 'muerte'">
              <thead>
                <tr>
                  <th>Nro Tramite</th>
                  <th>aps_death TRAMITE</th>
                  <th>aps_death APS</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in fails" :key="index">
                  <td>
                    <a :href="`/eco_com/${nf.id}`">{{ nf.code }}</a>
                  </td>
                  <td
                    :class="{'danger':  nf.aps_total_death != nf.aps_total_death_aps}"
                  >{{ nf.aps_total_death}}</td>
                  <td
                    :class="{'danger':  nf.aps_total_death != nf.aps_total_death_aps}"
                  >{{ nf.aps_total_death_aps}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <br>
          <div class="row">
            <div class="text-center m-sm">
              <button class="btn btn-danger" type="button" @click="confirm()">
                <i class="fa fa-check-circle"></i>&nbsp;Sobreescribir Informacion
              </button>
            </div>
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
      fails: [],
      showResults: false,
      refresh: false,
      override: false,
      type:null,
    };
  },
  methods: {
    async confirm(){
      this.override = true;
      await this.$swal({
        title: "¿Está seguro de sobreescribir la informacion?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#59B75C",
        cancelButtonColor: "#EC4758",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return axios
            .post("eco_com_import_rents_aps", {override: this.override, type: this.type})
            .then(response => {
              if (!response.data) {
                throw new Error(response.errors)
              }
              return response.status;
            })
            .catch(error => {
              this.$swal.showValidationError(
                `Solicitud fallida: ${error.response.data.errors}`
              );
            });
        },
        allowOutsideClick: () => !this.$swal.isLoading()
      }).then(result => {
        if (result.value) {
          this.$swal({
            type: "success",
            title: "Actualizacion realizada correctamente.",
            showConfirmButton: false,
            timer: 1000
          });
        }
      });
    },
    async refreshData(type) {
      this.refresh = true;
      this.sendForm(type)
    },
    async sendForm(type) {
      this.showResults = false;
      this.override = false;
      const fileInput = document.querySelector(`#file-upload-aps-${type}`);
      const formData = new FormData();
      formData.append(type, fileInput.files[0]);
      formData.append("override", this.override);
      formData.append("refresh", this.refresh);
      formData.append("type", type);
      this.loadingButton = true;
      await axios
        .post("eco_com_import_rents_aps", formData)
        .then(response => {
          console.log(response.data);
          // this.found = response.data.found;
          this.fails = response.data.fails;
          this.type = type;
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
