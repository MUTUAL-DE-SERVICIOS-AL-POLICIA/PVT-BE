<template>
  <div>
    <button
      @click="showModal()"
      class="btn btn-primary"
    >
      <!-- :disabled="!can('amortize_economic_complement')" -->
      <i class="fa fa-dollar"></i> Amortizar
    </button>
    <modal name="amortization-modal" class="p-lg" height="auto">
      <div cass="ibox-title">
        <h1>Registrar Amortización</h1>
      </div>
      <div class="ibox-content">
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
    async save() {
      if (!this.can("amortize_economic_complement", this.permissions)) {
        return;
      }
      this.loadingButton = true;
      this.form.id = this.ecoCom.id;
      this.form.amount = parseMoney(this.ecoCom.discount_amount);
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
    }
  }
};
</script>

<style>
</style>
