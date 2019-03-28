<script>
import {
  isPensionEntitySenasir,
  getNamePensionEntity,
  parseMoney
} from "../../helper.js";

export default {
  props: ["pesionEntityId", "ecoCom"],
  data() {
    return {
      aps_total_fsa: this.ecoCom.aps_total_fsa,
      aps_total_cc: this.ecoCom.aps_total_cc,
      aps_total_fs: this.ecoCom.aps_total_fs,

      aps_total_disability: this.ecoCom.aps_total_disability,
      sub_total_rent: this.ecoCom.sub_total_rent,
      reimbursement: this.ecoCom.reimbursement,
      dignity_pension: this.ecoCom.dignity_pension
    };
  },
  computed: {
    isSenasir() {
      return isPensionEntitySenasir(this.pensionEntityId);
    },
    namePensionEntity() {
      return getNamePensionEntity(this.pensionEntityId);
    },
    totalSumFractions() {
      return (
        parseFloat(parseMoney(this.aps_total_fsa)) +
        parseFloat(parseMoney(this.aps_total_cc)) +
        parseFloat(parseMoney(this.aps_total_fs)) +
        parseFloat(parseMoney(this.aps_total_disability))
      );
    },
    totalSumSenasir() {
      return (
        parseFloat(parseMoney(this.sub_total_rent)) -
        parseFloat(parseMoney(this.reimbursement)) +
        parseFloat(parseMoney(this.dignity_pension)) +
        parseFloat(parseMoney(this.aps_total_disability))
      );
    }
  }
};
</script>

<style>
</style>
