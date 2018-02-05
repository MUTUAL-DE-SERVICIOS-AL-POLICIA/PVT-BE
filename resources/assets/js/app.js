
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./inspinia');

window.Vue = require('vue');

window.events = new Vue();
window.flash = function (message, level = 'success') {
	window.events.$emit('flash', { message, level });
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
Vue.use(VueFormWizard)

Vue.component('flash', require('./components/Flash.vue'));

Vue.component('example', require('./components/Example.vue'));

// Vue.component('example-one', require('./components/affiliate/ExampleOne.vue'));
// Vue.component('listss', require('./components/affiliate/List.vue'));
Vue.component('affiliate-index', require('./components/affiliate/Index.vue'));
Vue.component('affiliate-show', require('./components/affiliate/ShowAffiliate.vue'));
Vue.component('affiliate-police', require('./components/affiliate/Police.vue'));
//Vue.component('form-wizard-ret-fund', require('./components/FormWizard.vue'));
Vue.component('temp', require('./components/temp.vue'));
Vue.component('step1-requirements', require('./components/ret_fun/Step1Requirements.vue'));
Vue.component('step2-applicant', require('./components/ret_fun/Step2Applicant.vue'));
Vue.component('step3-beneficiaries', require('./components/ret_fun/Step3Beneficiaries.vue'));
Vue.component('beneficiary-list', require('./components/ret_fun/BeneficiaryList.vue'));
Vue.component('beneficiary', require('./components/ret_fun/Beneficiary.vue'));

const app = new Vue({
    el: '#app',
});

