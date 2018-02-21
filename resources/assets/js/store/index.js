import Vue from 'vue';
import Vuex from 'vuex';
 Vue.use(Vuex); 

import retFunForm from './modules/retfun/form'

export default new Vuex.Store({
    modules: {
      retfunform: retFunForm
    }
  })