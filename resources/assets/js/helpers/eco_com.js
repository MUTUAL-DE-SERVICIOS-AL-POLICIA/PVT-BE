import { roles } from "../constants/roles";
import { reception_type, state_type } from "../constants/ecoCom";

export default {
  install(Vue) {
    Vue.prototype.$eco_com_helper = {
      // Rent Type
      isAutomaticRent(eco_com) {
        return eco_com.rent_type === "Automatico";
      },
      isManualRent(eco_com) {
        return eco_com.rent_type === "Manual";
      },
      // Reception Types
      isHabitual(eco_com) {
        return eco_com.eco_com_reception_type_id === reception_type.HABITUAL;
      },
      isInclusion(eco_com) {
        return eco_com.eco_com_reception_type_id === reception_type.INCLUSION;
      },
      // Roles
      isEncargadoCalificacion(roleId) {
        return roleId === roles.eco_com.ENCARGADO_CALIFICACION;
      },
      isAreaTecnica(roleId) {
        return roleId === roles.eco_com.AREA_TECNICA;
      },
      isJefatura(roleId) {
        return roleId === roles.eco_com.JEFATURA;
      },
      // state
      isPaid(eco_com) {
        eco_com.eco_com_state.eco_com_state_type_id === state_type.PAGADO;
      },
    };
  },
};
