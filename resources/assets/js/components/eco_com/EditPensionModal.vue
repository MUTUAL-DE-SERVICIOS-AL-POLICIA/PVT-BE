<template>
  <div class="modal fade" id="editPensionModal" tabindex="-1" role="dialog" aria-labelledby="editPensionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body">
          <form @submit.prevent="updatePension">
            <input type="hidden" v-model="eco_com_fixed_pension.id">

            <div cass="ibox-title">
              <h2 class="pull-left col-xs-offset-1">
                Edicion de Rentas
                <strong>{{ namePensionEntity }}</strong>
              </h2>
            </div>
            <div class="ibox-content">
              <div class="row" v-if="affiliate_pension_entity_id != 5">
                <div class="col-md-10 col-xs-offset-2">
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Fracción de Saldo Acumulado</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="aps_total_fsa"
                          v-model="eco_com_fixed_pension.aps_total_fsa" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Fracción de Cotización</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="aps_total_cc"
                          v-model="eco_com_fixed_pension.aps_total_cc" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Fracción Solidaria</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="aps_total_fs"
                          v-model="eco_com_fixed_pension.aps_total_fs" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Renta Invalidez</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="aps_disability"
                          v-model="eco_com_fixed_pension.aps_disability" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Renta Muerte</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="aps_disability"
                          v-model="eco_com_fixed_pension.aps_total_death" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <div class="hr-line-dashed"></div>
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Total Fracciones</label>
                      <div class="col-sm-4">
                        <strong>
                          <animated-integer v-bind:value="totalSumFractions"></animated-integer>
                        </strong>
                      </div>
                    </div>
                  </div>
                  <div class="hr-line-dashed"></div>
                  <div class="row">
                    <label class="col-sm-4 control-label">Periodo que correponde la renta</label>
                    <select class="col-sm-6" name="Periodo que correponde la renta"
                      v-model="eco_com_fixed_pension.eco_com_procedure_id">
                      <option v-for="p in procedures" :value="p.id" :key="p.id">{{ p.semester + p.year.split('-')[0] +'('+ p.rent_month+')' }}</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row" v-if="affiliate_pension_entity_id == 5">
                <div class="col-md-10 col-xs-offset-2">
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Total Ganado Renta ó Pensión</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="sub_total_rent"
                          v-model="eco_com_fixed_pension.sub_total_rent" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Reintegro</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="reimbursement"
                          v-model="eco_com_fixed_pension.reimbursement" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-minus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Renta Dignidad</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="dignity_pension"
                          v-model="eco_com_fixed_pension.dignity_pension" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-minus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Renta Invalidez</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-money name="aps_disability"
                          v-model="eco_com_fixed_pension.aps_disability" />
                      </div>
                      <div class="col-sm-2">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                      </div>
                    </div>
                  </div>
                  <div class="hr-line-dashed"></div>

                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Total Renta ó Pensión</label>
                      <div class="col-sm-4">
                        <strong>
                          <animated-integer v-bind:value="totalSumSenasir"></animated-integer>
                        </strong>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-sm-4 control-label">Periodo que correponde la renta</label>
                    <select class="col-sm-6" name="Periodo que correponde la renta"
                      v-model="eco_com_fixed_pension.eco_com_procedure_id">
                      <option v-for="p in procedures" :value="p.id" :key="p.id">{{ p.semester + p.year.split('-')[0] +'('+ p.rent_month+')' }}</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="text-center m-sm">
                  <button class="btn btn-danger" type="button" @click="closeModal();">
                    <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
                    <span class="bold">Cancelar</span>
                  </button>
                  <button type="button" class="btn btn-primary" @click="updatePension()" :disabled="loadingButton">
                    <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
                    <i v-else class="fa fa-save"></i>
                    &nbsp;
                    {{ loadingButton ? 'Guardando...' : 'Guardar' }}
                  </button>
                </div>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Axios from 'axios';
import {
  isPensionEntitySenasir,
  getNamePensionEntity,
  parseMoney,
  canOperation,
  flashErrors
} from "../../helper.js";

export default {
  props: ["affiliate_pension_entity_id"],

  mounted() {
    this.getProcedures();
  },
  data() {
    return {
      eco_com_fixed_pension: {
        id: null,
        rent_type: '',
        aps_total_cc: null,
        // Otros campos
      },
      editing: false,
      loadingButton: false,
      procedures: []
    };
  },
  computed: {
    // ecoCom() {
    //   return this.$store.state.ecoComForm.ecoCom;
    // },
    // isSenasir() {
    //   return isPensionEntitySenasir(this.affiliate.pension_entity_id);
    // },
    namePensionEntity() {
      return getNamePensionEntity(this.affiliate_pension_entity_id);
    },
    totalSumFractions() {
      return (
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_total_fsa)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_total_cc)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_total_fs)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_total_death)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_disability))
      );
    },
    totalSumSenasir() {
      return (
        parseFloat(parseMoney(this.eco_com_fixed_pension.sub_total_rent)) -
        parseFloat(parseMoney(this.eco_com_fixed_pension.reimbursement)) -
        parseFloat(parseMoney(this.eco_com_fixed_pension.dignity_pension)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_disability))
      );
    }
  },


  methods: {
    openModal(eco_com_fixed_pension) {
      this.eco_com_fixed_pension = { ...eco_com_fixed_pension };
      $('#editPensionModal').modal('show');
    },
    closeModal() {
      $('#editPensionModal').modal('hide');
    },
    cleanMonetaryValues() {
      // Eliminar símbolos y comas de los valores monetarios
      this.eco_com_fixed_pension.aps_total_cc = parseMoney(this.eco_com_fixed_pension.aps_total_cc);
      this.eco_com_fixed_pension.aps_total_fsa = parseMoney(this.eco_com_fixed_pension.aps_total_fsa);
      this.eco_com_fixed_pension.aps_total_fs = parseMoney(this.eco_com_fixed_pension.aps_total_fs);
      this.eco_com_fixed_pension.aps_disability = parseMoney(this.eco_com_fixed_pension.aps_disability);
      this.eco_com_fixed_pension.aps_total_death = parseMoney(this.eco_com_fixed_pension.aps_total_death);
      this.eco_com_fixed_pension.sub_total_rent = parseMoney(this.eco_com_fixed_pension.sub_total_rent);
      this.eco_com_fixed_pension.reimbursement = parseMoney(this.eco_com_fixed_pension.reimbursement);
      this.eco_com_fixed_pension.dignity_pension = parseMoney(this.eco_com_fixed_pension.dignity_pension);
      
      if(this.affiliate_pension_entity_id != 5){
        this.eco_com_fixed_pension.total_rent =  parseFloat(parseMoney(this.eco_com_fixed_pension.aps_total_fsa)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_total_cc)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_total_fs)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_total_death)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_disability))
        console.log(this.eco_com_fixed_pension.total_rent )
      }        
      else{
        this.eco_com_fixed_pension.total_rent =  parseFloat(parseMoney(this.eco_com_fixed_pension.sub_total_rent)) -
        parseFloat(parseMoney(this.eco_com_fixed_pension.reimbursement)) -
        parseFloat(parseMoney(this.eco_com_fixed_pension.dignity_pension)) +
        parseFloat(parseMoney(this.eco_com_fixed_pension.aps_disability))
      }        
    },

    async updatePension() {
      // if(!this.can('update_economic_complement', this.permissions)){
      //     return;
      // }
      this.cleanMonetaryValues();
      await axios
        .patch(`/eco_com_fixed_pensions/${this.eco_com_fixed_pension.id}`, this.eco_com_fixed_pension)
        .then(response => {
          this.$store.commit("ecoComForm/setEcoCom", response.data);
          this.closeModal();
          //this.$modal.hide("")
          flash("Rentas actualizadas con exito")
        })
        .catch(error => {
          flashErrors("Error al procesar:", error.response.data.errors);
           this.closeModal();
        })
      //location.reload();
      // this.$http.update(`/eco_com_fixed_pensions/${this.eco_com_fixed_pension.id}`, this.eco_com_fixed_pension)
      //     .then(response => {
      //         this.closeModal();
      //         // Actualiza la tabla o redirige según tu lógica
      //     })
      //     .catch(error => {
      //         console.error('Error:', error);
      //     });
    },
    async getProcedures() {
      this.$scrollTo("#wrapper");
      await axios
        .get(`/eco_com_procedures_regulation`)
        .then(response => {
          this.procedures = response.data
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
  }
};
</script>
