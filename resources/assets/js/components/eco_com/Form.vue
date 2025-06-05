<script>
import { scroller } from "vue-scrollto/src/scrollTo";
export default {
  data() {
    return {
      pass: false,
      loadingWizard: false,
      showRequirementsError: false,
      count: 0,
      name: null,
      email: null,
      phone: null,
      url: null
    };
  },
  methods: {
    onFinish() {
      document.getElementById("eco-com-form").submit();
    },
    setLoading: function(value) {
      this.loadingWizard = value;
    },
    showRequirementsErrorChanged(val) {
      console.log("llegue emmit");

      this.showRequirementsError = val;
    },
    async validateFirstStep() {
      let keys = ["modality_id", "pension_entity_id", "city_id"];
      try {
        await keys.forEach(k => {
          this.$refs.uno.$children[0].$validator.validate(k);
        });
      } catch (error) {
        console.log("some error");
      }
      await this.$refs.uno.$children[0].$validator.validateAll();
      await this.$refs.dos.$children[0].$validator.validateAll();
      let x = this.$refs.uno.$children[0].requirementList;
      var someRequirement = true;
      Object.keys(x).forEach(function(key) {
        if (!x[key].some(rq => rq.status)) {
          someRequirement = false;
        }
      });
      if (!someRequirement) {
        this.showRequirementsError = !this.showRequirementsError;
        return false;
      }
      const scrollToFooter = scroller();
      scrollToFooter("#eco-com-form-header");
      return true;
    },
    async validateSecondStep() {
      await this.$refs.dos.$children[0].$validator.validateAll();
      const scrollToFooter = scroller();
      scrollToFooter("#eco-com-form-header");
      return this.$refs.dos.$children[0].$validator.errors.items
        .map(x => x.field).length == 0
    },
    async validateThirdStep() {
      await this.$refs.tres.$children[0].$validator.validateAll();
      return this.$refs.tres.$children[0].$validator.errors.items
        .map(x => x.field).length == 0
    }
    // validateFirstStep() {
    //   this.showRequirementsError = false;
    //   if (!this.$refs.uno.$children[0].city_end_id) {
    //     return false;
    //   }
    //   if (!this.$refs.uno.$children[0].modality) {
    //     return false;
    //   }
    //   let x = this.$refs.uno.$children[0].requirementList;
    //   var someRequirement = true;
    //   Object.keys(x).forEach(function(key) {
    //     if (!x[key].some(rq => rq.status)) {
    //       someRequirement = false;
    //     }
    //   });
    //   if (!someRequirement) {
    //     this.showRequirementsError = !this.showRequirementsError;
    //     return false;
    //   }
    //   const scrollToFooter = scroller();
    //   scrollToFooter("#ret-fun-form-header");
    //   return true;
    // },
    // validateSecondStep() {
    //   if (!this.$refs.dos.$children[0].applicant_type) {
    //     return false;
    //   }
    //   if (!this.$refs.dos.$children[0].applicant_identity_card) {
    //     return false;
    //   }
    //   if (!this.$refs.dos.$children[0].applicant_first_name) {
    //     return false;
    //   }
    //   if (!this.$refs.dos.$children[0].applicant_kinship_id) {
    //     return false;
    //   }
    //   if (!this.$refs.dos.$children[0].applicant_city_identity_card_id) {
    //     return false;
    //   }
    //   if (!this.$refs.dos.$children[0].applicant_gender) {
    //     return false;
    //   }
    //   if (!this.$refs.dos.$children[0].date_derelict) {
    //     return false;
    //   }

    //   if (this.$refs.dos.$children[0].applicant_type == 3) {
    //     if (!this.$refs.dos.$children[0].legal_guardian_first_name) {
    //       return false;
    //     }
    //     if (!this.$refs.dos.$children[0].legal_guardian_identity_card) {
    //       return false;
    //     }
    //   }
    //   return true;
    // }
  }
};
</script>
<style>
</style>
