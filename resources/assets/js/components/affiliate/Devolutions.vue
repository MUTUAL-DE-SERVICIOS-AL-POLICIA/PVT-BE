<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-content">
          <div class="alert alert-warning" >
            Realice el <b>registro del depósito</b> y la creción del <b>compromiso de pago</b>, mediante el software <b>Seguimiento de Trámites Integral (STI)</b>.
          </div>
        </div>
        <div class="ibox-title">
          <h2 class="pull-left">Devoluciones</h2>
          <div class="ibox-tools">
            <div v-if="devolution">
               
              <!-- <button :disabled="devolution.balance==0"  class="btn btn-info" @click="deposito()">
                <i class="fa fa-dollar"></i> <b>Registrar Deposito</b>
              </button> -->
              <button  class="btn btn-primary" @click="printCertification()">
                <i class="fa fa-print"></i> Imprimir Certificacion
              </button>
              <button :disabled="devolution.hasPaymentCommitment || selectedDues.length == 0" class="btn btn-primary" @click="printPaymentCommitment()">
                <i class="fa fa-print"></i> Imprimir Compromiso
              </button>
              <!-- <button class="btn btn-primary" data-toggle="tooltip" title="Crear compromiso de Pago" @click="createPaymentCommitment()">
                <i class="fa fa-plus"></i> {{ devolution.has_payment_commitment ? 'Editar':'Crear'}} compromiso de Pago
              </button> -->
            </div>
          </div>
        </div>
        <div class="ibox-content">
          <div v-for="devolution in devolutions" :key="devolution.id">
            <!-- <h2>Por: {{devolution.observation_type.name}}</h2> -->
            <table class="table table-striped table-hover table-bordered" v-if='devolution.dues.length'>
              <thead>
                <tr>
                  <th></th>
                  <th>Nro.</th>
                  <th>GESTIÓN</th>
                  <th>MONTO ADEUDADO</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(d, index) in devolution.dues" :key="index">
                  <td><input type="checkbox" @click="selectDue(d.id)"></td>
                  <!-- <td>{{index + 1}}</td> -->
                   <td>{{  d.correlative }}</td>
                  <td>{{ d.eco_com_procedure_name }}</td>
                  <td>{{ d.amount | currency }}</td>
                </tr>
              </tbody>
            </table>
            <table style="width:35%">
              <tr>
                <td>Total deuda: </td>
                <td>
                  <strong class="text-xxs">{{ devolution.total | currency }}</strong>
                </td>
              </tr>
              <tr>
                <td>Semestre de inicio de pago: </td>
                <td>
                  <strong class="text-xxs">{{  devolution.start_eco_com_procedure }}</strong>
                </td>
              </tr>
              <tr>
                <td>Porcentaje de pago: </td>
                <td>
                  <strong>{{ devolution.percentage ? `${devolution.percentage}%` : '0%' }}</strong>
                </td>
              </tr>
              <tr>
                <td>Tiene compromiso de pago: </td>
                <td>
                  <strong>{{ devolution.has_payment_commitment ? 'Si' : 'No'}}</strong>
                </td>
              </tr>
            </table>
            <br>
            <!-- <button
                class="btn btn-primary"
                @click="confirmTotalDeuda()"
                data-toggle="tooltip"
                title="Actualizar Total Deuda"
               >
                <i class="fa fa-refresh"></i>Total Deuda
              </button> -->
              <!-- <button
                class="btn btn-primary"
                @click="confirmTotalDeudaPendiente()"
                data-toggle="tooltip"
                title="Actualizar Total Deuda Pendiente"
               >
                <i class="fa fa-refresh"></i>Total Deuda Pendiente
              </button> -->
          </div>
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
    <modal name="deposito-modal" class="p-lg mx-10 px-10" height="auto">
    <div class="col-md-12">
      <div class="text-center m-sm">
      <h1 class="mx-10"><b> Registrar Deposito</b></h1>
      <hr style="border:2px solid #ddd">
      <br>
      </div>
    </div>
      <div class="row col-xs-offset-2" >
        <div class="form-group">
          <label class="col-sm-4 control-label">Nro de Comprobante</label>
            <div class="col-sm-6">
              <input style="text-align:right"
                type="text"
                class="form-control"
                name="deposit_number"
                v-model="ecoCom.deposit_number"
              >
            </div>
        </div>
      </div>
        <br>
      <div class="row col-xs-offset-2" >
        <div class="form-group">
          <label class="col-sm-4 control-label">Fecha de Deposito</label>
            <div class="col-sm-6">
              <input style="text-align:right"
                type="text"
                v-date
                class="form-control"
                name="payment_date"
                v-model="ecoCom.payment_date"
              >
            </div>
        </div>
      </div>
      <br>
      <div class="row col-xs-offset-2" >
        <div class="form-group">
          <label class="col-sm-4 control-label">Monto</label>
            <div class="col-sm-6">
              <input
                type="text"
                v-money
                class="form-control"
                
                name="payment_amount"
                v-model="ecoCom.payment_amount"
              >
            </div>
        </div>
      </div>
      <br>
      <div class="col-md-12">
      <div class="text-center m-sm">
      <button
        class="btn btn-danger"
        type="button"
        @click="$modal.hide('deposito-modal')"
      >
        <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
        <span class="bold">Cancelar</span>
      </button>
      <button
        type="button"
        class="btn btn-primary"
        @click="confirmSaved()"
        :disabled="loadingButton"
      >
      <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
      <i v-else class="fa fa-save"></i>
      &nbsp;
      {{ loadingButton ? 'Guardando...' : 'Guardar' }}
      </button>
      </div>
      </div>
    </modal>

  </div>
</template>
<script>
import { parseMoney, flashErrors, canOperation } from "../../helper.js";
import { mapState, mapMutations } from "vuex";

export default {
  props: ["affiliate", "permissions", 'ecoComProcedures'],
  data() {
    return {
      selectedDues: [],
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
      dues: [],
      loadingButton: false
    };
  },

  computed: {
    ecoCom() {
      return this.$store.state.ecoComForm.ecoCom;
    }
  },

  mounted() {
    this.getDevolutions();
  },
  methods: {
    selectDue(due) {
      if (this.selectedDues.includes(due)) {
        this.selectedDues = this.selectedDues.filter(function(o) {return o != due;});
      } else {
        this.selectedDues.push(due)
      }
    },
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

    deposito() {
      this.ecoCom.payment_amount=this.devolution.balance;
      this.$modal.show("deposito-modal");
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
    async getDevolutions() {
      await axios
        .get(`/affiliate_get_devolutions/${this.affiliate.id}`)
        .then(response => {
          console.log(response.data)
          this.devolutions = response.data.devolutions;
          // this.devolution = response.data.devolution;
          // this.dues = response.data.dues;
          // if (this.devolution) {
          //     this.form.discountType = 'total'
          //     this.form.start_eco_com_procedure_id = this.devolution.start_eco_com_procedure_id;
          //     if (this.devolution.percentage > 0) {
          //       this.form.discountType =  'percentage';
          //       this.form.percentage =  this.devolution.percentage;
          //     }
          // }
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
          method: "POST",
          url: `/affiliate/${this.affiliate.id}/print/devolution_payment_commitment`,
          responseType: "arraybuffer",
          data: {
            dues:this.selectedDues
          }
        });
        const pdfBlob = new Blob([res.data], { type: "application/pdf" });
        printJS(URL.createObjectURL(pdfBlob));
        this.loading = false;
      } catch (error) {
        if (error.response) {
          const statusCode = error.response.status;
          let errorMessage = "Ocurrió un error al generar el documento";
          try {
            const jsonString = new TextDecoder().decode(new Uint8Array(error.response.data));
            const jsonResponse = JSON.parse(jsonString);
            if (jsonResponse.error) {
              errorMessage = jsonResponse.error;
            }
          } catch (e) {
            console.error("Error al convertir la respuesta a JSON", e);
          }

          flashErrors("Error: ", [errorMessage]);
        } else {
          flashErrors("Error: ", ["Ocurrió un error al generar el documento"]);
        }

        this.loading = false;
      }
    },
    async confirmSaved(){
        await this.$swal({
        title: "¿Está seguro de registrar?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#59B75C",
        cancelButtonColor: "#EC4758",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return this.saved().catch(error => {
            this.$swal.showValidationError(
              `Devolucion fallida: ${error.response.data.errors}`
            );
          });
        },
      });
    },
    async saved(){
      this.loadingButton = true;
      this.form.id = this.ecoCom.id;
      this.form.payment_amount = parseMoney(this.ecoCom.payment_amount);
      this.form.deposit_number = this.ecoCom.deposit_number;
      this.form.payment_date = this.ecoCom.payment_date;
      await axios
        .patch(`/eco_com_save_deposito`, this.form)
        .then(response => {
          this.$store.commit("ecoComForm/setEcoCom", response.data);
          this.$modal.hide("deposito-modal");
          this.getDevolutions();
          flash("Amortizacion realizada con exito.");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
      this.loadingButton = false;
    },
    async confirmTotalDeuda(){
      await this.$swal({
        title: "¿Está seguro de Actualizar Total Deuda?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#59B75C",
        cancelButtonColor: "#EC4758",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return this.totalDeuda().catch(error => {
            this.$swal.showValidationError(
              `Actualizacion fallida: ${error.response.data.errors}`
            );
          });
        },
      });
    },
    async totalDeuda(){
      await axios
        .patch(`/affiliate_devolucion_total_deuda`, this.form)
        .then(response => {
          this.$store.commit("ecoComForm/setEcoCom", response.data);
          this.getDevolutions();
          flash("Se actualizo Total Deuda.");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
    async confirmTotalDeudaPendiente(){
      await this.$swal({
        title: "¿Está seguro de Actualizar Total Deuda Pendiente?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#59B75C",
        cancelButtonColor: "#EC4758",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return this.totalDeudaPendiente().catch(error => {
            this.$swal.showValidationError(
              `Actualizacion fallida: ${error.response.data.errors}`
            );
          });
        },
      });
    },
    async totalDeudaPendiente(){
      await axios
        .patch(`/affiliate_devolucion_total_deuda_pendiente`, this.form)
        .then(response => {
          this.$store.commit("ecoComForm/setEcoCom", response.data);
          this.getDevolutions();
          flash("Se actualizo Total Deuda Pendiente.");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    }
  }
};
</script>