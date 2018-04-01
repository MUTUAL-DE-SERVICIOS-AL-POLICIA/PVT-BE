<script>
export default {
  props: ["datesContributions", "datesAvailability", "datesItemZero"],
  mounted() {
    this.calculate();
  },
  data() {
    return {
      years: 0,
      months: 0
    };
  },
  methods: {
    calculate(){
        const datesAvailability=this.datesAvailability.reduce((prev, current)=>{
            return prev + (moment(current.end).diff(moment(current.start), 'months') + 1);
        }, 0);
        const datesContributions=this.datesContributions.reduce((prev, current)=>{
            return prev + (moment(current.end).diff(moment(current.start), 'months') + 1);
        }, 0);
        const datesItemZero=this.datesItemZero.reduce((prev, current)=>{
            return prev + (moment(current.end).diff(moment(current.start), 'months') + 1);
        }, 0);
        const total = datesContributions + datesItemZero - datesAvailability;
        this.years = parseInt(total/12);
        this.months = (total%12);
    }
  }
};
</script>
