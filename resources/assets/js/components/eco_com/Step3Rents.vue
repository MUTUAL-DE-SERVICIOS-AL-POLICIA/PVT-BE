<template>
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <legend>
          Registrar Rentas de
          <strong>{{ namePensionEntity }}</strong>
        </legend>
        <div class="row" v-if="! isSenasir">
          <div class="col-md-7 col-xs-offset-3">
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Fracción de Saldo Acumulado</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="aps_total_fsa"
                    v-model="aps_total_fsa"
                  >
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Fracción de Cotización</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="aps_total_cc"
                    v-model="aps_total_cc"
                  >
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Fracción Solidaria</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="aps_total_fs"
                    v-model="aps_total_fs"
                  >
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Renta Invalidez</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="aps_total_disability"
                    v-model="aps_total_disability"
                  >
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
          </div>
        </div>
        <div class="row" v-if="isSenasir">
          <div class="col-md-8 col-xs-offset-3">
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Total Ganado Renta ó Pensión</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="sub_total_rent"
                    v-model="sub_total_rent"
                  >
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Reintegro</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="reimbursement"
                    v-model="reimbursement"
                  >
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Renta Dignidad</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="dignity_pension"
                    v-model="dignity_pension"
                  >
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Renta Invalidez</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="aps_total_disability"
                    v-model="aps_total_disability"
                  >
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
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { mapGetters, mapMutations, mapState } from "vuex";
import {
  isPensionEntitySenasir,
  getNamePensionEntity,
  parseMoney
} from "../../helper.js";

export default {
  data() {
    return {
      aps_total_fsa: !!this.ecoCom ? this.ecoCom.aps_total_fsa : 0,
      aps_total_cc: !!this.ecoCom ? this.ecoCom.aps_total_cc : 0,
      aps_total_fs: !!this.ecoCom ? this.ecoCom.aps_total_fs : 0,

      aps_total_disability: !!this.ecoCom
        ? this.ecoCom.aps_total_disability
        : 0,
      sub_total_rent: !!this.ecoCom ? this.ecoCom.sub_total_rent : 0,
      reimbursement: !!this.ecoCom ? this.ecoCom.reimbursement : 0,
      dignity_pension: !!this.ecoCom ? this.ecoCom.dignity_pension : 0
    };
  },
  computed: {
    pensionEntityId() {
      return this.$store.state.ecoComForm.pensionEntityId;
    },
    isSenasir() {
      return isPensionEntitySenasir(this.pensionEntityId);
    },
    namePensionEntity() {
      return getNamePensionEntity(this.pensionEntityId);
    },
    totalSumFractions() {
      return (
        parseFloat(parseMoney(this.aps_total_fsa)) +
        parseFloat(parseMoney(this.aps_total_cc)) +
        parseFloat(parseMoney(this.aps_total_fs)) +
        parseFloat(parseMoney(this.aps_total_disability))
      );
    },
    totalSumSenasir() {
      return (
        parseFloat(parseMoney(this.sub_total_rent)) -
        parseFloat(parseMoney(this.reimbursement)) +
        parseFloat(parseMoney(this.dignity_pension)) +
        parseFloat(parseMoney(this.aps_total_disability))
      );
    }
  }
};
</script>

<style>
</style>
