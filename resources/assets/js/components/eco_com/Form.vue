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
      let documentLoaded = this.$refs.uno.$children[0].documentsLoaded;
      if (!documentLoaded) {
        this.showRequirementsError = true;
        return false;
      }
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
  }
};
</script>
<style>
</style>
