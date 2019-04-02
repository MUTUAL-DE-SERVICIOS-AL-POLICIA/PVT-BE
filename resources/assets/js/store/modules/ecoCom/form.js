
const state ={
    modality:'',
    modality_id:'',
    city:'',
    beneficiary: {},
    contributionTypes:[],
    id: null,
    isValidated: false,
    correlative: null,

    receptionType:{},
    affiliate: {}
}
const mutations = {
  setModality(state, object) {
    state.modality = object.name;
    state.modality_id = object.id;
  },
  setCity(state, value) {
    state.city = value;
  },
  setAffiliate(state, object) {
    state.affiliate = object ;
  },
  setReceptionType(state, object){
      state.receptionType = object;
  },
  setEcoComBeneficiary(state, object) {
    state.beneficiary = object
  },
  setIsValidated(state, value){
    state.isValidated = value;
  },
  setCorrelative(state, value){
    state.correlative = value;
  }
};
const getters = {
    getData: state => state
}
export default{
    state,
    mutations,
    getters,
    namespaced: true,
}
