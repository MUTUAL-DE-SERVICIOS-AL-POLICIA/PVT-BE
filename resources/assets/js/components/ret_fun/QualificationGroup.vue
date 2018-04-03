<template>
<div>
  <ret-fun-date-interval
    v-for="(date,index) in datesAvailabilityChild" :key="index"
    :value="date"
    :indice="index"
    @setDate="updateDate"
    >
    </ret-fun-date-interval>
    <div><strong>Años:</strong> {{ years }}</div>
    <div><strong>Meses:</strong> {{ months }}</div>
    <hr>
</div>
</template>

<script>
import RetFunDateInterval from './DateInterval.vue'
export default {
    components:{
        RetFunDateInterval
    },
    props:[
      'datesAvailabilityChild',
    ],
    data(){
        return{
            years:0,
            months:0,
        }
    },
    mounted(){
        this.calculate();
    },
    methods:{
        updateDate(data, index){
            this.datesAvailabilityChild[index].start=data.start;
            this.datesAvailabilityChild[index].end=data.end;
            this.calculate();
            this.$emit('total');
        },
        calculate(){
            const x=this.datesAvailabilityChild.reduce((prev, current)=>{
                return prev + (moment(current.end).diff(moment(current.start), 'months') + 1);
            }, 0);
            this.years = parseInt(x/12);
            this.months = x%12;
        }
    }
}
</script>
