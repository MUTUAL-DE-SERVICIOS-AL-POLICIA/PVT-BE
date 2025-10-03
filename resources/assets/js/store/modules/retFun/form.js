const state ={
    modality:'',
    modality_id:'',
    city:'',
    applicant: {
      type: null,
      first_name: null,
      second_name: null,
      last_name: null,
      mothers_last_name: null,
      surname_husband: null,
      identity_card: null,
      city_identity_card_id: null,
      kinship_id: null,
      birth_date: null,
      gender: null,
      phone_number: null,
      cell_phone_number: null,
    },
    contributionTypes:[],
    id: null,
    isValidated: false,
    correlative: null,
}
const mutations = {
  setModality(state, object) {
    state.modality = object.name;
    state.modality_id = object.id;
  },
  setCity(state, value) {
    state.city = value;
  },
  setApplicant(state, object) {
    state.applicant.type = object.type;
    state.applicant.first_name = object.first_name;
    state.applicant.second_name = object.second_name;
    state.applicant.last_name = object.last_name;
    state.applicant.mothers_last_name = object.mothers_last_name;
    state.applicant.surname_husband = object.surname_husband;
    state.applicant.identity_card = object.identity_card;
    state.applicant.city_identity_card_id = object.city_identity_card_id;
    state.applicant.kinship_id = object.kinship_id;
    state.applicant.birth_date = object.birth_date;
    state.applicant.gender = object.gender;
    state.applicant.phone_number = object.phone_number;
    state.applicant.cell_phone_number = object.cell_phone_number;
  },
  setContributionTypes(state, obj) {
    state.id = obj.id;
    state.contributionTypes = [];

    const config = [
      {
        ids: [2, 3],
        id: 2,
        name: "Item 0",
        path: "print/cer_itemcero",
      },
      {
        ids: [4, 5],
        id: 4,
        name: "Batallón de Seguridad Fisica",
        path: "print/security_certification",
      },
      {
        ids: [7, 8, 9],
        id: 7,
        name: "Certificación",
        path: "print/contributions_certification",
      },
      {
        ids: [1],
        id: 1,
        name: "60 Aportes",
        path: "print/certification",
      },
      {
        ids: [10],
        id: 10,
        name: "Disponibilidad",
        path: "print/cer_availability",
      },
      {
        ids: [12, 13],
        id: 12,
        name: "Disponibilidad C/S",
        path: "print/cer_availability_new",
      },
      {
        ids: [15],
        id: 15,
        name: "Aportes Devueltos",
        path: "print/cer_devolution",
      },
      {
        ids: [37],
        id: 37,
        name: "Aportes Superior 30 años",
        path: "print/cer_additional_stay",
      },
    ];

    config.forEach(({ ids, id, name, path }) => {
      const match = obj.contributionTypes.find(item => ids.includes(item.id));
      if (match) {
        state.contributionTypes.push({
          id,
          name,
          path,
          message: match.message || "",
        });
      }
    });
  },
  resetContributionTypes(state, array){
      state.contributionTypes = array;
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
