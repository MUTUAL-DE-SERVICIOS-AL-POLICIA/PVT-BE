const state = {
  modality: "",
  procedureModalityName: "",
  pensionEntityName: "",
  modality_id: "",
  city: "",
  id: null,
  isValidated: false,
  correlative: null,

  beneficiary: {
    address: {}
  },
  legalGuardian: {},
  receptionType: {},
  affiliate: {},
  pensionEntityId: null,
  ecoCom: {}
};
const mutations = {
  setModality(state, object) {
    state.procedureModalityName = object.name;
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
    if (object.address.length > 0) {
      state.beneficiary.address = object.address[0];
    }else{
      state.beneficiary.address = {};

    }
  },
  setIsValidated(state, value) {
    state.isValidated = value;
  },
  setCorrelative(state, value) {
    state.correlative = value;
  },
  setPensionEntity(state, object) {
    state.pensionEntityId = object.id;
    state.pensionEntityName = object.name;
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
  setLegalGuardian(state, object) {
    var tt = null;
    if (state.legalGuardian) {
      if (state.legalGuardian.eco_com_legal_guardian_type_id) {
        tt = state.legalGuardian.eco_com_legal_guardian_type_id;
      }
    }
    state.legalGuardian = {};
    if (Object.keys(object).length) {
      state.legalGuardian = object;
    }
    if (!state.legalGuardian.hasOwnProperty("phone_number")) {
      state.legalGuardian.phone_number = [{ value: null }];
    }
    if (!state.legalGuardian.hasOwnProperty("cell_phone_number")) {
      state.legalGuardian.cell_phone_number = [{ value: null }];
    }
    if (tt) {
      state.legalGuardian.eco_com_legal_guardian_type_id = tt;
    }
  },
  setAffiliateCategoryId(state, id) {
    state.affiliate.category_id = id;
  }
};
const getters = {
  getData: state => state
};
/**
 ** only for testing XD
 */
const actions = {
  async setLegalGuardian1(context, data) {
    await context.commit("setLegalGuardian", data);
  }
};
export default {
  state,
  mutations,
  getters,
  actions,
  namespaced: true
};
