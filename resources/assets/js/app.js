
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./inspinia');
require('print-js');

window.Vue = require('vue');

window.events = new Vue();
window.flash = function (message, level = 'success', timeOut = 5000) {
	window.events.$emit('flash', { message, level, timeOut});
};
// console.log = function () {}
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import VModal from 'vue-js-modal'

Vue.use(VModal)


 /**VUEX */
 import store from './store/index';

import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
Vue.use(VueFormWizard);

import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

import Vuetify from 'vuetify'

Vue.use(Vuetify)

import VeeValidate, { Validator } from 'vee-validate';
import es from "vee-validate/dist/locale/es";
Vue.use(VeeValidate, {
  locale: "es",
  fieldsBagName: "vFields",
  dictionary: {
    es: {
      custom: {
        procedure_type_id: {
          required: "Debe seleccionar el tipo de Pago para el Trámite."
        },
        city_end_id: {
          required: "Debe seleccionar la regional del Trámite."
        },
        ret_fun_modality: {
          required: "Debe seleccionar la modalidad del Trámite."
        },
        accountType: {
          required: "Debe seleccionar el tipo de solicitante."
        },
        applicant_identity_card: {
          required: "Debe escribir el ci del solicitante."
        },
        date_derelict: {
          required: "Debe ingresar fecha de desvinculación."
        },
        date_last_contribution: {
          required: "Debe ingresar fecha del último aporte."
        },
        applicant_kinship: {
          required: "Debe seleccionar el parentesco del solicitante."
        },
        identity_card: {
          required: "Debe ingresar la Cédula de identidad."
        },
        nup: {
          required: "Debe ingresar el Número único policial."
        },
        first_name: {
          required: "Debe ingresar el primer nombre.",
          alpha_space_quote:
            "El campo primer nombre solo puede contener caracteres alfabéticos o '."
        },
        second_name: {
          alpha_space_quote:
            "El campo segundo nombre solo puede contener caracteres alfabéticos o '."
        },
        last_name: {
          required: "Debe escribir el apellido paterno.",
          alpha_space_quote:
            "El campo primer nombre solo puede contener caracteres alfabéticos o '."
        },
        mothers_last_name: {
          alpha_space_quote:
            "El campo apellido materno solo puede contener caracteres alfabéticos o '."
        },
        surname_husband: {
          alpha_space_quote:
            "El campo apellido de casada solo puede contener caracteres alfabéticos o '."
        },
        gender: {
          required: "Debe seleccionar el genero."
        },
        birth_date: {
          required: "Debe escribir la fecha de Nacimiento.",
          date_format:
            "Debe escribir la fecha de Nacimiento en el formato dia/mes/año."
        },
        service_years: {
          required: "Debe ingresar los años de servicio",
          numeric: "El campo años de servicio debe ser un numero",
          max_value: "El campo años servicio debe ser menor o igual a 12.",
          min_value: "El campo años de servicio debe ser mayor o igual a 0."
        },
        service_months: {
          required: "Debe ingresar los meses de servicio",
          numeric: "El campo meses de servicio debe ser un numero",
          max_value: "El campo meses servicio debe ser menor o igual a 12.",
          min_value: "El campo meses servicio debe ser mayor o igual a 0."
        }
      }
    }
  }
});

//custom rules
Validator.localize({es:es});
let instance = new Validator();
instance.extend('max_date', {
  getMessage: (field) => `La fecha ingresada no es valida.`,
  validate: (value) => {
    return moment().subtract(18, 'years').diff(moment(value, "DD/MM/YYYY"), "days") > 0;
  }
});
instance.extend('max_due_date', {
  getMessage: (field) => `La fecha ingresada no es valida.`,
  validate: (value) => {
    return moment(value,'DD/MM/YYYY').isBetween(moment(), moment().add(11, 'years'))
  }
});
instance.extend('phone_number', {
  getMessage: (field) => `El teléfono ingresado es incorrecto.`,
  validate: (value) => {
    let regex = /((\(\d{1}\) ?)|(\d{3}-))?\d{3}-\d{3}/i;
    return regex.exec(value) !== null;
  }
});
instance.extend('max_current_date', {
  getMessage: (field) => `La fecha ingresada no debe ser mayor a la fecha actual.`,
  validate: (value) => {
    return moment().diff(moment(value, "DD/MM/YYYY"), "days", true) > 0;
  }
});
instance = new Validator();
instance.extend('max_current_date_month_year', {
  getMessage: (field) => `La fecha ingresada no debe ser mayor a la fecha actual.`,
  validate: (value) => {
    return moment().diff(moment(value, "MM/YYYY"), "months",true) > 0;
  }
});
instance = new Validator();
instance.extend('alpha_space_quote', {
  getMessage: (field) => `El dato ingresado no debe contener números o minúsculas.`,
  validate: (value) => {
    let regex = /^[A-ZÁÉÍÑÓÚÜ\s\'\.]*$/;
    return regex.exec(value) !== null;
  }
});

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

import VueCurrencyFilter from 'vue-currency-filter';

Vue.use(VueCurrencyFilter,
{
	symbol: 'Bs',
	thousandsSeparator: ',',
	fractionCount: 2,
	fractionSeparator: '.',
	symbolPosition: 'front',
	symbolSpacing: true
});

Vue.filter('percentage', function (value) {
	return `${value.toFixed(2)} %`;
});
moment.locale("es");
Vue.filter('month', function (value) {
  return moment(value).format("MMMM").toString().toUpperCase();
});
Vue.filter('year', function (value) {
  return moment(value).format("YYYY");
});
Vue.filter('formatDateInbox', function (value) {
  return moment(value).format("DD MMMM YYYY");
});
Vue.filter('recordDate', function (value) {
  return moment(value).format("DD MMM YYYY");
});
Vue.filter('recordHour', function (value) {
  return moment(value).format("ddd HH:mm:ss");
});
Vue.filter('uppercase', function (value) {
  return value.toUpperCase();
});
Vue.filter('monthYear', function (value) {
  return moment(value).format("MM/Y");
});
Vue.filter('textDate', function (value) {
  return moment(value).format("DD MMM YYYY");
});



// custom directives
Vue.directive('phone', {
  inserted: function (el) {
    Inputmask("(9) 999-999").mask(el);
  }
});
Vue.directive('cell-phone', {
  inserted: function (el) {
    Inputmask("(999)-99999").mask(el);
  }
});
let dateInputMask = {
  alias: "date",
  inputFormat: "dd/mm/yyyy"
}
Vue.directive('date',{
  inserted: function(el) {
    Inputmask(dateInputMask).mask(el);
  }
})
let dateMonthYearInputMask = {
  alias: "mm/yyyy"
}
Vue.directive('month-year',{
  inserted: function(el) {
    Inputmask(dateMonthYearInputMask).mask(el);
  }
})
let moneyInputMask = {
  alias: "numeric",
  groupSeparator: ",",
  autoGroup: true,
  digits: 2,
  digitsOptional: false,
  prefix: "Bs ",
  placeholder: "0",
  max:1000000000
};
Vue.directive('money',{
  inserted: function(el) {
    Inputmask(moneyInputMask).mask(el);
  }
})

//vue mask hdp
import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask)

/* tabs */
import VueTabs from 'vue-nav-tabs'
import "vue-nav-tabs/themes/vue-tabs.css";
Vue.use(VueTabs)


// import { Tabs, Tab } from 'vue-tabs-component';

// Vue.component('tabs', Tabs);
// Vue.component('tab', Tab);

import VueScrollTo from 'vue-scrollto';

Vue.use(VueScrollTo, {
  container: "body",
  duration: 500,
  easing: "ease",
  offset: 0,
  cancelable: true,
  onStart: false,
  onDone: false,
  onCancel: false,
  x: false,
  y: true
})





/* Components */

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('check-svg', require('./components/CheckSvg.vue'));

//setting files
Vue.component('ret-fun-procedure', require('./components/setting/RetFunProcedure.vue'));

Vue.component('affiliate-index', require('./components/affiliate/Index.vue'));
Vue.component('affiliate-show', require('./components/affiliate/ShowAffiliate.vue'));
Vue.component('affiliate-police', require('./components/affiliate/Police.vue'));
//Vue.component('affiliate-create', require('./components/affiliate/CreateAffiliate.vue'));
Vue.component('affiliate-observations', require('./components/affiliate/Observations.vue'));
Vue.component('affiliate-devolutions', require('./components/affiliate/Devolutions.vue'));
Vue.component('affiliate-record', require('./components/affiliate/Record.vue'));
Vue.component('affiliate-notes', require('./components/affiliate/Notes.vue'));
Vue.component('affiliate-photo', require('./components/affiliate/Photo.vue'));
Vue.component('spouse-show', require('./components/spouse/ShowSpouse.vue'));
//retirement Fund

Vue.component('ret-fun-index', require('./components/ret_fun/Index.vue'));
Vue.component('ret-fun-form', require('./components/ret_fun/Form.vue'));
Vue.component('ret-fun-create-info', require('./components/ret_fun/CreateInfo.vue'));
Vue.component('ret-fun-step1-requirements', require('./components/ret_fun/Step1Requirements.vue'));
Vue.component('ret-fun-step1-requirements-edit', require('./components/ret_fun/Step1RequirementsEdit.vue'));
Vue.component('ret-fun-step2-applicant', require('./components/ret_fun/Step2Applicant.vue'));
Vue.component('ret-fun-step3-beneficiaries', require('./components/ret_fun/Step3Beneficiaries.vue'));
Vue.component('ret-fun-beneficiary-list', require('./components/ret_fun/BeneficiaryList.vue'));
Vue.component('ret-fun-beneficiary', require('./components/ret_fun/Beneficiary.vue'));
Vue.component('ret-fun-info', require('./components/ret_fun/Info.vue'));
Vue.component('ret-fun-beneficiaries-show', require('./components/ret_fun/ShowBeneficiaries.vue'));
Vue.component('ret-fun-qualification', require('./components/ret_fun/Qualification.vue'));
Vue.component('ret-fun-date-interval', require('./components/ret_fun/DateInterval.vue'));
Vue.component('ret-fun-qualification-group', require('./components/ret_fun/QualificationGroup.vue'));
Vue.component('ret-fun-certification-button', require('./components/ret_fun/CertificationButton.vue'));
Vue.component('ret-fun-chart', require('./components/ret_fun/Chart.vue'));
Vue.component('inbox-send-back-button-ret-fun', require('./components/inbox/SendBackButtonRetFun.vue'));
Vue.component('inbox-send-back-button-quota-aid', require('./components/inbox/SendBackButtonQuotaAid.vue'));
Vue.component('ret-fun-report-form', require('./components/ret_fun/ReportForm.vue'));
Vue.component('ret-fun-beneficiary-testimony-list', require('./components/ret_fun/BeneficiaryTestimonyList.vue'));
Vue.component('ret-fun-beneficiary-testimony', require('./components/ret_fun/BeneficiaryTestimony.vue'));

Vue.component('summary-select-contributions', require('./components/contribution/SummarySelectContributions.vue'));

Vue.component('generate-charge', require('./components/voucher/GenerateCharge.vue'));
Vue.component('ret-fun-alert', require('./components/ret_fun/Alert.vue'));
Vue.component('ret-fun-judicial-retention', require('./components/ret_fun/JudicialRetention.vue'));
/**
 * Treasury components
 */
Vue.component('treasury-select-report', require('./components/treasury/SelectReport.vue'));

Vue.component("chosen-select", {
  props: {
    value: [String, Array],
    multiple: Boolean
  },
  template: `<select :multiple="multiple"><slot></slot></select>`,
  mounted() {
    $(this.$el)
      .val(this.value)
      .chosen({ width: "100%" })
      .on("change", e => this.$emit("input", $(this.$el).val()));
  },
  watch: {
    value(val) {
      $(this.$el).val(val).trigger('chosen:updated');
    }
  }
})
// inbox
Vue.component('tabs-content', require('./components/inbox/TabsContent.vue'));
Vue.component('inbox-content', require('./components/inbox/Content.vue'));

//tags
Vue.component('tag-list', require('./components/tag/TagList.vue'));
Vue.component('tag-create', require('./components/tag/Create.vue'));
Vue.component('tag-wf-state', require('./components/tag/WfState.vue'));


// Quota Aid Mortuaries
Vue.component('quota-aid-mortuary-index', require('./components/quota_aid/Index.vue'));
Vue.component('quota-aid-form', require('./components/quota_aid/Form.vue'));
Vue.component('quota-aid-create-info', require('./components/quota_aid/CreateInfo.vue'));

//quota_aid
Vue.component('quota-aid-step1-requirements', require('./components/quota_aid/Step1Requirements.vue'));
Vue.component('quota-aid-step2-applicant', require('./components/quota_aid/Step2Applicant.vue'));
Vue.component('quota-aid-step3-beneficiaries', require('./components/quota_aid/Step3Beneficiaries.vue'));
Vue.component('quota-aid-beneficiary-list', require('./components/quota_aid/BeneficiaryList.vue'));
Vue.component('quota-aid-beneficiary', require('./components/quota_aid/Beneficiary.vue'));
Vue.component('quota-aid-info', require('./components/quota_aid/Info.vue'));
Vue.component('quota-aid-beneficiaries-show', require('./components/quota_aid/ShowBeneficiaries.vue'));
Vue.component('quota-aid-step1-requirements-edit', require('./components/quota_aid/Step1RequirementsEdit.vue'));
Vue.component('quota-aid-certification-button', require('./components/quota_aid/CertificationButton.vue'));
Vue.component('quota-aid-qualification', require('./components/quota_aid/Qualification.vue'));
Vue.component('quota-aid-qualification-group', require('./components/quota_aid/QualificationGroup.vue'));
Vue.component('quota-aid-date-interval', require('./components/quota_aid/DateInterval.vue'));
Vue.component('quota-aid-judicial-retention', require('./components/quota_aid/JudicialRetention.vue'));
//user
Vue.component('show-password', require('./components/user/ShowPassword.vue'));
//permission
Vue.component('nom-module', require('./components/permission/NomModule.vue'));

//contributions
Vue.component('contribution-create', require('./components/contribution/CreateContribution.vue'));
Vue.component('contribution-commitment', require('./components/contribution/Commitment.vue'));
Vue.component('contribution-select', require('./components/contribution/SelectContributions.vue'));
Vue.component('contribution-select-quota-aid', require('./components/contribution/SelectContributionsQuotaAid.vue'));
Vue.component('buttons-print-contributions', require('./components/contribution/ButtonsPrintContributions.vue'));

// direct contribution
Vue.component('direct-contribution-info', require('./components/direct_contribution/Info.vue'));
Vue.component("direct-contribution-form", require("./components/direct_contribution/Form.vue"));
Vue.component("direct-contribution-step1-requirements", require("./components/direct_contribution/Step1Requirements.vue"));
Vue.component('direct-contribution-step1-requirements-edit', require('./components/direct_contribution/Step1RequirementsEdit.vue'));
Vue.component("direct-contribution-step2-contributor", require("./components/direct_contribution/Step2Contributor.vue"));
Vue.component("direct-contribution-step3-letter", require("./components/direct_contribution/Step3Letter.vue"));
Vue.component("direct-contribution-payment", require("./components/direct_contribution/Payment.vue"));
Vue.component("aid-contribution-edit", require("./components/direct_contribution/AidContributionEdit.vue"));
Vue.component("contribution-edit", require("./components/direct_contribution/ContributionEdit.vue"));

//aid-contributions
Vue.component('aid-contribution-create', require('./components/contribution/CreateAidContribution.vue'));
Vue.component('contribution-aid-commitment',require('./components/contribution/AidCommitment.vue'));

// Eco Com
Vue.component('eco-com-dashboard', require('./components/eco_com/Dashboard.vue'));
Vue.component('pie-bar', require('./components/eco_com/dashboard/PieBar.vue'));
Vue.component('multiple', require('./components/eco_com/dashboard/Multiple.vue'));
Vue.component('eco-com-form', require('./components/eco_com/Form.vue'));
Vue.component('eco-com-step1-requirements', require('./components/eco_com/Step1Requirements.vue'));
Vue.component('eco-com-step2-beneficiary', require('./components/eco_com/Step2Beneficiary.vue'));
Vue.component('eco-com-step3-rents', require('./components/eco_com/Step3Rents.vue'));
Vue.component('eco-com-create-info', require('./components/eco_com/CreateInfo.vue'));
Vue.component('eco-com-info', require('./components/eco_com/Info.vue'));

Vue.component('eco-com-search-affiliate', require('./components/eco_com/SearchAffiliate.vue'));
Vue.component('eco-com-beneficiary', require('./components/eco_com/Beneficiary.vue'));
Vue.component('eco-com-legal-guardian', require('./components/eco_com/LegalGuardian.vue'));
Vue.component('eco-com-step1-requirements-edit', require('./components/eco_com/Step1RequirementsEdit.vue'));
Vue.component('eco-com-observations', require('./components/eco_com/Observations.vue'));
Vue.component('eco-com-qualification', require('./components/eco_com/Qualification.vue'));
Vue.component('eco-com-review', require('./components/eco_com/Review.vue'));
Vue.component('eco-com-amortization', require('./components/eco_com/Amortization.vue'));

Vue.component('eco-com-record', require('./components/eco_com/Record.vue'));
Vue.component('eco-com-notes', require('./components/eco_com/Notes.vue'));
Vue.component('eco-com-procedure', require('./components/eco_com/Procedure.vue'));
Vue.component('eco-com-select-report', require('./components/eco_com/SelectReport.vue'));
Vue.component('eco-com-import-rents', require('./components/eco_com/ImportRents.vue'));
Vue.component('eco-com-import-rents-aps', require('./components/eco_com/ImportRentsAPS.vue'));
Vue.component('eco-com-replication-procedures', require('./components/eco_com/Replication.vue'));
Vue.component('eco-com-import-pago-futuro', require('./components/eco_com/ImportPagoFuturo.vue'));
Vue.component('eco-com-update-paid-bank', require('./components/eco_com/UpdatePaidBank.vue'));
Vue.component('eco-com-automatic-qualification', require('./components/eco_com/AutomaticQualification.vue'));

Vue.component('eco-com-estado-pagado', require('./components/eco_com/EstadoPagado.vue'));

Vue.component('edit-pension-modal', require('./components/eco_com/EditPensionModal.vue'));
// utils
Vue.component('sweet-alert-modal', require('./components/utils/SweetAlertModal.vue'));
Vue.component('correlative', require('./components/utils/Correlative.vue'));
Vue.component('certification-button', require('./components/utils/CertificationButton.vue'));
// Edit user
Vue.component('edit-user', require('./components/user/EditUser.vue'));

// Dispositivo movil


Vue.component('eco-com-loadaverages', require('./components/eco_com/LoadAveranges.vue'));

/**
 * custom component
 */
Vue.component('animated-integer', {
  template: '<span>{{ tweeningValue }}</span>',
  props: {
    value: {
      type: Number,
      required: true
    }
  },
  data: function () {
    return {
      tweeningValue: 0
    }
  },
  watch: {
    value: function (newValue, oldValue) {
      this.tween(oldValue, newValue)
    }
  },
  mounted: function () {
    this.tween(0, this.value)
  },
  methods: {
    tween: function (startValue, endValue) {
      var vm = this
      function animate () {
        if (TWEEN.update()) {
          requestAnimationFrame(animate)
        }
      }

      new TWEEN.Tween({ tweeningValue: startValue })
        .to({ tweeningValue: endValue }, 500)
        .onUpdate(function () {
        // !! TODO set format currency
          vm.tweeningValue = 'Bs '+ this.tweeningValue.toFixed(2)
        })
        .start()

      animate()
    }
  }
})
const app = new Vue({
  el: '#app',
  store

});