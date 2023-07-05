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
      console.log(document.getElementById("ret-fun-form"));
      document.getElementById("ret-fun-form").submit();
    },
    setLoading: function(value) {
      this.loadingWizard = value;
    },
    showRequirementsErrorChanged(val) {
      console.log("llegue emmit");

      this.showRequirementsError = val;
    },
    validateFirstStep() {
      return true;
      this.showRequirementsError = false;
      if (!this.$refs.uno.$children[0].city_end_id) {
        return false;
      }
      if (!this.$refs.uno.$children[0].modality) {
        return false;
      }
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
      const scrollToFooterCreateBeneficiaries = scroller();
      scrollToFooterCreateBeneficiaries("#ret-fun-form-header");
      return true;
      // var deferred = $.Deferred();

      // const val = this.$validator;
      // setTimeout(function(){
      //     val.validateAll((res)=>{
      //         this.pass=res;
      //     })
      //     console.log("long func completed");
      //     deferred.resolve("hello");
      // }, 1000);
      // return deferred.promise().then((h)=>{return this.pass});
    },
    validateSecondStep() {
      if (!this.$refs.dos.$children[0].applicant_type) {
        return false;
      }
      if (!this.$refs.dos.$children[0].applicant_identity_card) {
        return false;
      }
      if (!this.$refs.dos.$children[0].applicant_first_name) {
        return false;
      }
      if (!this.$refs.dos.$children[0].applicant_kinship_id) {
        return false;
      }
      if (!this.$refs.dos.$children[0].applicant_gender) {
        return false;
      }
      if (!(this.$refs.uno.$children[0].modality == 62 || this.$refs.uno.$children[0].modality == 63)) {
        if (!this.$refs.dos.$children[0].date_derelict) {
          return false;
      }
    }

      if (this.$refs.dos.$children[0].applicant_type == 3) {
        // 3 id de Apoderado
        if (!this.$refs.dos.$children[0].legal_guardian_first_name) {
          return false;
        }
        if (!this.$refs.dos.$children[0].legal_guardian_identity_card) {
          return false;
        }
      }

      this.sendApplicant();
      const scrollToFooterCreateBeneficiaries = scroller();
      scrollToFooterCreateBeneficiaries("#ret-fun-form-header");
      return true;
      // var deferred = $.Deferred();

      // const val = this.$validator;
      // setTimeout(function(){
      //     val.validateAll((res)=>{
      //         this.pass=res;
      //     })
      //     console.log("long func completed");
      //     deferred.resolve("hello");
      // }, 1000);
      // return deferred.promise().then((h)=>{return this.pass});
    },
    sendApplicant() {
      let applicant = {
        type: this.$refs.dos.$children[0].applicant_type,
        first_name: this.$refs.dos.$children[0].applicant_first_name,
        second_name: this.$refs.dos.$children[0].applicant_second_name,
        last_name: this.$refs.dos.$children[0].applicant_last_name,
        mothers_last_name: this.$refs.dos.$children[0]
          .applicant_mothers_last_name,
        surname_husband: this.$refs.dos.$children[0].applicant_surname_husband,
        identity_card: this.$refs.dos.$children[0].applicant_identity_card,
        kinship_id: this.$refs.dos.$children[0].applicant_kinship_id,
        birth_date: this.$refs.dos.$children[0].applicant_birth_date,
        gender: this.$refs.dos.$children[0].applicant_gender,
        phone_number: this.$refs.dos.$children[0].applicant_phone_number,
        cell_phone_number: this.$refs.dos.$children[0]
          .applicant_cell_phone_number
      };
      this.$store.commit("retFunForm/setApplicant", applicant);
      return true;
    }
  },
  computed: {
    checkboxErrors() {
      const errors = [];
      if (!this.$v.checkbox.$dirty) return errors;
      !this.$v.checkbox.required && errors.push("You must agree to continue!");
      return errors;
    },
    selectErrors() {
      const errors = [];
      if (!this.$v.select.$dirty) return errors;
      !this.$v.select.required && errors.push("Item is required");
      return errors;
    },
    nameErrors() {
      const errors = [];
      if (!this.$v.name.$dirty) return errors;
      !this.$v.name.maxLength &&
        errors.push("Name must be at most 10 characters long");
      !this.$v.name.required && errors.push("Name is required.");
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.email.$dirty) return errors;
      !this.$v.email.email && errors.push("Must be valid e-mail");
      !this.$v.email.required && errors.push("E-mail is required");
      return errors;
    }
  }
};
</script>
