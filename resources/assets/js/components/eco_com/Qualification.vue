<template>
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h2 class="pull-left">
          Calificacion
          <strong>{{ namePensionEntity }}</strong>
        </h2>
        <div class="ibox-tools">
          <button
            class="btn btn-primary"
            @click="edit()"
            data-toggle="tooltip"
            title="Adicionar Observacion"
          >
            <!-- :disabled="!can('create_observation_type')"
            v-if="can('read_observation_type')"-->
            <i class="fa fa-plus"></i> Editar
          </button>
        </div>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Detalle</th>
                  <th>Monto</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="! isSenasir">
                  <td>Fracción de Saldo Acumulada</td>
                  <td>{{ ecoCom.aps_total_fsa | currency}}</td>
                </tr>
                <tr v-if="! isSenasir">
                  <td>Fracción de Pensión CCM o Pago de CCM</td>
                  <td>{{ ecoCom.aps_total_cc | currency}}</td>
                </tr>
                <tr v-if="! isSenasir">
                  <td>Fracción Solidaria de Vejéz</td>
                  <td>{{ ecoCom.aps_total_fs | currency}}</td>
                </tr>
                <tr class="danger" v-if="ecoCom.aps_disability > 0">
                  <td>Prestación por Invalidéz</td>
                  <td>{{ ecoCom.aps_disability | currency }}</td>
                </tr>

                <tr v-if="isSenasir">
                  <td>Total Ganado Renta ó Pensión</td>
                  <td>{{ ecoCom.sub_total_rent | currency }}</td>
                </tr>
                <tr v-if="isSenasir">
                  <td>- Reintegro</td>
                  <td>{{ ecoCom.reimbursement | currency }}</td>
                </tr>
                <tr v-if="isSenasir">
                  <td>- Renta Dignidad</td>
                  <td>{{ ecoCom.dignity_pension | currency }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Detalle</th>
                  <th>Monto</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Total Renta ó Pensión</td>
                  <td>{{ ecoCom.total_rent | currency }}</td>
                </tr>
                <tr>
                  <td>Renta ó Pensión Pasivo Neto</td>
                  <td>{{ ecoCom.total_rent_calc | currency }}</td>
                </tr>
                <tr>
                  <td>Referente Salario del Activo</td>
                  <td>{{ ecoCom.salary_reference | currency }}</td>
                </tr>
                <tr>
                  <td>Antigüedad (Según Categoría)</td>
                  <td>{{ ecoCom.seniority | currency }}</td>
                </tr>
                <tr>
                  <td>Salario Cotizable (Salario del Activo + Antigüedad)</td>
                  <td>{{ ecoCom.salary_quotable | currency }}</td>
                </tr>
                <tr>
                  <td>Diferencia (Salario Activo - Renta Pasivo)</td>
                  <td>{{ ecoCom.difference | currency }}</td>
                </tr>
                <tr>
                  <td>Total Semestre (Diferencia * 6 meses)</td>
                  <td>{{ ecoCom.total_amoun_semester | currency }}</td>
                </tr>
                <tr>
                  <td>Factor de Complementación</td>
                  <td>{{ ecoCom.complementary_factor }}</td>
                </tr>
                <tr class="success">
                  <td>
                    <strong>Total</strong>
                  </td>
                  <td>{{ ecoCom.total | currency }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <modal name="rents-modal" class="p-sm" height="auto">
      <div cass="ibox-title">
        <h2 class="pull-left">
          Edicion de Rentas
          <strong>{{ namePensionEntity }}</strong>
        </h2>
      </div>
      <div class="ibox-content">
        <div class="row" v-if="! isSenasir">
          <div class="col-md-10 col-xs-offset-2">
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
                    :disabled="!editing"
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
                    v-model="ecoCom.aps_total_cc"
                    :disabled="!editing"
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
                    v-model="ecoCom.aps_total_fs"
                    :disabled="!editing"
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
                    v-model="ecoCom.aps_total_disability"
                    :disabled="!editing"
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
                    <!-- <animated-integer v-bind:value="totalSumFractions"></animated-integer> -->
                  </strong>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" v-if="isSenasir">
          <div class="col-md-10 col-xs-offset-2">
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
                    :disabled="!editing"
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
                    v-model="ecoCom.reimbursement"
                    :disabled="!editing"
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
                    v-model="ecoCom.dignity_pension"
                    :disabled="!editing"
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
                    v-model="ecoCom.aps_total_disability"
                    :disabled="!editing"
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
                    <!-- <animated-integer v-bind:value="totalSumSenasir"></animated-integer> -->
                  </strong>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="text-center m-sm">
            <button class="btn btn-danger" type="button" @click="cancel()">
              <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
              <span class="bold">Cancelar</span>
            </button>
            <button type="button" class="btn btn-primary" @click="save()" :disabled="loadingButton">
              <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
              <i v-else class="fa fa-save"></i>
              &nbsp;
              {{ loadingButton ? 'Guardando...' : 'Guardar' }}
            </button>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import {
  isPensionEntitySenasir,
  getNamePensionEntity,
  parseMoney
} from "../../helper.js";
export default {
  props: ["ecoComId", "affiliate"],
  data() {
    return {
      ecoCom: {},
      editing: false,
      loadingButton:false,
    };
  },
  mounted() {
    this.getEcoCom();
  },
  computed: {
    isSenasir() {
      return isPensionEntitySenasir(this.affiliate.pension_entity_id);
    },
    namePensionEntity() {
      return getNamePensionEntity(this.affiliate.pension_entity_id);
    }
  },
  methods: {
    edit() {
      this.$modal.show("rents-modal");
      this.editing = true;
    },
    cancel() {
      this.$modal.hide("rents-modal");
      this.editing = false;
    },
    async getEcoCom() {
      await axios
        .get(`/get_eco_com/${this.ecoComId}`)
        .then(response => {
          this.ecoCom = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    async save() {
      this.loadingButton = true;
      this.editing = false;
      this.ecoCom.pension_entity_id = this.affiliate.pension_entity_id;
      await axios
        .patch(`/eco_com_update_rents`, this.ecoCom)
        .then(response => {
          this.ecoCom = response.data;
        })
        .catch(error => {
          console.log(error);
        });
      this.loadingButton = false;
      this.editing = true;
    }
  }
};
</script>
