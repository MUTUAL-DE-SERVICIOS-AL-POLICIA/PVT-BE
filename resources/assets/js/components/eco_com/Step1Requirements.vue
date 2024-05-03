<template>
  <div>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="form-group" :class="{'has-error': errors.has('modality_id') }">
          <label class="col-sm-3 control-label">Modalidad</label>
          <div class="col-sm-8">
            <select
              class="form-control m-b"
              ref="modality_id"
              name="modality_id"
              @change="onChooseModality()"
              v-model="modality_id"
              v-validate.initial="'required'"
            >
              <option :value="null"></option>
              <option v-for="type in modalities" :value="type.id" :key="type.id">{{ type.name }}</option>
            </select>
            <i v-show="errors.has('modality_id')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('modality_id')"
              class="text-danger"
            >{{ errors.first('modality_id') }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group" :class="{'has-error': errors.has('reception_type_id') }">
          <label class="col-sm-4 control-label">Tipo de Recepcion</label>
          <div class="col-sm-8">
            <input type="hidden" v-model="reception_type_id" name="reception_type">
            <select
              class="form-control m-b"
              ref="reception_type_id"
              name="reception_type_id"
              @change="onChooseCity"
              v-model="reception_type_id"
              v-validate.initial="'required'"
              disabled
            >
              <option v-for="rt in ecoComReceptionTypes" :value="rt.id" :key="rt.id">{{ rt.name }}</option>
            </select>
            <i v-show="errors.has('reception_type_id')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('reception_type_id')"
              class="text-danger"
            >{{ errors.first('reception_type_id') }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group" :class="{'has-error': errors.has('pension_entity_id') }">
          <label class="col-sm-4 control-label">Ente Gestor</label>
          <div class="col-sm-8">
            <select
              class="form-control"
              v-model="pension_entity_id"
              ref="pension_entity_id"
              name="pension_entity_id"
              id="pension_entity_id"
              v-validate.initial="'required'"
              @change="setPensionEntity()"
              :disabled="itsUsual"
            >
              <option :value="null"></option>
              <option v-for="(p, index) in pensionEntities" :value="p.id" :key="index">{{p.name}}</option>
            </select>
            <i v-show="errors.has('pension_entity_id')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('pension_entity_id')"
              class="text-danger"
            >{{ errors.first('pension_entity_id') }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group" :class="{'has-error': errors.has('city_id') }">
          <label class="col-sm-4 control-label">Regional</label>
          <div class="col-sm-8">
            <select
              class="form-control m-b"
              ref="city"
              name="city_id"
              @change="onChooseCity"
              v-model="city_id"
              v-validate.initial="'required'"
            >
              <option :value="null"></option>
              <option v-for="city in cities" :value="city.id" :key="city.id">{{ city.name }}</option>
            </select>
            <i v-show="errors.has('city_id')" class="fa fa-warning text-danger"></i>
            <span v-show="errors.has('city_id')" class="text-danger">{{ errors.first('city_id') }}</span>
          </div>
        </div>
      </div>
    </div>
    <h2>Lista de Requisitos</h2>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div v-for="(requirement, key)  in requirementList" :key="key">
        <div
          class="vote-item"
          v-for="(rq, i) in requirement"
          @click="checked(key, i)"
          :class="rq.background"
          style="cursor:pointer"
          :key="rq.id"
        >
          <div class="row">
            <div class="col-md-10">
              <div class="vote-actions">
                <h1>{{rq.number}}</h1>
              </div>
              <span class="vote-title">{{rq.procedure_document.name}}</span>
              <div class="vote-info">
                <div class="col-md-2 no-margins no-padding">
                  <i class="fa fa-comments-o"></i> Comentario:
                </div>
                <div class="col-md-6 no-margins no-padding">
                  <input type="text" :name="'comment'+rq.id" class="form-control">
                </div>
                <br>
              </div>
            </div>
            <div class="col-md-2">
              <div class="vote-icon">
                <span style="color:#3c3c3c">
                  <i class="fa" :class="rq.status ? 'fa-check-square' :'fa-square-o'  "></i>
                </span>
                <div style="opacity:0">
                  <input
                    type="checkbox"
                    v-model="rq.status"
                    value="checked"
                    :name="'document'+rq.id"
                    class="largerCheckbox"
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div v-if="additionalRequirements.length > 0">
      <h4>Documentos adicionales</h4>
      <select
        data-placeholder="Documentos adicionales..."
        class="chosen-select"
        name="aditional_requirements[]"
        multiple
        style="width: 350px; display: none;"
        tabindex="-1"
      >
        <option
          v-for="(requirement, index) in additionalRequirements"
          :value="requirement.id"
          :key="index"
        >{{ requirement.procedure_document.name }}</option>
      </select>
    </div>
    <transition name="show-requirements-error" enter-active-class="animated bounceInLeft">
      <div class="alert alert-danger" v-if="showRequirementsError">
        <h2>Debe seleccionar los requisitos</h2>
      </div>
    </transition>
  </div>
</template>
<script>
import { mapState, mapMutations } from "vuex";
export default {
  props: [
    "modalities",
    "user",
    "cities",
    "showRequirementsError",
    "lastEcoCom",
    "pensionEntities",
    "affiliate",
    "ecoComProcedureId",
    "ecoComReceptionTypes",
    "ecoComConsecutivo"
  ],
  computed: {
    itsUsual() {
      if(this.lastEcoCom) {
        return this.lastEcoCom.eco_com_reception_type_id == 1
      }
    }
  },
  data() {
    return {
      requirements: [],
      requirementList: [],
      additionalRequirements: [],
      modality_id: !!this.lastEcoCom
        ? this.lastEcoCom.procedure_modality_id
        : null,
      city_id: !!this.lastEcoCom ? this.lastEcoCom.city_id : this.user.city_id,
      pension_entity_id: !!this.affiliate.pension_entity_id
        ? this.affiliate.pension_entity_id
        : null,
      reception_type_id: null
    };
  },
  mounted() {
    this.setReceptionType();
    this.setPensionEntity();
    this.setModality();
    this.setCity();
    this.getRequirements();
  },
  methods: {
    async onChooseModality(event) {
      await this.setReceptionType();
      await this.setModality();
      await this.getRequirements();
    },
    setPensionEntity() {
      let name = null;
      if (this.pension_entity_id) {
        name = this.pensionEntities.find(x => x.id == this.pension_entity_id)
          .name;
      }
      this.$store.commit("ecoComForm/setPensionEntity", {
        id: this.pension_entity_id,
        name: name
      });
    },
    setCity() {
      let name = null;
      if (this.city_id) {
        name = this.cities.find(x => x.id == this.city_id).name;
        this.$store.commit("ecoComForm/setCity", name);
      }
    },
    setModality() {
      let name = null;
      if (this.modality_id) {
        name = this.modalities.find(x => x.id == this.modality_id).name;
      }
      this.$store.commit("ecoComForm/setModality", {
        id: this.modality_id,
        name: name
      });
    },
    async setReceptionType() {
      let last_eco_com_id = !!this.lastEcoCom ? this.lastEcoCom.id : null;
      await axios
        .get("/get_eco_com_reception_type", {
          params: {
            modality_id: this.modality_id,
            last_eco_com_id
          }
        })
        .then(response => {
          this.reception_type_id = response.data;
        })
        .catch(error => {
          console.log(error);
        });
      await this.$store.commit(
        "ecoComForm/setReceptionType",
        this.ecoComReceptionTypes.find(r => r.id == this.reception_type_id)
      );
      await this.findBeneficiary();
      this.getRequirements();
    },
    async findBeneficiary() {
      let last_eco_com_id = !!this.lastEcoCom ? this.lastEcoCom.id : null;
      await axios
        .get("/get_eco_com_type_beneficiary", {
          params: {
            modality_id: this.modality_id,
            affiliate_id: this.affiliate.id,
            last_eco_com_id
          }
        })
        .then(response => {
          this.$store.commit("ecoComForm/setEcoComBeneficiary", response.data);
          this.$store.commit("ecoComForm/setAffiliate", this.affiliate);
        })
        .catch(error => {
          console.log(error);
        });
      await this.$validator.validateAll();
      await this.verifyFirstSemesterRents(last_eco_com_id);
    },
    async verifyFirstSemesterRents(last_eco_com_id) {
      await axios
        .get("/get_eco_com_rents_first_semester", {
          params: {
            last_eco_com_id,
            current_procedure_id: this.ecoComProcedureId
          }
        })
        .then(response => {
          this.$store.commit("ecoComForm/setEcoCom", response.data);
        })
        .catch(error => {
          console.log(error);
        });
    },
    async getRequirements() {
      if (!this.modality_id) {
        this.requirementList = [];
      }
      await axios
        .get("/get_procedure_requirements", {
          params: {
            affiliate_id: this.affiliate.id,
            procedure_modality_id: this.modality_id,
            reception_type_id: this.reception_type_id
          }
        })
        .then(response => {
          this.requirementList = response.data.requirements;
          this.additionalRequirements = response.data.additional_requirements;
          setTimeout(() => {
            $(".chosen-select")
              .chosen({ width: "100%" })
              .trigger("chosen:updated");
          }, 500);
          this.verifyOneNumber();
        })
        .catch(error => {
          console.log(error);
        });
    },
    verifyOneNumber(){
      let sw = true;
      for (const key in this.requirementList) {
        if (this.requirementList[key].length != 1) {
          sw = false;
          break;
        }
      }
      if (sw) {
        for (const key in this.requirementList) {
          this.requirementList[key][0].background = "bg-success-green";
          this.requirementList[key][0].status = true;
        }
      }
    },
    checked(index, i) {
      for (var k = 0; k < this.requirementList[index].length; k++) {
        if (k != i) {
          this.requirementList[index][k].status = false;
          this.requirementList[index][k].background = "bg-warning-yellow";
        }
      }
      console.log(this.requirementList[index][i].status);
      this.requirementList[index][i].status = !this.requirementList[index][i]
        .status;
      this.requirementList[index][i].background =
        this.requirementList[index][i].background == "bg-success-green"
          ? ""
          : "bg-success-green";
      if (this.requirementList[index].every(r => !r.status)) {
        for (var k = 0; k < this.requirementList[index].length; k++) {
          if (!this.requirementList[index][k].status) {
            this.requirementList[index][k].background = "";
          }
        }
      }
    },
    onChooseCity() {
      this.setCity();
    }
  }
};
</script>