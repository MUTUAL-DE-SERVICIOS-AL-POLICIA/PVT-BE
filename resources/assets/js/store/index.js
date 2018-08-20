import Vue from 'vue';
import Vuex from 'vuex';
 Vue.use(Vuex); 

import retFunForm from './modules/retFun/form'
import quotaAidForm from './modules/quotaAid/form'
import inbox from './modules/inbox'

export default new Vuex.Store({
    modules: {
      retFunForm: retFunForm,
      inbox: inbox,
      quotaAidForm, //quotaAidForm: quotaAidForm
    }
  })