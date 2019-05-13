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
              <option v-for="rt in reception_types" :value="rt.id" :key="rt.id">{{ rt.name }}</option>
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
    <div v-if="reception_type_id == 2">
      <h3>Documentos ya presentados</h3>
    </div>
    <div v-else class="wrapper wrapper-content animated fadeInRight">
      <div v-for="(requirement, index) in requirementList" :key="index">
        <div
          class="vote-item"
          @click="checked(index, i)"
          v-for="(rq, i) in requirement"
          :class="rq.background"
          style="cursor:pointer"
          :key="i"
        >
          <div class="row">
            <div class="col-md-10">
              <div class="vote-actions">
                <h1>{{rq.number}}</h1>
              </div>
              <span class="vote-title">{{rq.document}}</span>
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
      <br>
      <div v-if="aditionalRequirements.length > 0">
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
            v-for="(requirement, index) in aditionalRequirements"
            :value="requirement.id"
            :key="index"
          >{{ requirement.document }}</option>
        </select>
      </div>
      <transition name="show-requirements-error" enter-active-class="animated bounceInLeft">
        <div class="alert alert-danger" v-if="showRequirementsError">
          <h2>Debe seleccionar los requisitos</h2>
        </div>
      </transition>
    </div>
  </div>
</template>
<script>
import { mapState, mapMutations } from "vuex";
export default {
  props: [
    "modalities",
    "requirements",
    "user",
    "cities",
    "showRequirementsError",
    "lastEcoCom",
    "pensionEntities",
    "affiliate",
    "ecoComProcedureId"
  ],
  data() {
    return {
      requirementList: [],
      aditionalRequirements: [],
      modality_id: !!this.lastEcoCom
        ? this.lastEcoCom.procedure_modality_id
        : null,
      city_id: this.user.city_id,
      my_index: 1,
      pension_entity_id: !!this.affiliate.pension_entity_id
        ? this.affiliate.pension_entity_id
        : null,
      reception_types: [
        {
          id: 1,
          name: "Inclusion"
        },
        {
          id: 2,
          name: "Habitual"
        }
      ],
      reception_type_id: null
    };
  },
  mounted() {
    this.setPensionEntity();
    this.setReceptionType();
    this.setModality();
    this.$store.commit(
      "ecoComForm/setCity",
      this.cities.filter(city => city.id == this.city_id)[0].name
    );
    // this.$store.commit("ecoComForm/setAffiliate", this.affiliate);
  },
  methods: {
    async onChooseModality(event) {
      // const options = event.target.options;
      // const selectedOption = options[options.selectedIndex];
      // if (selectedOption) {
      //   const selectedText = selectedOption.textContent;
      //   var object = {
      //     name: selectedText,
      //     id: this.procedure_modality_id
      //   };
      //   this.$store.commit("retFunForm/setModality", object);
      // }
      await this.setReceptionType();
      await this.getRequirements();
      await this.getAditionalRequirements();
      await this.setModality();
    },
    setPensionEntity() {
      this.$store.commit("ecoComForm/setPensionEntity", this.pension_entity_id);
    },
    setModality() {
      this.$store.commit("ecoComForm/setModality", {
        id: this.modality_id,
        name: null
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
        this.reception_types.find(r => r.id == this.reception_type_id)
      );
      await this.findBeneficiary();
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
    getRequirements() {
      if (!this.modality_id) {
        this.requirementList = [];
      }
      this.requirementList = this.requirements.filter(r => {
        if (r.modality_id == this.modality_id && r.number != 0) {
          r["status"] = false;
          r["background"] = "";
          return r;
        }
      });
      Array.prototype.groupBy = function(prop) {
        return this.reduce(function(groups, item) {
          const val = item[prop];
          groups[val] = groups[val] || [];
          groups[val].push(item);
          return groups;
        }, {});
      };
      this.requirementList = this.requirementList.groupBy("number");
    },
    getAditionalRequirements() {
      if (!this.modality_id) {
        this.aditionalRequirements = [];
      }
      this.aditionalRequirements = this.requirements.filter(requirement => {
        if (
          requirement.modality_id == this.modality_id &&
          requirement.number == 0
        ) {
          return requirement;
        }
      });
      setTimeout(() => {
        $(".chosen-select")
          .chosen({ width: "100%" })
          .trigger("chosen:updated");
      }, 500);
    },
    checked(index, i) {
      for (var k = 0; k < this.requirementList[index].length; k++) {
        if (k != i) {
          this.requirementList[index][k].status = false;
          this.requirementList[index][k].background = "bg-warning-yellow";
        }
      }
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
    onChooseCity(event) {
      const options = event.target.options;
      const selectedOption = options[options.selectedIndex];
      const selectedText = selectedOption.textContent;
      this.$store.commit("retFunForm/setCity", selectedText);
    }
  }
};
</script>