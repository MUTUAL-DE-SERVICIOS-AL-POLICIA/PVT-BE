<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Devoluciones</h2>
          <div class="ibox-tools">
            <!-- @click="createObs()" -->
            <button v-if="devolution" class="btn btn-primary" @click="printCertification()">
              <i class="fa fa-print"></i> Imprimir Certificacion
            </button>
            <!-- <button class="btn btn-primary" data-toggle="tooltip" title="Adicionar Observacion"> -->
              <!-- :disabled="!can('create_observation_type')"
              v-if="can('read_observation_type')"-->
              <!-- <i class="fa fa-plus"></i> Adicionar
            </button> -->
          </div>
        </div>
        <!-- v-if="can('read_observation_type')" -->
        <div class="ibox-content">
          <div v-for="devolution in devolutions" :key="devolution.id">
            <h2>Por: {{devolution.observation_type.name}}</h2>
            <table class="table table-striped table-hover table-bordered" v-if='dues.length'>
              <thead>
                <tr>
                  <th>Nro.</th>
                  <th>GESTIÓN</th>
                  <th>MONTO ADEUDADO</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(d, index) in dues" :key="index">
                  <td>{{index + 1}}</td>
                  <td>{{ d.eco_com_procedure_name }}</td>
                  <td>{{ d.amount | currency }}</td>
                </tr>
              </tbody>
            </table>
            <table>
              <tr>
                <td>Total Deuda</td>
                <td>
                  <strong>{{ devolution.total | currency }}</strong>
                </td>
              </tr>
              <tr>
                <td>Total Deuda Pendiente</td>
                <td>
                  <strong>{{ devolution.balance | currency }}</strong>
                </td>
              </tr>
            </table>
          </div>
          <!-- <div >
            <div class="alert alert-info">El afiliado no tiene deudas.</div>
          </div> -->
        </div>
        <!-- v-else -->
        <div class="ibox-content">
          <div class="alert alert-warning">No tiene permisos para ver las Devoluciones.</div>
        </div>
      </div>
    </div>
    <!-- <modal name="observation-modal" class="p-sm" height="auto">
      <div class="ibox-title">
        <h1>{{ method == 'post' ? 'Agregar' : 'Editar'}} Observacion</h1>
      </div>
      <div class="row">
        <div class="col-md-12" :class="{'has-error': errors.has('observation_type_id')}">
          <div class="col-md-3">
            <label class="control-label">Tipo</label>
          </div>
          <div class="col-md-9">
            <select
              class="form-control m-b"
              name="observation_type_id"
              v-model="form.observationTypeId"
              v-validate.initial="'required'"
              :disabled="method == 'patch'"
              v-if="method == 'post'"
            >
              <option :value="null"><me }}</option>
            </select>
            <select
              class="form-control m-b"
              name="observation_type_id"
              v-model="form.observationTypeId"
              v-validate.initial="'required'"
              :disabled="method == 'patch'"
              v-else
            >
              <option :value="null"></option>
              <option
                v-for="(o, index) in observationTypes"
                :value="o.id"
                :key="index"
              >[{{ o.type }}] {{ o.name }}</option>
            </select>
            <i v-show="errors.has('observation_type_id')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('observation_type_id')"
              class="text-danger"
            >{{ errors.first('observation_type_id') }}</span>
          </div>
        </div>
        <div class="col-md-12" :class="{'has-error': errors.has('message') }">
          <div class="col-md-3">
            <label class="control-label">Mensaje</label>
          </div>
          <div class="col-md-9">
            <textarea
              cols="30"
              rows="10"
              name="message"
              v-model.trim="form.message"
              class="form-control"
              v-validate.initial="'required|min:10|max:250'"
            ></textarea>
            <div v-show="errors.has('message')">
              <i class="fa fa-warning text-danger"></i>
              <span class="text-danger">{{ errors.first('message') }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="text-center m-sm">
            <button class="btn btn-danger" type="button" @click="$modal.hide('observation-modal')">
              <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
              <span class="bold">Cancelar</span>
            </button>
            <button class="btn btn-primary" type="button" @click="save()">
              <i class="fa fa-check-circle"></i>&nbsp;Guardar
            </button>
          </div>
        </div>
      </div>
    </modal>-->
  </div>
</template>
<script>
import { flashErrors, canOperation } from "../../helper.js";
export default {
  props: ["affiliate", "permissions"],
  data() {
    return {
      form: {
        message: null,
        observationTypeId: null,
        enabled: true
      },
      method: "post",
      devolution: {},
      devolutions: [],
      dues: []
    };
  },
  mounted() {
    this.getDevolutions();
  },
  methods: {
    can(operation) {
      return canOperation(operation, this.permissions);
    },
    getOption() {
      let method = "";
      switch (this.method) {
        case "post":
          method = "create";
          break;
        case "patch":
          method = "update";
          break;
        case "delete":
          method = "delete";
          break;
      }
      return method;
    },
    async printCertification() {
      try {
        let res = await axios({
          method: "GET",
          url: `/affiliate/${this.affiliate.id}/print/certification_devolutions`,
          responseType: "arraybuffer"
        });
        const pdfBlob = new Blob([res.data], { type: "application/pdf" });
        printJS(URL.createObjectURL(pdfBlob));
        this.loading = false;
      } catch (error) {
        this.loading = false;
        flashErrors("Error: ", ["Ocurrio un error al generar el documento"]);
      }
    },
    // createObs() {
    //   if(!this.can('create_observation_type', this.permissions)){
    //     return;
    //   }
    //   this.$modal.show("observation-modal");
    //   this.form.observationTypeId = null;
    //   this.form.message = null;
    //   this.form.enabled = false;
    //   this.method = "post";
    // },
    // async save() {
    //   await this.$validator.validateAll();
    //   if (this.$validator.errors.items.length) {
    //     return;
    //   }
    //   let option = this.getOption();
    //   this.form.affiliateId = this.affiliate.id;
    //   if (this.method == "delete") {
    //     this.form = { data: this.form };
    //   }
    //   await axios[this.method](`/affiliate_observation_${option}`, this.form)
    //     .then(response => {
    //       console.log(response);
    //       this.$modal.hide("observation-modal");
    //     })
    //     .catch(error => {
    //       flashErrors(
    //         "Error al procesar la observacion: ",
    //         error.response.data.errors
    //       );
    //       console.log(error);
    //     });
    //   await this.getDevolutions();
    // },
    async getDevolutions() {
      //   if(!this.can('read_observation_type', this.permissions)){
      //     return;
      //   }
      await axios
        .get(`/affiliate_get_devolutions/${this.affiliate.id}`)
        .then(response => {
          this.devolutions = response.data.devolutions;
          this.devolution = response.data.devolution;
          this.dues = response.data.dues;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style>
</style>
