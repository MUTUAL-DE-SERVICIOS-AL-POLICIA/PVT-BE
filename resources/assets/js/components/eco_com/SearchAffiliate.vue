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
                <select
                  class="form-control"
                  v-model="searchSelected"
                >
                  <option v-for="s in searchSelect" :value="s.type" :key="s.type">
                    <strong>Buscar por {{ s.text }}</strong>
                  </option>
                </select>
              </div>
              <div class="col-md-3" :class="{'has-error': errors.has(searchSelected) }">
                <div class="input-group">
                  <input
                    ref="searchInput"
                    type="text"
                    data-vv-name="identity_card"
                    v-model="identityCard"
                    class="form-control"
                    v-validate="'required'"
                    @keypress.enter="searchAffiliate()"
                    :disabled="hasDoblePerception"
                    v-show="searchSelected == 'identity_card'"
                  >
                  <input
                    ref="searchInput"
                    type="text"
                    data-vv-name="nup"
                    v-model="identityCard"
                    class="form-control"
                    v-validate="'required'"
                    @keypress.enter="searchAffiliate()"
                    :disabled="hasDoblePerception"
                    v-show="searchSelected == 'nup'"
                  >
                  <span class="input-group-btn">
                    <button
                      class="btn"
                      type="button"
                      @click="searchAffiliate()"
                      :class="errors.has(searchSelected) ? 'btn-danger' : 'btn-primary'"
                      role="button"
                    >
                      <i v-if="searching" key="searching" class="fa fa-spinner fa-pulse fa-fw"></i>
                      <i v-else key="foundnofound" class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
                <div v-show="errors.has(searchSelected)">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first(searchSelected) }}</span>
                </div>
              </div>
              <div class="col-sm-6" v-if="hasDoblePerception">
                <!-- :class="{'has-error': errors.has('search_type_id') }" -->
                <div class="form-group">
                  <label for>El ci percibe doble beneficio seleccione:</label>
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
                  >{{ errors.first('search_type_id') }}</span>-->
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
          No se encontró el {{ searchSelect.find(o => o.type == searchSelected).text }}
          <strong>{{ identityCard }}</strong>
        </h2>
      </div>
    </transition>
    <transition name="custom-show-affiliate-transition" enter-active-class="animated fadeInLeftBig">
      <div>
        <div class="col-lg-6">
          <div class="ibox float-e-margins" v-if="hasBeneficiary">
            <div class="ibox-title">
              <h1>
                <i class="fa fa-user"></i> Datos del Beneficiario
              </h1>
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
                    >{{ ecoComBeneficiary.identity_card }}</h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="ibox float-e-margins" v-if="affiliateFound">
            <div class="ibox-title">
              <h1>
                <img
                  key="female"
                  v-if="affiliate.gender != 'M'"
                  src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAARxSURBVGhD7dpniB1VHIbxtcZYIyo2rCQgChYwCnZFUL8oKqiRIHYxUROxYUXFhv2DFQuiIIZg7yUECxpjJCJYQD8oYsdesT+P7MBh+N+ZM+WuK8kLP0g2c8+ZO3P6ZmRplpCsjJPwKn7Gl6N/Ph8b43+RA/Eh/h7gd9yItTEuMxlPIrr5yBc4FOMmK+I82ISiG65zP9bFf5p98TaiGyzYlKKfp+xD0zDm2Q7PIrqpwuvYC76xtXAafkR0bWE+LHuoWQEH4Rn8hehGCp9hJZRjn4iuT/2Je7ETeos3vxuuwMeIKo7YnKIvMhPR9YMsxilwIGmcjeCNv4JfEFWQ4wiUY5nRtTk+wcM4BsugNu8gKqipd7EGiji/RNe1cSpq8z2iD7fhgLAtDsa3oz/rw5WozQ2IPjxe/IFtUBubw5uICmniO3wNlyuOYv75J0TX5nK0tPNnx3H/aUSFpb7Cc7ga0zEVG2J5DMoqmALnmNm4E845vyKqo/ApnIAbZzlcjnTO+A22e5/K5qiKX2ZNbIYNsDqq4lDvl7sGDhTpl5iLzotMJ7E34LJ8kj9Ishr2w1m4Ay/BZlT1dG1y7+FxeNPHY2ssizQOEjfBa7KG26axo12K12DHi262DZvpgzgKvsmhpHgi6+BlRDfSF5uyE7Lp9U1YmM3AJmRsyy4AXbFGN9LFQuwBYz13Yc9//9ZDnMysxI5+Ooq2PBFH4jG03YvoI9gXdkaRTfEC/Hf7pwNPp7gEt2OmFTvHHIC0cJ/eDjgBNgtXr45u3syiUQ7TT+A2uBE7BN5wGjdYV6H8YI5Gpzg3pAWmnOjs9Lugat6oi/3OkfEh+NajunwrneJGJyq47Af49G/HmTgcvrW9sT08VYnisOv+IyqzbEe0ihNe3Qaqjp+/EFWjz6OIPlt2C1rF2TsqMJdNzx1lXWxa5X4YsbxWeQRRgVVsJi/iOExAbtwBfoCozNQWaBRHJJcSUWF6C/Pg0OtY7wni/vDpts16eACfI6pTM9Aork6jgnQGhhnnJ5c/Ud0OJo0yaFvq8NikybTNiYjqdz5qlHMRFeQwOxY5DFH9HoY0Wn/diqggbYlh51pEdWt9ZKdqxHoedZukLtkVVcdQLoWyY1uMCik4snjY1mdc17kWqzsrdhGbnfcRFZL6Bquir3iIHdVT5hyVnaqxPHUy+kruKWSj4T93f+HSwiV81+yOqPzIZchOVMAgXSdIN2p1fTKVdcpYJCpgEOeWLr/gdJcZlTuIu8nsRAVUcbRpc1CwCTw9icocZKhfRB7mNYnD7QJEZVXJ/iKufKMCcrg1zol1uGqOyqhzD7LitjR3+1nmotI2XxXfxH2IPp/jbmTHw2XXW56IXASPRAsX4zrMgYd10b7Fz0anhVvBjVd6rdthJ2BPXTzQts5zkNZ5Aa6HzWpovyy1k7vDOxZPoTgJ8QveDJcys+DevHjTng/7vx/2Qfk8edzE7ai7x/SpF/z19CUYiz1Nb7EJnA2fvMty+46/d1maJSAjI/8ADDjATv9kfIgAAAAASUVORK5CYII="
                >
                <img
                  key="male"
                  v-else
                  src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAG1SURBVEhL7dZPKARRAMfx9Se5cXEgRbgoN1fhwkVyUcpB4UIpxYEoCgcnJSlFuclBXJSLq6JQ/oQDDlykCKH89/2NnWzT7O683Vkc/OrTzns7772dnTfzXuA/BkkKfv5YqrGKJ1xiHNlIWIqwiA8XN+hGGnyLrmYCukJ7oHtcBz0H6+QUTUiGcfSri1GHGTzA7tjWATu7cH5/hD6UIRdRk4IzODtyWoFSiBe4nRNqAFFzCLfGTj1Yc9SF04WoqcQr3DqIxRbS4SltOPHBNjzd41+PJlmpD/TsGyUDbvfL1DKM8icGvgs59uIdO8HjuAYuwXxIOZJbNEKvTZVjHvjRKn2lHscIHcj2hgXkQamB6mMe+NwqfUcv/1pMQa/NJQzCOXvjHvjCKplHC4zaaxk1zhXUuMIqeU8qZqG2o6owTS/UWJpV4THrUBttDvJVYRrtqUagTg7gZXdRBZ2vuaG1OK5sQJ0NW6Xw0bywZ32rKuKNdiP2LmQIuofOFGATOkez3bcdaAPsvZWWO02aFnRiDnre9d0esuBrdM/2oQGctHGYRiYSEv2F5ejHJMbQjhz8J0ICgU/xpvcXuzsBLwAAAABJRU5ErkJggg=="
                >
                Datos del Afiliado
              </h1>
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
                    >{{ affiliate.identity_card }}</h3>
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
                    <h4
                      data-toggle="tooltip"
                      data-placement="top"
                      title="N.U.P."
                    >{{ affiliate.id }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            class="ibox float-e-margins"
            v-if="affiliateFound && affiliateDevolutions.length > 0"
          >
            <div class="ibox-title" style="color: rgb(255, 55, 55);">
              <h1>
                <i class="fa fa-balance-scale"></i> Deudas Pendientes del Afiliado
              </h1>
            </div>
            <div class="ibox-content">
              <div class="row">
                <div class="col-md-12">
                  <div v-for="ad in affiliateDevolutions" :key="ad.id">
                    <h3
                      class="no-margins"
                      data-toggle="tooltip"
                      data-placement="top"
                      title
                    >{{ ad.observation_type.name }}</h3>
                    <span style="font-size:18px">
                      Deuda Pendiente:
                      <strong>{{ ad.balance | currency }}</strong> Deuda Total:
                      <strong>{{ ad.total | currency}}</strong>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="custom-show-ecocom-transition" enter-active-class="animated fadeInRightBig">
      <div class="col-lg-6" v-if="affiliateFound">
        <div class="alert alert-orange block" v-if="otherObservations.length > 0">
          Se encontraron algunas observaciones antes de crear el Trámite:
          <ul>
            <li
              class="alert-link"
              v-for="(oo, index) in otherObservations"
              :key="index"
            >{{ oo.value }}</li>
          </ul>
        </div>
        <div class="ibox float-e-margins">
          <div class="ibox-title">
            <h1>Ultimo Complemento Economico</h1>
          </div>
          <div class="ibox-content">
            <div class="table-responsive" v-if="ecoCom.length">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th># de Trámite</th>
                    <th>Gestion</th>
                    <th>Modalidad</th>
                    <th>Estado</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="e in ecoCom" class="hover" :key="e.id" @click="rowClick(e.id)">
                    <td>{{e.code}}</td>
                    <td>{{e.eco_com_procedure.semester | uppercase }}/{{e.eco_com_procedure.year | year }}</td>
                    <td>{{e.eco_com_modality.shortened}}</td>
                    <td>{{e.eco_com_state.name}}</td>
                    <td>{{e.total}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <h3 v-else>No se encontro ningun Trámite</h3>
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
                No hay Trámites pendientes para crear
              </button>
            </div>
          </transition>
        </div>
        <div class="alert alert-danger block" v-if="affiliateObservationsExclude.length">
          <h4>Excluido por:</h4>
          <ul>
            <li
              class="alert-link"
              v-for="ao in affiliateObservationsExclude"
              :key="ao.id"
            >{{ ao.name }}</li>
          </ul>
          <a :href="`/affiliate/${affiliate.id}`">
            ir al afiliado
            <i class="fa fa-external-link"></i>
          </a>
        </div>
        <div class="alert alert-danger block" v-if="affiliateObservations.length">
          <h4>Observado por:</h4>
          <ul>
            <li class="alert-link" v-for="ao in affiliateObservations" :key="ao.id">{{ ao.name }}</li>
          </ul>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { scroller } from "vue-scrollto/src/scrollTo";
import { flashErrors } from "../../helper";
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
      searchSelect: [
        {
          type: 'identity_card',
          text: 'C.I.'
        }, {
          type: 'nup',
          text: 'N.U.P.'
        }
      ],
      searchSelected: 'identity_card',
      identityCard: null,
      ecoComProcedureCreateName: null,
      ecoComProcedure: {},
      searching: false,
      affiliateObservationsExclude: [],
      affiliateObservations: [],
      affiliateDevolutions: [],
      otherObservations: [],
      oneTime: true,
      hasDoblePerception: false
    };
  },
  watch: {
    searchSelected(val) {
      this.affiliateNoFound = false
      this.identityCard = null
      this.errors.clear()
    },
    identityCard(val) {
      this.affiliateNoFound = false
    }
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
      let params = {
        type: this.searchType,
        one_time: this.oneTime,
        has_doble_perception: this.hasDoblePerception
      }
      params[this.searchSelected] = this.identityCard
      await axios
        .post("/search_ajax_only_affiliate", params)
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
            this.affiliateObservationsExclude =
              response.data.affiliate_observations_exclude;
            this.affiliateObservations = response.data.affiliate_observations;
            this.affiliateDevolutions = response.data.affiliate_devolutions;
            this.otherObservations = response.data.other_observations;
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
          flashErrors("Error al buscar: ", [error]);
        });
      const scrollToFooter = scroller();
      scrollToFooter("#create-eco-com-ibox");
      this.searching = false;
    },
    clearResults() {
      this.$forceUpdate()
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
            if (this.affiliateObservationsExclude.length > 0) {
              this.showButton = false;
            }
          } else {
            this.showButton = false;
          }
        })
        .catch(error => {
          flashErrors("error: ", [error]);
        });
    }
  },
  computed: {
    hasBeneficiary() {
      if (this.ecoCom.length > 0) {
        if (this.ecoCom[this.ecoCom.length - 1].eco_com_modality != null) {
          return (
            this.ecoCom[this.ecoCom.length - 1].eco_com_modality
              .procedure_modality_id != 29
          );
        }
      }
      return false;
    }
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

