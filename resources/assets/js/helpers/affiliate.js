import { roles } from "../constants/roles";
import { pension_entity } from "../constants/affiliate";

export default {
  install(Vue) {
    Vue.prototype.$affiliate_helper = {
      // Pension Entity
      isSenasir(affiliate) {
        return affiliate.pension_entity_id === pension_entity.SENASIR;
      },
    };
  },
};
