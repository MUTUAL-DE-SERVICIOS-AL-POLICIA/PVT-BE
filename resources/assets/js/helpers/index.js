import Complemento from './eco_com';
import Affiliate from './affiliate';
export default {
  install(Vue) {
    Vue.use(Complemento);
    Vue.use(Affiliate);
  }
};