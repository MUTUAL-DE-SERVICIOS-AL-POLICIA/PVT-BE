<template>
<div>
  <ret-fun-date-interval
    v-for="(date,index) in datesChild" :key="index"
    :value="date"
    :indice="index"
    @setDate="updateDate"
    >
    </ret-fun-date-interval>
    <div class="alert alert-info">
        <div>Años:<strong> {{ years }} </strong></div>
        <div>Meses:<strong> {{ months }} </strong></div>
    </div>
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
      'datesChild',
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
            console.log("received emit", data);
            this.datesChild[index].start=data.start;
            this.datesChild[index].end=data.end;
            this.calculate();
            this.$emit('total');
            console.log("emit total");
        },
        calculate(){
            const x=this.datesChild.reduce((prev, current)=>{
                return prev + (moment(current.end).diff(moment(current.start), 'months') + 1);
            }, 0);
            console.log(x);
            
            this.years = parseInt(x/12);
            this.months = x%12;
        }
    }
}
</script>
