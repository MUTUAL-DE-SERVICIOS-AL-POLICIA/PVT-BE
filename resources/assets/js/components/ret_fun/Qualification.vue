<script>
import { dateInputMask, moneyInputMask, parseMoney, moneyInputMaskAll }  from "../../helper.js";
export default {
  props: ["datesGlobal","datesContributions", "datesAvailability", "datesItemZero","datesSecurityBattalion","datesNoRecords","datesCas","retirementFundId"],
  mounted() {
    this.calculate();
    moneyInputMaskAll();
  },
  data() {
    return {
      years: 0,
      months: 0,
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
    };
  },
  methods: {
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
    calculate(){
        let global = this.calculateDiffWithYearMonth(this.datesGlobal);
        this.yearsGlobal = global.years;
        this.monthsGlobal = global.months;
        let totalContributions = this.calculateDiffWithYearMonth(this.datesContributions);
        this.yearsContributions = totalContributions.years;
        this.monthsContributions = totalContributions.months;
        let totalAvailability = this.calculateDiffWithYearMonth(this.datesAvailability);
        this.yearsAvailability = totalAvailability.years;
        this.monthsAvailability = totalAvailability.months;
        let totalItemZero = this.calculateDiffWithYearMonth(this.datesItemZero);
        this.yearsItemZero = totalItemZero.years;
        this.monthsItemZero = totalItemZero.months;
        let totalSecurityBattalion = this.calculateDiffWithYearMonth(this.datesSecurityBattalion);
        this.yearsSecurityBattalion = totalSecurityBattalion.years;
        this.monthsSecurityBattalion = totalSecurityBattalion.months;
        let totalCas = this.calculateDiffWithYearMonth(this.datesCas);
        this.yearsCas = totalCas.years;
        this.monthsCas = totalCas.months;
        let totalNoRecords = this.calculateDiffWithYearMonth(this.datesNoRecords);
        this.yearsNoRecords = totalNoRecords.years;
        this.monthsNoRecords = totalNoRecords.months;

        const datesGlobal = this.calculateDiff(this.datesGlobal);
        const datesContributions = this.calculateDiff(this.datesContributions);
        const datesAvailability = this.calculateDiff(this.datesAvailability);
        const datesItemZero = this.calculateDiff(this.datesItemZero);
        const datesSecurityBattalion = this.calculateDiff(this.datesSecurityBattalion);
        const datesCas = this.calculateDiff(this.datesCas);
        const datesNoRecords = this.calculateDiff(this.datesNoRecords);
        // const total = datesContributions + datesItemZero - datesAvailability - datesSecurityBattalion - datesCas - datesNoRecords;
        const total = datesGlobal - datesAvailability - datesSecurityBattalion - datesCas - datesNoRecords;
        this.years = parseInt(total/12);
        this.months = (total%12);
    },
    save(){
      let uri = `/ret_fun/${this.retirementFundId}/get_data_qualification`;
      axios.post(uri,
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
          TweenLite.to(this.$data, 0.5, { totalAverageSalaryQuotable: response.data.total_average_salary_quotable,totalQuotes: response.data.total_quotes });
      }).catch(error =>{
          flash("Los Datos no Coinciden", "error");
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

          this.arrayDiscounts = response.data.array_discounts;
          console.log(response.data.array_discounts);

      }).catch(error =>{
        console.log(error);
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
