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
    <div class="col-lg-12" v-if="showResults" >
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Resultados</h2>
          <div class="ibox-tools"></div>
        </div>
        <div class="ibox-content">
          <div class="row">
            <h2>Catidad Total de Datos del EXCEL/CSV {{ csvTotal }}</h2>
          </div>
          <div class="row">
            <h2>Importados Satisfactoriamente: {{ success }}</h2>
          </div>
          <div class="row">
            <h2>Datos que aun no fueron importados: {{notFound.length}}</h2>
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
          <div class="row">
            <h2>Afiliados que no tiene Tramite: {{ notHasEcoCom.length }}</h2>
          </div>
          <div class="row">
            <h2>Afiliados que no estan en la BASE DE DATOS de la MUSERPOL: {{ notFoundDB.length}}</h2>
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>CI</th>
                  <th>NUA</th>
                  <th>PATERNO</th>
                  <th>MATERNO</th>
                  <th>AP CASADA</th>
                  <th>p NOMBRE</th>
                  <th>s NOMBRE</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in notFoundDB" :key="index">
                  <td>{{ nf[10]}}</td>
                  <td>{{ nf[3]}}</td>
                  <td>{{ nf[6]}}</td>
                  <td>{{ nf[7]}}</td>
                  <td>{{ nf[8]}}</td>
                  <td>{{ nf[4]}}</td>
                  <td>{{ nf[5]}}</td>
                </tr>
              </tbody>
            </table>
            <!-- <table class="table table-striped table-bordered" v-if="type == 'invalidez'">
              <thead>
                <tr>
                  <th>Nro Tramite</th>
                  <th>aps_disability TRAMITE</th>
                  <th>aps_disability APS</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in notFound" :key="index">
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
            </table> -->
            <!-- <table class="table table-striped table-bordered" v-if="type == 'muerte'">
              <thead>
                <tr>
                  <th>Nro Tramite</th>
                  <th>aps_death TRAMITE</th>
                  <th>aps_death APS</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(nf, index) in notFound" :key="index">
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
            </table> -->
          </div>
          <br>
          <!-- <div class="row">
            <div class="text-center m-sm">
              <button class="btn btn-danger" type="button" @click="confirm()">
                <i class="fa fa-check-circle"></i>&nbsp;Sobreescribir Informacion
              </button>
            </div>
          </div> -->
        </div>
      </div>
    </div>
    <div id="results-import-aps"></div>
  </div>
</template>

<script>
import { flashErrors, canOperation } from "../../helper.js";
export default {
  props: ["permissions"],
  data() {
    return {
      loadingButton: false,
      found: 0,
      success: 0,
      notHasEcoCom: 0,
      csvTotal: 0,
      notFound: [],
      notFoundDB: [],
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
          this.success = response.data.success;
          this.notFound = response.data.notFound;
          this.notFoundDB = response.data.notFoundDB;
          this.csvTotal = response.data.csvTotal;
          this.notHasEcoCom = response.data.notHasEcoCom;
          this.type = type;
        })
        .catch(error => {
          // flashErrors(
          //   "Error al procesar la observacion: ",
          //   error.response.data
          // );
          console.log(error);
        });
      this.showResults = true;
      this.loadingButton = false
      this.refresh = false
      this.$scrollTo("#results-import-aps");
    }
  }
};
</script>

<style>
</style>
