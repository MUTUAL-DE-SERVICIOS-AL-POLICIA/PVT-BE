<template>
  <div class="modal fade" id="editPensionModal" tabindex="-1" role="dialog" aria-labelledby="editPensionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body">
          <form @submit.prevent="savePension">
            <input type="hidden" v-model="eco_com_fixed_pension.id">

            <div cass="ibox-title">
              <h2 class="pull-left col-xs-offset-1">
                Edicion de Rentas
                <strong>{{ namePensionEntity }}</strong>
              </h2>
            </div>
            <div class="ibox-content">
              <div class="row">
                <div class="col-md-10 col-xs-offset-1">
                  <template v-for="input in inputs">
                    <div class="row" style="margin-bottom: 5px;" v-if="input.show">
                      <div class="form-group">
                        <label class="col-sm-6 control-label">{{ input.label }}</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" v-money :name="input.name"
                            v-model="eco_com_fixed_pension[input.name]" />
                        </div>
                        <div class="col-sm-2">
                          <i class="fa" :class="'fa-' + input.icon" style="font-size:20px"></i>
                        </div>
                      </div>
                    </div>
                  </template>
                  <div class="hr-line-dashed"></div>
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">{{ modal.totalLabel }}</label>
                      <div class="col-sm-4">
                        <strong>
                          <animated-integer v-bind:value="modal.totalValue"></animated-integer>
                        </strong>
                      </div>
                    </div>
                  </div>
                  <div class="hr-line-dashed"></div>
                  <div class="row">
                    <label class="col-sm-6 control-label">Periodo que correponde la renta</label>
                    <select class="col-sm-6" name="Periodo que correponde la renta"
                      v-model="eco_com_fixed_pension.eco_com_procedure_id">
                      <option v-for="p in procedures" :value="p.id" :key="p.id">{{ p.semester + p.year.split('-')[0]
                        + '(' + p.rent_month + ')' }}</option>
                    </select>
                  </div>
                  <div class="row">
                    <label class="col-sm-6 control-label">Periodo de Sueldo Base</label>
                    <select class="col-sm-6" name="Periodo que correponde la renta"
                      v-model="eco_com_fixed_pension.base_wage_id">
                      <option v-for="bw in baseWages" :value="bw.id" :key="'baseWage'+bw.id">{{ bw.month_year | year }} - {{ bw.amount | currency }}</option>
                    </select>
                  </div>
                  <div class="row">
                    <label class="col-sm-6 control-label">Periodo de Promedio y Limite Referencial ({{ type }})</label>
                    <select class="col-sm-6" name="Periodo que correponde la renta"
                      v-model="eco_com_fixed_pension.eco_com_rent_id">
                      <option v-for="r in ecoComRents" :value="r.id" :key="'baseWage'+r.id">{{ r.year | year }} - {{ r.semester }}</option>
                    </select>
                  </div>
                  <div class="row">
                    <template v-if="eco_com_fixed_pension.eco_com_rent_id">
                      <p class="col-sm-6 control-label">Promedio {{ ecoComRents.find(r => r.id == eco_com_fixed_pension.eco_com_rent_id).average | currency}}</p>
                      <p class="col-sm-6 control-label">Limite Referencial {{ ecoComRents.find(r => r.id == eco_com_fixed_pension.eco_com_rent_id).referential_limit | currency}}</p>
                    </template>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="text-center m-sm">
                  <button class="btn btn-danger" type="button" @click="closeModal();">
                    <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
                    <span class="bold">Cancelar</span>
                  </button>
                  <button type="button" class="btn btn-primary" @click="savePension()" :disabled="loadingButton">
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
  props: ["affiliate_pension_entity_id", "affiliate_id"],

  async mounted() {
    await this.getProcedures();
    await this.createFixedRent();
  },
  data() {
    return {
      eco_com_fixed_pension: {
        id: null,
        aps_total_cc: null,
        aps_total_fsa: null,
        aps_total_fs: null,
        aps_disability: null,
        aps_total_death: null,
        sub_total_rent: null,
        reimbursement: null,
        dignity_pension: null,
        base_wage_id: null,
        eco_com_rent_id: null,
      },
      loadingButton: false,
      procedures: [],
      baseWages: [],
      ecoComRents: [],
      type: '',
    };
  },

  computed: {
    isNew() {
      return this.eco_com_fixed_pension.id == null;
    },
    inputs() {
      return [
        { name: 'aps_total_fsa', label: 'Fracción de Saldo Acumulado', icon: 'plus', show: !this.isSenasir, },
        { name: 'aps_total_cc', label: 'Fracción de Cotización', icon: 'plus', show: !this.isSenasir, },
        { name: 'aps_total_fs', label: 'Fracción Solidaria', icon: 'plus', show: !this.isSenasir, },
        { name: 'aps_disability', label: 'Renta Invalidez', icon: 'plus', show: !this.isSenasir, },
        { name: 'aps_total_death', label: 'Renta Muerte', icon: 'plus', show: !this.isSenasir, },
        { name: 'sub_total_rent', label: 'Total Ganado Renta ó Pensión', icon: 'plus', show: this.isSenasir, },
        { name: 'reimbursement', label: 'Reintegro', icon: 'minus', show: this.isSenasir, },
        { name: 'dignity_pension', label: 'Renta Dignidad', icon: 'minus', show: this.isSenasir, },
        { name: 'aps_disability', label: 'Renta Invalidez', icon: 'plus', show: this.isSenasir, },
      ];
    },
    modal() {
      return {
        totalLabel: this.isGestora
          ? "Total Renta ó Pensión"
          : "Total Fracciones",
        totalValue: this.isGestora ? this.totalSumSenasir : this.totalSumFractions
      };
    },
    isSenasir() {
      return isPensionEntitySenasir(this.affiliate_pension_entity_id);
    },
    totalValue() {
      return this.isSenasir ? this.totalSumSenasir : this.totalSumFractions;
    },
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
    openModal(eco_com_fixed_pension = null) {
      if (eco_com_fixed_pension) {
        this.eco_com_fixed_pension = { ...eco_com_fixed_pension };
      } else {
        this.eco_com_fixed_pension = {
          id: null,
          affiliate_id: this.affiliate_id,
          rent_type: 'Manual',
          aps_total_cc: null,
          aps_total_fsa: null,
          aps_total_fs: null,
          aps_disability: null,
          aps_total_death: null,
          sub_total_rent: null,
          reimbursement: null,
          dignity_pension: null,
          eco_com_procedure_id: null,
        };
      }
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

      if (this.isSenasir) {
        this.eco_com_fixed_pension.total_rent = this.totalSumSenasir;
      }
      else {
        this.eco_com_fixed_pension.total_rent = this.totalSumFractions;
      }
    },

    async savePension() {
      // if(!this.can('update_economic_complement', this.permissions)){
      //     return;
      // }
      this.cleanMonetaryValues();
      if (this.isNew) {
        console.log('Creando nueva renta');
        await axios
          .post(`/eco_com_fixed_pensions`, this.eco_com_fixed_pension)
          .then(response => {
            this.closeModal();
            flash("Rentas actualizadas con exito")
            location.reload();
          })
          .catch(error => {
            flashErrors("Error al procesar:", error.response.data.errors);
            this.closeModal();
          })
      } else {
        console.log('Editando renta existente');
        await axios
          .patch(`/eco_com_fixed_pensions/${this.eco_com_fixed_pension.id}`, this.eco_com_fixed_pension)
          .then(response => {
            this.closeModal();
            flash("Rentas actualizadas con exito")
            location.reload();
          })
          .catch(error => {
            flashErrors("Error al procesar:", error.response.data.errors);
            this.closeModal();
          })
      }
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
    async createFixedRent() {
      await axios
        .get(`/affiliate/${this.affiliate_id}/eco_com_fixed_pensions/create`)
        .then(response => {
          console.log(response.data);
          this.baseWages = response.data.base_wages;
          this.ecoComRents = response.data.eco_com_rents;
          this.type = response.data.type;
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
  }
};
</script>