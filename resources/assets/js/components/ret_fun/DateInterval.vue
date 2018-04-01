<template>
    <div class="input-daterange input-group col-md-offset-4 col-md-5">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <input type="date" name="date_availability_start" ref="start" :value="value.start" @change="updateDate()" class="form-control">
        <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
        <input type="date" name="date_availability_start" ref="end" :value="value.end" @change="updateDate()" class="form-control">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <code>
            Anios<strong> {{ years }} </strong>
            Mese<strong> {{ months }} </strong>
        </code>
    </div>

</template>

<script>
export default {
  props: ["value", "indice"],
  mounted() {
    this.calculate();
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
    updateDate() {
      this.calculate();
      this.$emit(
        "setDate",
        {
          start: this.$refs.start.value,
          end: this.$refs.end.value
        },
        this.indice
      );
    }
  },
  data() {
    return {
      months: null,
      years: null
    };
  }
};
</script>
