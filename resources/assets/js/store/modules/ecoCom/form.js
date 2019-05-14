const state = {
  modality: "",
  modality_id: "",
  city: "",
  id: null,
  isValidated: false,
  correlative: null,

  beneficiary: {},
  legalGuardian: {},
  receptionType: {},
  affiliate: {},
  pensionEntityId: null,
  ecoCom: {}
};
const mutations = {
  setModality(state, object) {
    state.modality = object.name;
    state.modality_id = object.id;
  },
  setCity(state, value) {
    state.city = value;
  },
  setAffiliate(state, object) {
    state.affiliate = object;
  },
  setEcoCom(state, object) {
    state.ecoCom = object;
  },
  setReceptionType(state, object) {
    state.receptionType = object;
  },
  setEcoComBeneficiary(state, object) {
    state.beneficiary = object;
  },
  setIsValidated(state, value) {
    state.isValidated = value;
  },
  setCorrelative(state, value) {
    state.correlative = value;
  },
  setPensionEntity(state, value){
    state.pensionEntityId = value
  },
  addBeneficiaryPhoneNumber(state) {
    if (state.beneficiary.phone_number.length > 0) {
      let last_phone =
        state.beneficiary.phone_number[
          state.beneficiary.phone_number.length - 1
        ];
      if (last_phone.value && !last_phone.value.includes("_")) {
        state.beneficiary.phone_number.push({ value: "" });
      }
    } else {
      state.beneficiary.phone_number.push({ value: "" });
    }
  },
  deleteBeneficiaryPhoneNumber(state, phone) {
    state.beneficiary.phone_number.splice(
      state.beneficiary.phone_number.indexOf(phone),
      1
    );
    if (state.beneficiary.phone_number.length < 1)
      state.beneficiary.phone_number.push({ value: "" });
  },
  addBeneficiaryCellPhoneNumber() {
    if (state.beneficiary.cell_phone_number.length > 0) {
      let last_phone =
        state.beneficiary.cell_phone_number[
          state.beneficiary.cell_phone_number.length - 1
        ];
      if (last_phone.value && !last_phone.value.includes("_")) {
        state.beneficiary.cell_phone_number.push({ value: null });
      }
    } else {
      state.beneficiary.cell_phone_number.push({ value: null });
    }
  },
  deleteBeneficiaryCellPhoneNumber(state, cell_phone_number) {
    state.beneficiary.cell_phone_number.splice(
      state.beneficiary.cell_phone_number.indexOf(cell_phone_number),
      1
    );
    if (state.beneficiary.cell_phone_number.length < 1)
      state.beneficiary.cell_phone_number.push({ value: null });
  },
  addLegalGuardianPhoneNumber(state) {
    if (state.legalGuardian.phone_number.length > 0) {
      let last_phone =
        state.legalGuardian.phone_number[
          state.legalGuardian.phone_number.length - 1
        ];
      if (last_phone.value && !last_phone.value.includes("_")) {
        state.legalGuardian.phone_number.push({ value: "" });
      }
    } else {
      state.legalGuardian.phone_number.push({ value: "" });
    }
  },
  deleteLegalGuardianPhoneNumber(state, phone) {
    state.legalGuardian.phone_number.splice(
      state.legalGuardian.phone_number.indexOf(phone),
      1
    );
    if (state.legalGuardian.phone_number.length < 1)
      state.legalGuardian.phone_number.push({ value: "" });
  },
  addLegalGuardianCellPhoneNumber() {
    if (state.legalGuardian.cell_phone_number.length > 0) {
      let last_phone =
        state.legalGuardian.cell_phone_number[
          state.legalGuardian.cell_phone_number.length - 1
        ];
      if (last_phone.value && !last_phone.value.includes("_")) {
        state.legalGuardian.cell_phone_number.push({ value: null });
      }
    } else {
      state.legalGuardian.cell_phone_number.push({ value: null });
    }
  },
  deleteLegalGuardianCellPhoneNumber(state, cell_phone_number) {
    state.legalGuardian.cell_phone_number.splice(
      state.legalGuardian.cell_phone_number.indexOf(cell_phone_number),
      1
    );
    if (state.legalGuardian.cell_phone_number.length < 1)
      state.legalGuardian.cell_phone_number.push({ value: null });
  },
  setLegalGuardian(state, object){
    state.legalGuardian = object
    // if(!!object.phone_number && typeof object.phone_number == 'object'){
      
    // }else if (typeof object.phone_number == 'string') {
    //   state.legalGuardian.phone_number = object.phone_number.split(',').map(x=>{return{value:x}})
    // }
  },
  setAffiliateCategoryId(state, id){
    state.affiliate.category_id = id
  }
};
const getters = {
  getData: state => state
};
/**
 ** only for testing XD
 */
const actions = {
  async setLegalGuardian1(context, data){
    await context.commit('setLegalGuardian', data)
  }
};
export default {
  state,
  mutations,
  getters,
  actions,
  namespaced: true
};
