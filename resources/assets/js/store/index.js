import Vue from 'vue';
import Vuex from 'vuex';
 Vue.use(Vuex); 

import retFunForm from './modules/retfun/form'
import quotaAidForm from './modules/quotaAid/form'
import inbox from './modules/inbox'

export default new Vuex.Store({
    modules: {
      retfunform: retFunForm,
      inbox: inbox,
      quotaAidForm, //quotaAidForm: quotaAidForm
    }
  })