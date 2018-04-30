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
      phone_number: null,
      cell_phone_number: null,
    }
}
const mutations = {
    setModality(state, object){
        state.modality = object.name;
        state.modality_id = object.id;
    },
    setCity(state, value){
        state.city = value;
    },
    setApplicant(state, object){
      state.applicant.type =object.type;
      state.applicant.first_name =object.first_name;
      state.applicant.second_name =object.second_name;
      state.applicant.last_name =object.last_name;
      state.applicant.mothers_last_name =object.mothers_last_name;
      state.applicant.surname_husband =object.surname_husband;
      state.applicant.identity_card =object.identity_card;
      state.applicant.city_identity_card_id =object.city_identity_card_id;
      state.applicant.kinship_id =object.kinship_id;
      state.applicant.birth_date =object.birth_date;
      state.applicant.phone_number =object.phone_number;
      state.applicant.cell_phone_number =object.cell_phone_number;
    }
}
const getters = {
    getData: state => state
}
export default{
    state,
    mutations,
    getters,
}
