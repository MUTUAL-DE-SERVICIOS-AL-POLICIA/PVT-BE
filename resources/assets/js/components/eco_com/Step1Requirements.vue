<template>
  <div>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="form-group" :class="{'has-error': errors.has('modality_id') }">
          <label class="col-sm-3 control-label">Modalidad</label>
          <div class="col-sm-8">
            <select class="form-control m-b" ref="modality_id" name="modality_id" @change="onChooseModality()"
              v-model="modality_id" v-validate.initial="'required'">
              <option :value="null"></option>
              <option v-for="type in modalities" :value="type.id" :key="type.id">{{ type.name }}</option>
            </select>
            <i v-show="errors.has('modality_id')" class="fa fa-warning text-danger"></i>
            <span v-show="errors.has('modality_id')" class="text-danger">{{ errors.first('modality_id') }}</span>
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
            <select class="form-control m-b" ref="reception_type_id" name="reception_type_id" @change="onChooseCity"
              v-model="reception_type_id" v-validate.initial="'required'" disabled>
              <option v-for="rt in ecoComReceptionTypes" :value="rt.id" :key="rt.id">{{ rt.name }}</option>
            </select>
            <i v-show="errors.has('reception_type_id')" class="fa fa-warning text-danger"></i>
            <span v-show="errors.has('reception_type_id')" class="text-danger">{{ errors.first('reception_type_id')
              }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group" :class="{'has-error': errors.has('pension_entity_id') }">
          <label class="col-sm-4 control-label">Ente Gestor</label>
          <div class="col-sm-8">
            <select class="form-control" v-model="pension_entity_id" ref="pension_entity_id" name="pension_entity_id"
              id="pension_entity_id" v-validate.initial="'required'" @change="setPensionEntity()" :disabled="itsUsual">
              <option :value="null"></option>
              <option v-for="(p, index) in pensionEntities" :value="p.id" :key="index">{{p.name}}</option>
            </select>
            <input type="hidden" v-if="itsUsual" :value="pension_entity_id" name="pension_entity_id">
            <i v-show="errors.has('pension_entity_id')" class="fa fa-warning text-danger"></i>
            <span v-show="errors.has('pension_entity_id')" class="text-danger">{{ errors.first('pension_entity_id')
              }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group" :class="{'has-error': errors.has('city_id') }">
          <label class="col-sm-4 control-label">Regional</label>
          <div class="col-sm-8">
            <select class="form-control m-b" ref="city" name="city_id" @change="onChooseCity" v-model="city_id"
              v-validate.initial="'required'">
              <option :value="null"></option>
              <option v-for="city in cities" :value="city.id" :key="city.id">{{ city.name }}</option>
            </select>
            <i v-show="errors.has('city_id')" class="fa fa-warning text-danger"></i>
            <span v-show="errors.has('city_id')" class="text-danger">{{ errors.first('city_id') }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
      <requirement-select ref="requirements" :requirement-list="requirementList"
        :additional-requirements="additionalRequirements" :is-loading="loadingReq"></requirement-select>
    </div>
  </div>
</template>
<script>
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
      requirementList: {},
      additionalRequirements: [],
      loadingReq: false,
      modality_id: !!this.lastEcoCom
        ? this.lastEcoCom.procedure_modality_id
        : null,
      city_id: !!this.lastEcoCom ? this.lastEcoCom.city_id : this.user.city_id,
      pension_entity_id: !!this.affiliate.pension_entity_id
        ? this.affiliate.pension_entity_id
        : null,
      reception_type_id: null,
    };
  },
  async mounted() {
    await this.setReceptionType();
    await this.setPensionEntity();
    await this.setModality();
    await this.setCity();
    if (this.modality_id) {
        await this.getRequirements();
    }
  },
  methods: {
    // Valida el primer paso del formulario, llamado desde el padre
    async validateStep() {
      const isStepValid = await this.$validator.validateAll();
      const isRequirementsValid = this.reception_type_id == 1 ? true : this.$refs.requirements.validate();      
      return isStepValid && isRequirementsValid;
    },
    async onChooseModality() {
      await this.setReceptionType();
      await this.setModality();
      if (this.modality_id) {
        this.getRequirements();
      }
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
      if (!this.modality_id || this.reception_type_id == 1) {
        this.requirementList = {};
        this.additionalRequirements = [];
        return;
      }
      let uri = `/gateway/api/affiliates/${this.affiliate.id}/modality/${this.modality_id}/collate`;
      this.loadingReq = true;
      try {
        const data = (await axios.get(uri)).data;
        this.requirementList = data.requiredDocuments;
        this.additionalRequirements = data.additionallyDocuments;
      } catch (error) {
        console.log(error);
      } finally {
        this.loadingReq = false;
      }
    },    
    onChooseCity() {
      this.setCity();
    }
  }
};
</script>