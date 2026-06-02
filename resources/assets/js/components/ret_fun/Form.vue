<script>
import { scroller } from "vue-scrollto/src/scrollTo";
export default {
  data() {
    return {
      pass: false,
      loadingWizard: false,
      count: 0,
      name: null,
      email: null,
      phone: null,
      url: null,
      loading: false,
      showSecondStep: false,
    };
  },
  methods: {
    async onFinish() {
      this.loading = true
      try {
        await new Promise(resolve => setTimeout(resolve, 3500));
        document.getElementById("ret-fun-form").submit();
      } catch (error) {
        console.log("Error al enviar el formulario: ", error)
      } finally {
        this.loading = false
      }
    },
    setLoading: function(value) {
      this.loadingWizard = value;
    },
    async validateFirstStep() {      
      const isValid = await this.$refs.uno.validateStep();      
      if (isValid) {
        this.showSecondStep = true;
        const scrollToFooterCreateBeneficiaries = scroller();
        scrollToFooterCreateBeneficiaries("#ret-fun-form-header");
      }
      return isValid;
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
<style>
.form-wizard-container {
    position: relative;
}

.spinner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999; /* Asegúrate de que esté por encima del contenido */
}

.spinner {
    border: 20px solid rgba(0, 0, 0, 0.1);
    border-left-color: #1AB394;
    border-radius: 50%;
    width: 150px;
    height: 150px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
