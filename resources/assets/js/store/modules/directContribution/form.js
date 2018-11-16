const state = {
  modality: "",
  modality_id: "",
  procedure_type_id: "",
  city: "",
  id: null,
  isValidated: false,
  correlative: null,
  commitment: {
    commitment_type: null,
    number: null,
    commision_date: null,
    destination: null,
    commitment_date: null,
    pension_declaration: null,
    pension_declaration_date: null,
    date_commitment: null,
    start_contribution_date: null
  }
};
const mutations = {
    setModality(state, object) {
        state.modality = object.name;
        state.modality_id = object.id;
    },
    setProcedureType(state, object) {
        state.procedure_type_id = object.id;
    },
    setCity(state, value) {
        state.city = value;
    },
    setIsValidated(state, value) {
        state.isValidated = value;
    },
    setCorrelative(state, value) {
        state.correlative = value;
    },
    updateCommitment(state, payload){
        state.commitment = payload
    }
};
const getters = {
    getData: state => state
}
export default {
    state,
    mutations,
    getters,
    namespaced: true,
}
