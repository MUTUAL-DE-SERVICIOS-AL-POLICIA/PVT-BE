<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox-title" id="create-eco-com-ibox">
          <h3>Creando Complemento Economico</h3>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-lg-12 mx-auto">
              <div class="col-md-2">
                <strong>Ingrese la cédula de identidad:</strong>
              </div>
              <div class="col-md-3" :class="{'has-error': errors.has('identity_card') }">
                <div class="input-group">
                  <input
                    type="text"
                    name="identity_card"
                    v-model="identityCard"
                    class="form-control"
                    v-validate="'required'"
                    @keypress.enter="searchAffiliate()"
                    :disabled="hasDoblePerception"
                  >
                  <span class="input-group-btn">
                    <button
                      class="btn"
                      type="button"
                      @click="searchAffiliate()"
                      :class="errors.has('identity_card') ? 'btn-danger' : 'btn-primary'"
                      role="button"
                    >
                      <i v-if="searching" key="searching" class="fa fa-spinner fa-pulse fa-fw"></i>
                      <i v-else key="foundnofound" class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
                <div v-show="errors.has('identity_card')">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('identity_card') }}</span>
                </div>
              </div>
              <div class="col-sm-6" v-if="hasDoblePerception">
                <!-- :class="{'has-error': errors.has('search_type_id') }" -->
                <div class="form-group" >
                  <label for="">El ci tiene doble percepcion seleccione ...: </label>
                  <select
                    class="form-control"
                    v-model="searchType"
                    name="search_type_id"
                    @change="setSearchType()"
                  >
                    <option :value="null"></option>
                    <option v-for="p in searchTypes" :value="p.id" :key="p.id">{{p.name}}</option>
                  </select>
                  <!-- <i v-show="errors.has('search_type_id')" class="fa fa-warning text-danger"></i>
                  <span
                    v-show="errors.has('search_type_id')"
                    class="text-danger"
                  >{{ errors.first('search_type_id') }}</span> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <transition
      name="show-requirements-error"
      enter-active-class="animated bounceInLeft"
      leave-active-class="animated bounceOutRight"
    >
      <div class="alert alert-warning" v-if="affiliateNoFound">
        <h2>
          No se encontro el ci
          <strong>{{ identityCard }}</strong>
        </h2>
      </div>
    </transition>
    <transition name="custom-show-affiliate-transition" enter-active-class="animated fadeInLeftBig">
      <div>
        <div class="col-lg-6">
          <div class="ibox float-e-margins" v-if="hasBeneficiary">
            <div class="ibox-title">
              <h1>Datos del Beneficiario</h1>
            </div>
            <div class="ibox-content">
              <div class="row">
                <div class="col-md-12">
                  <div>
                    <h2
                      class="no-margins"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Nombre Completo"
                    >{{ ecoComBeneficiary.full_name }}</h2>
                    <h3
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Cedula de Identidad"
                    >{{ ecoComBeneficiary.ci_with_ext }}</h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="ibox float-e-margins" v-if="affiliateFound">
            <div class="ibox-title">
              <h1>Datos del Afiliado</h1>
            </div>
            <div class="ibox-content">
              <div class="row">
                <div class="col-md-12">
                  <div>
                    <h2
                      class="no-margins"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Nombre Completo"
                    >{{ affiliate.full_name }}</h2>
                    <h3
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Cedula de Identidad"
                    >{{ affiliate.ci_with_ext }}</h3>
                    <h3
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Grado"
                    >{{ affiliate.degree_name }}</h3>
                    <h4
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Categoria"
                    >{{ affiliate.category_percentage }}</h4>
                    <h4
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Ente Gestor"
                    >{{ affiliate.pension_entity_name }}</h4>
                    <h4
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Fecha de desvinculacion"
                    >{{ affiliate.date_entry }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="custom-show-ecocom-transition" enter-active-class="animated fadeInRightBig">
      <div class v-if="affiliateFound">
        <div class="col-lg-6" v-if="!verifyValidDueDate || verifyHasDisability">
          <div class="alert alert-orange block">
            Se encontraron algunas observaciones antes de crear el tramite:
            <ul>
              <li
                class="alert-link"
                v-if="! verifyValidDueDate"
              >La cedula de identidad esta caducada o no se registro una fecha de vencimiento de la cedula de identidad.</li>
              <li
                class="alert-link"
                v-if="verifyHasDisability"
              >El tramite anterior tuvo concurrencia.</li>
            </ul>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="ibox float-e-margins">
            <div class="ibox-title">
              <h1>Ultimo Complemento Economico</h1>
            </div>
            <div class="ibox-content">
              <div class="table-responsive" v-if="ecoCom.length">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th># de tramite</th>
                      <th>Gestion</th>
                      <th>Modalidad</th>
                      <th>Estado</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="e in ecoCom" class="hover" :key="e.id" @click="rowClick(e.id)">
                      <td>{{e.code}}</td>
                      <td>{{e.semester | uppercase }}/{{e.year | year }}</td>
                      <td>{{e.eco_com_modality.shortened}}</td>
                      <td>{{e.eco_com_state.name}}</td>
                      <td>{{e.total}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <h3 v-else>No se encontro ningun tramite</h3>
            </div>
            <transition
              name="custom-button-transition"
              enter-active-class="animated fadeInUp"
              leave-active-class="animated fadeOut"
              mode="out-in"
              :duration="{ enter: 1000, leave: 200 }"
            >
              <div v-if="showButton" key="showButton">
                <a
                  :href="`/affiliate/${this.affiliate.id}/eco_com/create/${this.ecoComProcedure.id}`"
                >
                  <button class="btn btn-primary btn-lg btn-block">
                    <i class="fa fa-plus"></i>
                    Crear {{ ecoComProcedureCreateName }}
                  </button>
                </a>
              </div>
              <div v-else key="noShowButton">
                <button
                  class="btn btn-warning btn-lg btn-block"
                  :class="{'denied':!showButton}"
                  v-if="! affiliateObservationsExclude.length"
                >
                  <i class="fa fa-exclamation-triangle"></i>
                  No hay tramites pendientes para crear
                </button>
              </div>
            </transition>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="alert alert-danger block" v-if="affiliateObservationsExclude.length">
            El afiliado se encuentra observado por:
            <ul>
              <li class="alert-link" v-for="ao in affiliateObservationsExclude" :key="ao.id">{{ ao.name }}</li>
            </ul>Es necesario subsanar las observaciones. (
            <a :href="`/affiliate/${affiliate.id}`">ir al afiliado</a> )
          </div>
        </div>
        <div class="col-lg-6">
          <div class="alert alert-danger block" v-if="affiliateObservations.length">
            El afiliado se encuentra observado por:
            <ul>
              <li class="alert-link" v-for="ao in affiliateObservations" :key="ao.id">{{ ao.name }}</li>
            </ul>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { scroller } from "vue-scrollto/src/scrollTo";
import { flashErrors } from '../../helper';
export default {
  props: ["cities"],
  data() {
    return {
      editing: false,
      affiliate: {},
      ecoComBeneficiary: {},
      searchType: null,
      searchTypes: [
        {
          id: 1,
          name: "Titular"
        },
        {
          id: 2,
          name: "Beneficiario"
        }
      ],
      ecoCom: [],
      affiliateNoFound: false,
      affiliateFound: false,
      showButton: false,
      // identityCard: "5633617",
      identityCard: "1379469",
      ecoComProcedureCreateName: null,
      ecoComProcedure: {},
      searching: false,
      affiliateObservationsExclude: [],
      affiliateObservations: [],
      oneTime: true,
      hasDoblePerception: false
    };
  },
  methods: {
    rowClick(id) {
      window.location = `/eco_com/${id}`;
    },
    setSearchType() {
      this.searchAffiliate();
    },
    async searchAffiliate() {
      this.clearResults();
      await this.$validator.validateAll();
      if (this.$validator.errors.items.length) {
        return;
      }
      this.searching = true;
      await axios
        .post("/search_ajax_only_affiliate", {
          ci: this.identityCard,
          type: this.searchType,
          one_time: this.oneTime,
          has_doble_perception: this.hasDoblePerception
        })
        .then(response => {
          let data = response.data;
          if (response.data.has_doble_perception) {
            this.oneTime = false;
            this.hasDoblePerception = true;
            return;
          }
          this.hasDoblePerception = false;
          if (data.affiliate != null) {
            flash("Affiliado Encontrado");
            this.affiliate = response.data.affiliate;
            this.affiliateObservationsExclude = response.data.affiliate_observations_exclude;
            this.affiliateObservations = response.data.affiliate_observations;
            this.ecoCom = response.data.eco_com;
            this.ecoCom = this.ecoCom.reverse();
            this.ecoComBeneficiary = response.data.eco_com_beneficiary;
            this.affiliateFound = true;
            this.affiliateNoFound = false;
            this.getEcoComProcedureCreateName();
            this.oneTime = true;
            this.searchType = null;
          } else {
            flash("Affiliado NO Encontrado", "warning");
            this.affiliateNoFound = true;
            this.affiliateNoFound = true;
            this.affiliateFound = false;
            this.affiliate = null;
            this.ecoCom = [];
            this.searchType = null;

          }
        })
        .catch(function(error) {
          flashErrors("Error al buscar: ", [error])
        });
      const scrollToFooter = scroller();
      scrollToFooter("#create-eco-com-ibox");
      this.searching = false;
    },
    clearResults() {
      this.affiliateFound = false;
      this.affiliate = null;
      this.ecoCom = [];
    },
    async getEcoComProcedureCreateName() {
      this.showButton = true;
      this.ecoComProcedureCreateName = "cargando... XD";
      let id = !!this.ecoCom[this.ecoCom.length - 1]
        ? this.ecoCom[this.ecoCom.length - 1].eco_com_procedure_id
        : null;
      await axios
        .get("get_eco_com_procedures_active", {
          params: {
            id,
            affiliate_id: this.affiliate.id
          }
        })
        .then(response => {
          if (response.status == 200) {
            this.ecoComProcedureCreateName = `${
              response.data.semester
            }/${moment(response.data.year).year()}`;
            this.ecoComProcedure = response.data;
            if(this.affiliateObservationsExclude.length > 0){
              this.showButton = false;
            }
          } else {
            this.showButton = false;
          }
        })
        .catch(error => {
          flashErrors("error: ", [error])
        });
    }
  },
  computed: {
    hasBeneficiary(){
      if (this.ecoCom.length > 0) {
        if (this.ecoCom[this.ecoCom.length - 1].eco_com_modality != null) {
          return this.ecoCom[this.ecoCom.length - 1].eco_com_modality.procedure_modality_id != 29
        }
      }
      return false;
    },
    verifyValidDueDate() {
      if (this.hasBeneficiary) {
        return this.ecoComBeneficiary.valid_due_date;
      }else{
        return this.affiliate.valid_due_date;
      }

    },
    verifyHasDisability() {
      let eco = this.ecoCom[this.ecoCom.length - 1];
      if (eco) {
        return eco.aps_disability > 0;
      }
      return false;
    },
  }
};
</script>

<style scoped>
.hover:hover {
  cursor: pointer;
  font-weight: bold;
}
.denied {
  cursor: not-allowed;
}
</style>

