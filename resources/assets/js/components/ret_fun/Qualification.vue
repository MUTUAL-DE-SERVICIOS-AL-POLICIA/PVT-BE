<script>
import { dateInputMask, moneyInputMask, parseMoney, moneyInputMaskAll }  from "../../helper.js";
export default {
  props: [
    'contributions',
    'datesGlobal',
    'datesContributions',
    'datesItemZeroWithContribution',
    'datesItemZeroWithoutContribution',
    'datesSecurityBattalionWithContribution',
    'datesSecurityBattalionWithoutContribution',
    'datesMay1976WithoutContribution',
    'datesCertificationPeriodWithContribution',
    'datesCertificationPeriodWithoutContribution',
    'datesNotWorked',
    'datesAvailability',
    "retirementFundId"
  ],
  mounted() {
    // moneyInputMaskAll();
    this.addGuarantor();
  },
  data() {
    return {


      years: this.contributions.years,
      months: this.contributions.months,
      yearsContributions: 0,
      monthsContributions: 0,
      yearsGlobal: 0,
      monthsGlobal: 0,
      yearsAvailability: 0,
      monthsAvailability: 0,
      yearsItemZero: 0,
      monthsItemZero: 0,
      yearsSecurityBattalion:0,
      monthsSecurityBattalion:0,
      yearsNoRecords:0,
      monthsNoRecords:0,
      yearsCas:0,
      monthsCas:0,

      showEconomicData:false,
      showEconomicDataTotal:false,
      showPercentagesRetFun:false,
      showPercentagesRetFunAvailability:false,

      subTotalRetFun: 0,
      totalRetFun: 0,

      advancePayment: 0,
      retentionLoanPayment: 0,
      retentionGuarantor: 0,

      hasAvailability: false,
      finishRetFun: false,
      finishAvailability: false,

      arrayDiscounts: [],

      subTotalAvailability:0,
      totalAnnualYield:0,
      totalAvailability:0,
      total:0,

      beneficiaries: [],
      beneficiariesAvailability: [],
      beneficiariesRetFunAvailability: [],

      // perecentageAdvancePayment: 0,
      totalAverageSalaryQuotable: 0,
      totalQuotes:0,
      guarantors:[],

    };
  },
  methods: {
    updateTotalGuarantor(){
      this.retentionGuarantor = this.guarantors.reduce((acc, g)=>{
        return acc + parseFloat(parseMoney(g.amount));
      },0);
    },
    addGuarantor(){
      let guarantor = {
        amount: 0,
        identity_card: null,
        full_name: null
      }
      if (this.guarantors.length > 0) {
        if (! this.guarantors.some( g =>  { return !g.full_name || !g.identity_card })) {
          this.guarantors.push(guarantor);
        }
      }else{
        this.guarantors.push(guarantor);
      }
      setTimeout(() => {
        moneyInputMaskAll();
      }, 500);
    },
    deleteGuarantor(index){
      this.guarantors.splice(index,1);
      if(this.guarantors.length < 1)
        this.addGuarantor();
    },
    searchGuarantor(index){
      let ci = this.guarantors[index].identity_card;
      axios.get('/search_ajax', {
        params: {
          ci
        } 
      })
      .then( (response) => {
        let data = response.data;
        this.guarantors[index].full_name = `${data.first_name} ${data.second_name} ${data.last_name} ${data.mothers_last_name} ${data.surname_husband}`;
      })
      .catch(function (error) {
        console.log('error al buscar garante: ', error);
      });
    },
    calculateDiff(dates){
      const diff = dates.reduce((prev, current)=>{
            return prev + (moment(current.end).diff(moment(current.start), 'months') + 1);
        }, 0);
      return diff;
    },
    calculateDiffWithYearMonth(dates){
      let date = this.calculateDiff(dates);
      return{
        years: parseInt(date/12),
        months: (date%12),
      }
    },
    firstContinue(){
      let uri = `/ret_fun/${this.retirementFundId}/get_average_quotable`;
      axios.get(uri,
        {
          datesAvailability: this.datesAvailability,
          datesItemZero: this.datesItemZero,
          datesContributions: this.datesContributions,
          datesSecurityBattalion: this.datesSecurityBattalion,
          datesCas: this.datesCas,
          datesNoRecords: this.datesNoRecords,
        }
      ).then(response =>{
          flash("Verificacion Correcta");
          this.showEconomicData = true
          TweenLite.to(this.$data, 0.5, { totalAverageSalaryQuotable: response.data.total_salary_quotable.total_average_salary_quotable,totalQuotes: response.data.total_quotes });
      }).catch(error =>{
          flash("Error", "error");
          this.showEconomicData = false;
      });
    },
    saveAverageQuotable(){
      let uri=`/ret_fun/${this.retirementFundId}/save_average_quotable`;
      axios.get(uri).then(response => {
        flash("Salario Promedio Cotizable Actualizado")
        this.showEconomicDataTotal = true;
        TweenLite.to(this.$data, 0.5, { totalRetFun: response.data.total_ret_fun,subTotalRetFun: response.data.sub_total_ret_fun });
        moneyInputMaskAll();
      }).catch(error => {
        this.showEconomicDataTotal = false
      });
    },
    saveTotalRetFun(){
      let uri =`/ret_fun/${this.retirementFundId}/save_total_ret_fun`;
      axios.patch(uri,
        {
          advancePayment: parseMoney(this.advancePayment),
          retentionLoanPayment: parseMoney(this.retentionLoanPayment),
          retentionGuarantor: parseMoney(this.retentionGuarantor),
        }
      ).then(response =>{
          flash("Calculo Total guardado correctamente.");
          this.beneficiaries = response.data.beneficiaries;
          this.totalRetFun = response.data.total_ret_fun;
          this.subTotalRetFun = response.data.sub_total_ret_fun;
          this.showPercentagesRetFun = true;
      }).catch(error =>{
          flash("Error al guardar los Datos", "error");
          this.showPercentagesRetFun = false;
      });
    },
    requalificationTotal(index){
      this.beneficiaries[index].temp_amount = (this.totalRetFun * this.beneficiaries[index].temp_percentage)/100;
    },
    savePercentages(){
      let uri =`/ret_fun/${this.retirementFundId}/save_percentages`;
      axios.patch(uri,
        {
          beneficiaries: this.beneficiaries,
        }
      ).then(response =>{
          flash("Porcentages actualizados a los derechohabientes.");
          this.hasAvailability = response.data.has_availability;
          this.subTotalAvailability = response.data.subtotal_availability;
          this.totalAnnualYield = response.data.total_annual_yield;
          this.totalAvailability = response.data.total_availability;
          this.total = response.data.total;
          this.beneficiariesAvailability = response.data.beneficiaries;
          this.finishRetFun = true,
          this.arrayDiscounts = response.data.array_discounts;
          console.log('Has Availability: '+response.data.has_availability);

      }).catch(error =>{
        console.log(error);
          this.finishRetFun = false,
          flash("Error al guardar los porcentages", "error");
      });
    },
    savePercentagesAvailability(){
      let uri =`/ret_fun/${this.retirementFundId}/save_percentages_availability`;
      axios.patch(uri,
        {
          beneficiaries: this.beneficiariesAvailability,
        }
      ).then(response =>{
          flash("Montos de Disponibilidad Actualizados.");
          this.beneficiariesRetFunAvailability = response.data.beneficiaries;
          this.showPercentagesRetFunAvailability = true;
      }).catch(error =>{
          flash("Error al guardar Montos de Disponibilidad", "error");
          this.showPercentagesRetFunAvailability = false;
      });
    },
    saveTotalRetFunAvailability(){
      let uri =`/ret_fun/${this.retirementFundId}/save_total_ret_fun_availability`;
      axios.patch(uri,
        {
          beneficiaries: this.beneficiariesRetFunAvailability,
        }
      ).then(response =>{
          this.finishAvailability =  true,
          flash("Montos de Fondo de Retiro + Disponibilidad Actualizados.");
      }).catch(error =>{
          flash("Error al guardar Montos de Fondo de Retiro + Disponibilidad", "error");
      });
    },
    requalificationTotalAvailability(index){
      this.beneficiariesAvailability[index].temp_amount_availability = (this.totalAvailability * this.beneficiariesAvailability[index].percentage)/100;
    },
    requalificationTotalRetFunAvailability(index){
      this.beneficiariesRetFunAvailability[index].temp_amount_total = (this.total * this.beneficiariesAvailability[index].percentage)/100;
    },
  },
  computed: {
    totalAverageSalaryQuotableAnimated: function() {
      return this.totalAverageSalaryQuotable;
    },
    totalQuotesAnimated: function() {
      return this.totalQuotes;
    },
    subTotalRetFunAnimated(){
      return this.subTotalRetFun;
    },
    totalAnimated(){
      return this.subTotalRetFun - parseMoney(this.advancePayment) -parseMoney(this.retentionLoanPayment) -parseMoney(this.retentionGuarantor);
    },
    percentageAdvancePayment(){
      return (this.subTotalRetFun > 0 && this.subTotalRetFun != '') ?  (100 * parseMoney(this.advancePayment))/this.subTotalRetFun : 0;
    },
    percentageRetentionLoanPayment(){
      return (this.subTotalRetFun > 0 && this.subTotalRetFun != '') ?  (100 * parseMoney(this.retentionLoanPayment))/this.subTotalRetFun : 0;
    },
    percentageRetentionGuarantor(){
      return (this.subTotalRetFun > 0 && this.subTotalRetFun != '') ?  (100 * parseMoney(this.retentionGuarantor))/this.subTotalRetFun : 0;
    },
    totalPercentageRetFun(){
      const sum = this.beneficiaries.reduce((accumulator, current) => {
        return accumulator + parseFloat(current.temp_percentage);
       }, 0.0);
       return sum;
    },
    totalAmountRetFun(){
      const sum = this.beneficiaries.reduce((accumulator, current) => {
        return accumulator + parseFloat(current.temp_amount);
       }, 0.0);
       return sum;
    },
    totalPercentageAvailability(){
      const sum = this.beneficiariesAvailability.reduce((accumulator, current) => {
        return accumulator + parseFloat(current.percentage);
       }, 0.0);
       return sum;
    },
    totalAmountAvailability(){
      const sum = this.beneficiariesAvailability.reduce((accumulator, current) => {
        return accumulator + parseFloat(current.temp_amount_availability);
       }, 0.0);
       return sum;
    },
    totalPercentageRetFunAvailability(){
      const sum = this.beneficiariesRetFunAvailability.reduce((accumulator, current) => {
        return accumulator + parseFloat(current.percentage);
       }, 0.0);
       return sum;
    },
    totalAmountRetFunAvailability(){
      const sum = this.beneficiariesRetFunAvailability.reduce((accumulator, current) => {
        return accumulator + parseFloat(current.temp_amount_total);
       }, 0.0);
       return sum;
    },
  },
};
</script>
