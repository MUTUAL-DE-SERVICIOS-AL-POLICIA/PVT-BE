<template>
    <div class="col-xs-offset-5">
        <table class="table table-hover no-margins">
            <thead>
            <tr>
                <th colspan="2" class="text-center" style="border-right:1px solid #e7eaec">Desde</th>
                <th colspan="2" class="text-center" style="border-right:1px solid #e7eaec">Hasta</th>
                <th class="text-center">Años</th>
                <th class="text-center">Meses</th>
            </tr>
            </thead>
            <tbody>
            <ret-fun-date-interval v-for="(date,index) in datesChild"
                                   :key="index"
                                   :value="date"
                                   :indice="index"
                                   @setDate="updateDate">
            </ret-fun-date-interval>
        </tbody>
        <tfoot>
            <tr style="background-color: #efefef">
                <td colspan="4" style="border-right:1px solid #e7eaec"> <strong>Total</strong></td>
                <td class="text-center"> <strong>{{ years }}</strong></td>
                <td class="text-center"> <strong>{{ months }}</strong></td>
            </tr>
        </tfoot>
        </table>
    </div>
</template>

<script>
    import RetFunDateInterval from "./DateInterval.vue";
    export default {
      components: {
        RetFunDateInterval
      },
      props: ["datesChild"],
      data() {
        return {
          years: 0,
          months: 0
        };
      },
      mounted() {
        this.calculate();
      },
      methods: {
        updateDate(data, index) {
          this.datesChild[index].start = data.start;
          this.datesChild[index].end = data.end;
          this.calculate();
          this.$emit("total");
        },
        calculate() {
          const x = this.datesChild.reduce((prev, current) => {
            return (
              prev + (moment(current.end).diff(moment(current.start), "months") + 1)
            );
          }, 0);
          this.years = parseInt(x / 12);
          this.months = x % 12;
        }
      }
    };
</script>
