<script>
export default {
  props:[
      'datesContributions',
      'datesAvailability',
      'datesItemZero',
  ],
  mounted(){
    this.datesAvailability.forEach(date => {

      console.log(moment(date.end).diff(moment(date.start), 'months')+1);
    })
  },
  data(){
    return{
      
    }
  },
  computed:{
    totalMonths(index){
      return moment(this.datesAvailability[index]).add(5, 'days');
    }
  }
}
</script>
