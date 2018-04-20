<script>
import { dateInputMask, moneyInputMask, parseMoney, moneyInputMaskAll }  from "../../helper.js";
export default {
  props: ["datesContributions", "datesAvailability", "datesItemZero","datesSecurityBattalion","datesNoRecords","datesCas","retirementFundId"],
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

      subTotalRetFun: 0,
      totalRetFun: 0,

      advancePayment: 0,
      retentionLoanPayment: 0,
      retentionGuarantor: 0,

      hasAvailability: false,

      subTotalAvailability:0,
      totalAvailability:0,
      total:0,

      beneficiaries: [],
      beneficiariesAvailability: [],

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

        const datesAvailability = this.calculateDiff(this.datesAvailability);
        const datesContributions = this.calculateDiff(this.datesContributions);
        const datesItemZero = this.calculateDiff(this.datesItemZero);
        const datesSecurityBattalion = this.calculateDiff(this.datesSecurityBattalion);
        const datesCas = this.calculateDiff(this.datesCas);
        const datesNoRecords = this.calculateDiff(this.datesNoRecords);
        const total = datesContributions + datesItemZero - datesAvailability - datesSecurityBattalion - datesCas - datesNoRecords;
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
        // this.sub_total_ret_fun = response.data.sub_total_ret_fun;
        // this.total = response.data.total;
        TweenLite.to(this.$data, 0.5, { totalRetFun: response.data.total_ret_fun,subTotalRetFun: response.data.sub_total_ret_fun });
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
      }).catch(error =>{
          flash("Error al guardar los Datos", "error");

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
          this.totalAvailability = response.data.total_availability;
          this.total = response.data.total;
          this.beneficiariesAvailability = response.data.beneficiaries;
      }).catch(error =>{
          flash("Error al guardar los porcentages", "error");
      });
    },
    saveTotalAvailability(){
      let uri =`/ret_fun/${this.retirementFundId}/save_total_availability`;
      axios.get(uri,
        {
          beneficiaries: this.beneficiaries,
        }
      ).then(response =>{
          flash("Total disponibilidad Actualizado.");
          this.totalRetFun = response.data.total_ret_fun;
      }).catch(error =>{
          flash("Error al guardar total disponibilidad", "error");
      });
    },
    requalificationTotalAvailability(index){
      this.beneficiariesAvailability[index].temp_amount_availability = (this.totalAvailability * this.beneficiariesAvailability[index].percentage)/100;
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
    totalPercentage(){
      const sum = this.beneficiaries.reduce((accumulator, current) => {
        return accumulator + parseFloat(current.temp_percentage);
       }, 0.0);
       return sum;
    },
    totalAmount(){
      const sum = this.beneficiaries.reduce((accumulator, current) => {
        return accumulator + parseFloat(current.temp_amount);
       }, 0.0);
       return sum;
    },
  },
};
</script>
