<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Devoluciones</h2>
          <div class="ibox-tools">
            <!-- @click="createObs()" -->
            <div v-if="devolution">
              <button  class="btn btn-primary" @click="printCertification()">
                <i class="fa fa-print"></i> Imprimir Certificacion
              </button>
              <button :disabled="! devolution.has_payment_commitment" class="btn btn-primary" @click="printPaymentCommitment()">
                <i class="fa fa-print"></i> Imprimir Compromiso
              </button>
              <button class="btn btn-primary" data-toggle="tooltip" title="Crear compromiso de Pago" @click="createPaymentCommitment()">
                <!-- :disabled="!can('create_observation_type')"
                v-if="can('read_observation_type')" -->
                <i class="fa fa-plus"></i> {{ devolution.has_payment_commitment ? 'Editar':'Crear'}} compromiso de Pago
              </button>
            </div>
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
    <modal name="create-payment-commitment-modal" class="p-sm" height="auto">
      <div class="ibox-title">
        <h1>Crea compromiso de Pago</h1>
      </div>
      <div class="row">
        <div class="col-md-12" :class="{'has-error': errors.has('type_discount')}">
          <div class="col-md-3">
            <label class="control-label">Tipo De descuento</label>
          </div>
          <div class="col-md-9">
            <input type="radio" id="total_discount" name="type_discount" value="total" v-model="form.discountType" v-validate.initial="'required'">
            <label for="total_discount" class="pointer">Por el Total de la Deuda Pendiente</label>
            <br>
            <input type="radio" id="percentage_discount" name="type_discount" value="percentage" v-model="form.discountType" v-validate.initial="'required'">
            <label for="percentage_discount" class="pointer">Porcentaje para Amortizar</label>
            <br>
            <select
              class="form-control m-b"
              name="percentage"
              v-model="form.percentage"
              v-if="form.discountType == 'percentage'"
            >
              <option v-for="p in percentages" :value="p.percentage" :key="p.percentage">{{ p.name}}</option>
            </select>
            <i v-show="errors.has('percentage')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('percentage')"
              class="text-danger"
            >{{ errors.first('percentage') }}</span>
            <br>
          </div>
          <i v-show="errors.has('type_discount')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('type_discount')"
              class="text-danger"
            >{{ errors.first('type_discount') }}</span>
        </div>
        <div class="col-md-12" :class="{'has-error': errors.has('type_discount')}">
          <div class="col-md-3">
            <label class="control-label">Gestion inicio pago deuda</label>
          </div>
          <div class="col-md-9">
            <select
              class="form-control m-b"
              name="start_eco_com_procedure_id"
              v-model="form.start_eco_com_procedure_id"
            >
              <option v-for="p in ecoComProcedures" :value="p.id" :key="p.id">{{ p.full_name}}</option>
            </select>
          </div>
          <i v-show="errors.has('start_eco_com_procedure_id')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('start_eco_com_procedure_id')"
              class="text-danger"
            >{{ errors.first('start_eco_com_procedure_id') }}</span>
        </div>
        <div class="col-md-12">
          <div class="text-center m-sm">
            <button class="btn btn-danger" type="button" @click="$modal.hide('create-payment-commitment-modal')">
              <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
              <span class="bold">Cancelar</span>
            </button>
            <button class="btn btn-primary" type="button" @click="savePaymentCommitment()">
              <i class="fa fa-check-circle"></i>&nbsp;Guardar
            </button>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
import { flashErrors, canOperation } from "../../helper.js";
export default {
  props: ["affiliate", "permissions", 'ecoComProcedures'],
  data() {
    return {
      form: {
        affiliate_id: this.affiliate.id,
        percentage: null,
        discountType: null,
      },
      percentages:[
        {
          percentage: '0.50',
          name: "50%"
        },
        {
          percentage: '0.60',
          name: "60%"
        },
        {
          percentage: '0.70',
          name: "70%"
        },
        {
          percentage: '0.80',
          name: "80%"
        },
        {
          percentage: '0.90',
          name: "90%"
        },
        {
          percentage: "1.00",
          name: "100%"
        },
      ],
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
          if (this.devolution) {
            // if (this.devolution.has_payment_commitment) {
              this.form.discountType = 'total'
              this.form.start_eco_com_procedure_id = this.devolution.start_eco_com_procedure_id;
              if (this.devolution.percentage > 0) {
                this.form.discountType =  'percentage';
                this.form.percentage =  this.devolution.percentage;
              }
            // }
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    createPaymentCommitment(){
      this.$modal.show("create-payment-commitment-modal");
    },
    async savePaymentCommitment(){
      await axios.post('/affiliate_devolution_payment_commitment', this.form)
      .then(response => {
        console.log(response)
        this.$modal.hide("create-payment-commitment-modal");
        this.devolution = response.data.devolution;
      }).catch(error => {
        flashErrors("Error: ", error.response.data.errors);
      })
    },
    async printPaymentCommitment(){
      try {
        let res = await axios({
          method: "GET",
          url: `/affiliate/${this.affiliate.id}/print/devolution_payment_commitment`,
          responseType: "arraybuffer"
        });
        const pdfBlob = new Blob([res.data], { type: "application/pdf" });
        printJS(URL.createObjectURL(pdfBlob));
        this.loading = false;
      } catch (error) {
        this.loading = false;
        flashErrors("Error: ", ["Ocurrio un error al generar el documento"]);
      }
    }
  }
};
</script>

<style>
</style>
