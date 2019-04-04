<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox-title">
          <h3>Creando Complemento Economico</h3>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <div class="col-md-5">
                <strong>Ingrese la cédula de identidad del afiliado:</strong>
              </div>
              <div class="col-md-5">
                <div class="input-group">
                  <input
                    type="text"
                    ref="identityCard"
                    v-model="identityCard"
                    class="form-control"
                    @keypress.enter="searchAffiliate()"
                  >
                  <span class="input-group-btn">
                    <button
                      class="btn btn-primary"
                      type="button"
                      @click="searchAffiliate()"
                      role="button"
                    >
                      <i v-if="searching" key="searching" class="fa fa-spinner fa-pulse fa-fw"></i>
                      <i v-else key="foundnofound" class="fa fa-search"></i>
                    </button>
                  </span>
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
          No se encontro el afiliado con ci
          <strong>{{ identityCard }}</strong>
        </h2>
      </div>
    </transition>
    <transition name="custom-show-affiliate-transition" enter-active-class="animated fadeInLeftBig">
      <div class v-if="affiliateFound">
        <div class="col-lg-6">
          <div class="ibox float-e-margins">
            <div class="ibox-title">
              <h1>Datos del Afiliado</h1>
            </div>
            <div class="ibox-content">
              <div class="row">
                <div class="col-md-12">
                  <div>
                    <h2 class="no-margins" data-toggle="tooltip" data-placement="top" title="Nombre Completo">{{ affiliate.full_name }}</h2>
                    <h3 data-toggle="tooltip" data-placement="top" title="Cedula de Identidad">{{ affiliate.ci_with_ext }}</h3>
                    <h3 data-toggle="tooltip" data-placement="top" title="Grado">{{ affiliate.degree_name }}</h3>
                    <h4 data-toggle="tooltip" data-placement="top" title="Categoria">{{ affiliate.category_percentage }}</h4>
                    <h4 data-toggle="tooltip" data-placement="top" title="Ente Gestor">{{ affiliate.pension_entity_name }}</h4>
                    <h4 data-toggle="tooltip" data-placement="top" title="Fecha de desvinculacion">{{ affiliate.date_entry }}</h4>
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
        <div class="col-lg-6">
          <div class="ibox float-e-margins">
            <div class="ibox-title">
              <h1>Ultimo Complemento Economico</h1>
            </div>
            <div class="ibox-content">
              <div class="table-responsive" v-if="ecoCom.length">
                <table class="table table-striped">
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
                    <tr v-for="e in ecoCom" :key="e.id">
                      <td>{{e.code}}</td>
                      <td>{{e.semester | uppercase }}/{{e.year | year }}</td>
                      <td>{{e.eco_com_modality.name}}</td>
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
                  :href="`/affiliate/${this.affiliate.id}/eco_com_process/create/${this.ecoComProcedure.id}`"
                >
                  <button class="btn btn-primary btn-lg btn-block">
                    <i class="fa fa-plus"></i>
                    Crear {{ ecoComProcedureCreateName }}
                  </button>
                </a>
              </div>
              <div v-else key="noShowButton">
                <button class="btn btn-warning btn-lg btn-block">
                  <i class="fa fa-exclamation-triangle"></i>
                  No hay tramites pendientes para crear
                </button>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import ShowAffiliate from "../affiliate/ShowAffiliate.vue";
export default {
  props: ["cities"],
  data() {
    return {
      editing: false,
      affiliate: {},
      ecoCom: null,
      affiliateNoFound: false,
      affiliateFound: false,
      showButton: false,
      identityCard: "2520804",
      ecoComProcedureCreateName: null,
      ecoComProcedure: {},
      searching: false
    };
  },
  components: {
    ShowAffiliate
  },
  methods: {
    async searchAffiliate() {
      this.searching = true;
      await axios
        .get("/search_ajax_only_affiliate", {
          params: {
            ci: this.identityCard
          }
        })
        .then(response => {
          console.log(response);
          let data = response.data;
          if (data.affiliate != null) {
            flash("Affiliado Encontrado");
            this.affiliate = response.data.affiliate;
            this.ecoCom = response.data.eco_com;
            this.affiliateFound = true;
            this.affiliateNoFound = false;
            this.getEcoComProcedureCreateName();
          } else {
            flash("Affiliado NO Encontrado", "warning");
            this.affiliateNoFound = true;
            this.affiliateFound = false;
            this.affiliate = null;
            this.ecoCom = null;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
      this.searching = false;
    },
    async getEcoComProcedureCreateName() {
      this.showButton = true;
      this.ecoComProcedureCreateName = "cargando... XD";
      let id = !!this.ecoCom[0] ? this.ecoCom[0].eco_com_procedure_id : null;
      await axios
        .get("get_eco_com_procedures_active", {
          params: {
            id,
            affiliate_id: this.affiliate.id
          }
        })
        .then(response => {
          console.log(response.status);
          if (response.status == 200) {
            this.ecoComProcedureCreateName = `${
              response.data.semester
            }/${moment(response.data.year).year()}`;
            this.ecoComProcedure = response.data;
          } else {
            this.showButton = false;
          }
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style>
</style>

