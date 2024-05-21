<template>
  <div>
    <button
      @click="showModal()"
      class="btn btn-primary"
    >
      <!-- :disabled="!can('amortize_economic_complement')" -->
      <i class="fa fa-dollar"></i> Amortizar
    </button>
    <!--<button
      @click="showModald()"
      class="btn btn-primary"
    >
      <i class="fa fa-dollar"></i> Deposito
    </button>-->
    <modal name="amortization-modal" class="p-lg" height="auto">
      <div cass="ibox-title">
        <h1>Registrar Amortización</h1>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-10 col-xs-offset-2">
            <div class="form-group">
              <label class="col-sm-2 control-label">Tipo</label>
              <div class="col-sm-8">
                <select
                  class="form-control m-b"
                  name="Tipo"
                  v-model="discount_type"
                >
                  <option value="1">Reposición por fondos</option>
                  <option value="2">Reposición por juzgado</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10 col-xs-offset-2">
            <div class="form-group">
              <label class="col-sm-2 control-label">Monto</label>
              <div class="col-sm-8">
                <input
                  type="text"
                  class="form-control"
                  v-money
                  name="discount_amount"
                  v-model="ecoCom.discount_amount"
                >
                <!-- v-validate="'required'" -->
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="text-center m-sm">
              <button
                class="btn btn-danger"
                type="button"
                @click="$modal.hide('amortization-modal')"
              >
                <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
                <span class="bold">Cancelar</span>
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click="save()"
                :disabled="loadingButton"
              >
                <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
                <i v-else class="fa fa-save"></i>
                &nbsp;
                {{ loadingButton ? 'Guardando...' : 'Guardar' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </modal>

    <modal name="deposito-modal" class="p-lg mx-10 px-10" height="auto">
    <div class="col-md-12">
      <div class="text-center m-sm">
      <h1 class="mx-10"><b> Registrar Deposito</b></h1>
      <hr style="border:2px solid #ddd">
      <br/>
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
        <br/>
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
      <br/>
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
      <br/>
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
        @click="saved()"
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
import { parseMoney, canOperation, flashErrors } from "../../helper.js";
import { mapState, mapMutations } from "vuex";
export default {
  props: ["permissions"],
  data() {
    return {
      form: {},
      discount_type: null,
      loadingButton: false
    };
  },
  computed: {
    ecoCom() {
      return this.$store.state.ecoComForm.ecoCom;
    }
  },
  methods: {
    can(operation) {
      return canOperation(operation, this.permissions);
    },
    showModal() {
      // if (!this.can("amortize_economic_complement", this.permissions)) {
      //   flash("No tiene permisos para realizar la amortizacion", "error");
      //   return;
      // }
      this.$modal.show("amortization-modal");
    },
    showModald() {
      // if (!this.can("amortize_economic_complement", this.permissions)) {
      //   flash("No tiene permisos para realizar la amortizacion", "error");
      //   return;
      // }
      this.$modal.show("deposito-modal");
    },
    async save() {
      if (!this.can("amortize_economic_complement", this.permissions)) {
        flash("No se puede realizar la Amortizacion.", 'error');
        this.$modal.hide("amortization-modal");
        return;
      }
      this.loadingButton = true;
      this.form.id = this.ecoCom.id;
      this.form.amount = parseMoney(this.ecoCom.discount_amount);
      this.form.discount_type = this.discount_type
      await axios
        .patch(`/eco_com_save_amortization`, this.form)
        .then(response => {
          console.log(response);
          this.$store.commit("ecoComForm/setEcoCom", response.data);
          this.$modal.hide("amortization-modal");
          flash("Amortizacion realizada con exito.");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
      this.loadingButton = false;
    },
    
  async saved(){
  if (!this.can("amortize_economic_complement", this.permissions)) {
        flash("No se puede realizar el Deposito.", 'error');
        this.$modal.hide("deposito-modal");
        return;
      }
      console.log("entra aca")
      this.loadingButton = true;
      this.form.id = this.ecoCom.id;
      this.form.payment_amount = parseMoney(this.ecoCom.payment_amount);
      this.form.deposit_number = this.ecoCom.deposit_number;
      this.form.payment_date = this.ecoCom.payment_date;
      await axios
        .patch(`/eco_com_save_deposito`, this.form)
        
        .then(response => {
          console.log(response);
          this.$store.commit("ecoComForm/setEcoCom", response.data);
          this.$modal.hide("deposito-modal");
          flash("Amortizacion realizada con exito.");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
      this.loadingButton = false;
  }  
    
  }
};
</script>

<style>
</style>
