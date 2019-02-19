const state = {
    id: null,
    isValidated: false,
    correlative: null,
}
const mutations = {
    setIsValidated(state, value) {
        state.isValidated = value;
    },
    setCorrelative(state, value) {
        state.correlative = value;
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
