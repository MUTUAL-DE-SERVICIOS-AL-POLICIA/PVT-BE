<template>
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <!-- <legend>
          Registrar Rentas de
          <strong>{{ namePensionEntity }}</strong>
        </legend>
        <div class="row" v-if="! isSenasir">
          <div class="col-md-8 col-xs-offset-3">
            <div class="row">
              <div class="form-group">
                <label class="col-sm-4 control-label">Fracción de Saldo Acumulado</label>
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    v-money
                    name="aps_total_fsa"
                    v-model="ecoCom.aps_total_fsa"
                  >
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
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
                    v-model="ecoCom.aps_total_cc"
                  >
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
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
                    v-model="ecoCom.aps_total_fs"
                  >
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
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
                    name="aps_disability"
                    v-model="ecoCom.aps_disability"
                  >
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
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
                    name="renta_dignidad"
                    v-model="ecoCom.renta_dignidad"
                  >
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
                    v-model="ecoCom.sub_total_rent"
                  >
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-plus" style="font-size:20px"></i>
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
                    v-model="ecoCom.reimbursement"
                  >
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-minus" style="font-size:20px"></i>
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
                    v-model="ecoCom.dignity_pension"
                  >
                </div>
                <div class="col-sm-2">
                  <i class="fa fa-minus" style="font-size:20px"></i>
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
                    name="aps_disability"
                    v-model="ecoCom.aps_disability"
                  >
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
          </div>
        </div> -->
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
    };
  },
  computed: {
    ecoCom() {
      return this.$store.state.ecoComForm.ecoCom;
    },
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
        parseFloat(parseMoney(this.ecoCom.aps_total_fsa)) +
        parseFloat(parseMoney(this.ecoCom.aps_total_cc)) +
        parseFloat(parseMoney(this.ecoCom.aps_total_fs)) +
        parseFloat(parseMoney(this.ecoCom.aps_disability)) +
        parseFloat(parseMoney(this.ecoCom.renta_dignidad))
      );
    },
    totalSumSenasir() {
      return (
        parseFloat(parseMoney(this.ecoCom.sub_total_rent)) -
        parseFloat(parseMoney(this.ecoCom.reimbursement)) -
        parseFloat(parseMoney(this.ecoCom.dignity_pension)) +
        parseFloat(parseMoney(this.ecoCom.aps_disability))
      );
    }
  }
};
</script>

<style>
</style>
