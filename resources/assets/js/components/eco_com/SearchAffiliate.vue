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
                      <i class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row" v-if="affiliateNoFound">
            <h1>No se encontro el afiliado con ci {{ identityCard }}</h1>
          </div>
          <div class="row" v-if="affiliateFound">
            <!-- <div class="row" v-if="false"> -->
            <div class="col-md-6">
              <div class="row m-b-md">
                <div class="col-md-4">
                  <label class="control-label">Cédula de identidad:</label>
                </div>
                <div class="col-md-8">
                  <input
                    name="identity_card"
                    type="text"
                    v-model="affiliate.identity_card"
                    class="form-control"
                    :disabled="!editing"
                  >
                </div>
              </div>
              <div class="row m-b-md">
                <div class="col-md-4">
                  <label class="control-label">Lugar de expedición:</label>
                </div>
                <div class="col-md-8">
                  <!-- <input
                    name="identity_card"
                    type="text"
                    v-model="affiliate.city_identity_card.first_shortened"
                    class="form-control"
                    :disabled="!editing"
                  >-->
                </div>
              </div>
              <div class="row m-b-md">
                <div class="col-md-4">
                  <label class="control-label">Primer Nombre:</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="first_name"
                    v-model="affiliate.first_name"
                    class="form-control"
                    :disabled="!editing"
                  >
                </div>
              </div>
              <div class="row m-b-md">
                <div class="col-md-4">
                  <label class="control-label">Segundo Nombre:</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="second_name"
                    v-model="affiliate.second_name"
                    class="form-control"
                    :disabled="!editing"
                  >
                </div>
              </div>
              <div class="row m-b-md">
                <div class="col-md-4">
                  <label class="control-label">Apellido Paterno:</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="last_name"
                    v-model="affiliate.last_name"
                    class="form-control"
                    :disabled="!editing"
                  >
                </div>
              </div>
              <div class="row m-b-md">
                <div class="col-md-4">
                  <label class="control-label">Apellido Materno:</label>
                </div>
                <div class="col-md-8">
                  <input
                    name="mothers_last_name"
                    type="text"
                    v-model="affiliate.mothers_last_name"
                    class="form-control"
                  >
                </div>
              </div>
              <div class="row m-b-md" v-show="affiliate.gender === 'F'">
                <div class="col-md-4">
                  <label class="control-label">Apellido de Casada:</label>
                </div>
                <div class="col-md-8">
                  <input
                    name="surname_husband"
                    type="text"
                    class="form-control"
                    v-model="affiliate.surname_husband"
                  >
                </div>
              </div>
              <div class="row m-b-md">
                <div class="col-md-4">
                  <label class="control-label">Genero:</label>
                </div>
                <div class="col-md-8">
                  <input
                    name="surname_husband"
                    type="text"
                    class="form-control"
                    v-model="affiliate.gender"
                  >
                </div>
              </div>
            </div>
            <!-- {{-- right --}} -->
            <div class="col-md-6">
              <div class="form-group row m-b-md">
                <div class="col-sm-3 col-form-label">
                  <label class="control-label">Fecha de Nacimiento:</label>
                </div>
                <div class="col-md-5">
                  <input
                    name="birth_date"
                    v-model="affiliate.birth_date"
                    v-date
                    type="text"
                    class="form-control"
                  >
                </div>
              </div>
              <div class="row m-b-md">
                <div class="col-md-3">
                  <label class="control-label">Lugar de Nacimiento:</label>
                </div>
                <div class="col-md-9">
                  <!-- <input
                    name="birth_date"
                    v-model="affiliate.city_birth.name"
                    type="text"
                    class="form-control"
                  >-->
                </div>
              </div>
              <div class="row m-b-md">
                <div class="col-md-3">
                  <label class="control-label">Estado Civil:</label>
                </div>
                <div class="col-md-9">
                  <input
                    name="birth_date"
                    v-model="affiliate.civil_status"
                    type="text"
                    class="form-control"
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="row" v-if="affiliate">
      <div class="col-lg-12">
        <div class="ibox float-e-margins">
          <div class="ibox-title">
            <h1>Ultimo Complemento Economico</h1>
          </div>
          <div class="ibox-content">
            <table class="table table-striped" v-if="ecoCom">
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
                <tr>
                  <td>{{ecoCom.code}}</td>
                  <td>{{ecoCom.semester | uppercase }}/{{ecoCom.year | year }}</td>
                  <td>{{ecoCom.eco_com_modality.name}}</td>
                  <td>{{ecoCom.eco_com_state.name}}</td>
                  <td>{{ecoCom.total}}</td>
                </tr>
              </tbody>
            </table>
            <h3 v-else>No se encontro ningun tramite</h3>
          </div>
        </div>
      </div>
    </div>
    <div v-if="affiliate">
      <a
        :href="`/affiliate/${this.affiliate.id}/eco_com_process/create/${this.ecoComProcedure.id}`"
      >
        <button class="btn btn-primary" v-if="showButton">
          <i class="fa fa-arrow-circle-right"></i>
          Crear {{ ecoComProcedureCreateName }}
        </button>
      </a>
    </div>
  </div>
</template>

<script>
import ShowAffiliate from "../affiliate/ShowAffiliate.vue";
export default {
  props: ["cities"],
  data() {
    return {
      editing: false,
      affiliate: null,
      ecoCom: null,
      affiliateNoFound: false,
      affiliateFound: false,
      showButton: false,
      identityCard: "977631",
      ecoComProcedureCreateName: null,
      ecoComProcedure: null
    };
  },
  components: {
    ShowAffiliate
  },
  methods: {
    searchAffiliate() {
      axios
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
    },
    getEcoComProcedureCreateName() {
      this.showButton = true;
      this.ecoComProcedureCreateName = "cargando... XD";
      let id = !!this.ecoCom ? this.ecoCom.eco_com_procedure_id : null;
      axios
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

