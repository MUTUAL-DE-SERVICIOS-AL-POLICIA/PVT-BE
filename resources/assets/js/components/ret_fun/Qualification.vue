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

      showEconomicData:false
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
        months: (date%12)
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
        if (response.data) {
          flash("Verificacion Correcta")
          this.showEconomicData = true;
        }else{
          flash("Los Datos no Coinciden", "error")
          this.showEconomicData = false;
        }
      }).catch(error => alert(error))
    }
  }
};
</script>
