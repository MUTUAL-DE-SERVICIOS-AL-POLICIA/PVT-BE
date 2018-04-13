<script>
export default {
  props: ["datesContributions", "datesAvailability", "datesItemZero","retirementFundId"],
  mounted() {
    this.calculate();
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

      showEconomicData:false,
      showEconomicDataTotal:false,

      subTotal: 0,
      total: 0,

      advancePayment: 0,
      retentionLoanPayment: 0,
      retentionGuarantor: 0,


      beneficiaries: [],

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

        const datesAvailability = this.calculateDiff(this.datesAvailability);
        const datesContributions = this.calculateDiff(this.datesContributions);
        const datesItemZero = this.calculateDiff(this.datesItemZero);
        const total = datesContributions + datesItemZero - datesAvailability;
        this.years = parseInt(total/12);
        this.months = (total%12);
    },
    save(){
      let uri = `/ret_fun/${this.retirementFundId}/get_data_qualification`;
      axios.post(uri,
        {
          datesAvailability: this.datesAvailability,
          datesItemZero: this.datesItemZero,
          datesContributions: this.datesContributions
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
        // this.sub_total = response.data.sub_total;
        // this.total = response.data.total;
        TweenLite.to(this.$data, 0.5, { total: response.data.total,subTotal: response.data.sub_total });
      }).catch(error => {
        this.showEconomicDataTotal = false
      });
    },
    saveTotal(){
      let uri =`/ret_fun/${this.retirementFundId}/save_total`;
      axios.patch(uri,
        {
          advancePayment: this.advancePayment,
          retentionLoanPayment: this.retentionLoanPayment,
          retentionGuarantor: this.retentionGuarantor,
        }
      ).then(response =>{
          flash("Calculo Total guardado correctamente.");
          // this.showEconomicData = true
          this.beneficiaries = response.data.beneficiaries;
          console.log(response.data);
          this.total = response.data.total;
          this.subTotal = response.data.sub_total;
          console.log(`${this.total} ${this.subTotal} `);
          // TweenLite.to(this.$data, 0.5, { totalAverageSalaryQuotable: response.data.total_average_salary_quotable,totalQuotes: response.data.total_quotes });
      }).catch(error =>{
          flash("Error al guardar los Datos", "error");
          // this.showEconomicData = false;
      });
    },
    requalificationTotal(index){
      this.beneficiaries[index].temp_amount = (this.total * this.beneficiaries[index].temp_percentage)/100;
    },
    savePercentages(){

      let uri =`/ret_fun/${this.retirementFundId}/save_percentages`;
      axios.patch(uri,
        {
          beneficiaries: this.beneficiaries,
        }
      ).then(response =>{
          flash("Porcentages actualizados a los derechohabientes.");
          console.log(response.data);
          this.beneficiaries = response.data.beneficiaries;
          this.total = response.data.total;
          this.subTotal = response.data.sub_total;
          console.log(`${this.total} ${this.subTotal} `);
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
          this.total = response.data.total;
      }).catch(error =>{
          flash("Error al guardar total disponibilidad", "error");
      });
    },
  },
  computed: {
    totalAverageSalaryQuotableAnimated: function() {
      return this.totalAverageSalaryQuotable;
    },
    totalQuotesAnimated: function() {
      return this.totalQuotes;
    },
    subTotalAnimated(){
      return this.subTotal;
    },
    totalAnimated(){
      return this.subTotal - this.advancePayment -this.retentionLoanPayment -this.retentionGuarantor;
    },
    percentageAdvancePayment(){
      return (100 * this.advancePayment)/this.subTotal;
    },
    percentageRetentionLoanPayment(){
      return (100 * this.retentionLoanPayment)/this.subTotal;
    },
    percentageRetentionGuarantor(){
      return (100 * this.retentionGuarantor)/this.subTotal;
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
