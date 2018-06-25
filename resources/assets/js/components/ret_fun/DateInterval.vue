<template>
  <div class="input-daterange input-group col-md-offset-4 col-md-8" :class="! valid ? 'has-error' : ''" >
      <div class="form-inline m-b-sm">
        <div class="form-group">
            <div class="input-daterange input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input disabled type="date" name="date_availability_start" ref="start" :value="value.start" @change="updateDate()" class="form-control">
                <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                <input disabled type="date" name="date_availability_end" ref="end" :value="value.end" @change="updateDate()" class="form-control">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
        <div class="col-xs-offset-1 inline">
          <button type="button" class="btn" :class="valid_year ? 'btn-outline btn-primary' : 'btn-danger'">Años: <strong>{{ years }}</strong></button>
          <button type="button" class="btn" :class="valid_month ? 'btn-outline btn-primary' : 'btn-danger'">Meses: <strong>{{ months }}</strong></button>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  props: ["value", "indice"],
  mounted() {
    this.calculate();
    this.showErrors();
  },
  data(){
    return{
      valid:true,
      valid_year:true,
      valid_month:true,
      months: null,
      years: null
    }
  },
  methods: {
    calculate() {
      let diff =
        moment(this.$refs.end.value).diff(
          moment(this.$refs.start.value),
          "months"
        ) + 1;
      this.years = parseInt(diff / 12);
      this.months = diff % 12;
    },
    showErrors(){
      if (moment(this.$refs.end.value).diff(moment(this.$refs.start.value),"years") >=0) {
        this.valid_year = true;
      }else{
        this.valid_year = false;
      }
      if (moment(this.$refs.end.value).diff(moment(this.$refs.start.value),"months") >=0) {
        this.valid_month = true;
      }else{
        this.valid_month = false;
      }
    },
    updateDate() {
      let start=this.$refs.start.value;
      let end=this.$refs.end.value;
      let diff = moment(end).diff(moment(start), 'months')+1;
      if (diff > 0) {
      this.valid = true;
      this.calculate();
      this.showErrors();
      this.$emit(
        "setDate",
        {
          start: this.$refs.start.value,
          end: this.$refs.end.value
        },
        this.indice
      );
      }else{
        this.valid = false;
        this.showErrors();
      }
    }
  },
};
</script>
