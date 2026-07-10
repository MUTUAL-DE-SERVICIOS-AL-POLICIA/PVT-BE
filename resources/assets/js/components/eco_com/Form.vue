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
      const isValid = await this.$refs.uno.validateStep();
      if (isValid) {
        this.showSecondStep = true;
        const scrollToFooter = scroller();
        scrollToFooter("#eco-com-form-header");
      }
      return isValid;
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
  }
};
</script>
<style>
</style>
