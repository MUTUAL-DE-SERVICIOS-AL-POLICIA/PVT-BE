<template>
    <tr>
      <td>
        <i class="fa fa-calendar m-r-sm"></i>
        <span ref="start">{{value.start | month}}</span>
      </td>
      <td style="border-right:1px solid #e7eaec">
        <span ref="start">{{value.start | year}}</span>
      </td>
      <td>
        <span ref="end">{{value.end | month}}</span>
      </td>
      <td style="border-right:1px solid #e7eaec">
        <span ref="end" class="m-r-sm">{{value.end | year}}</span>
          <i class="fa fa-calendar"></i>
      </td>
      <td class="text-center">
        {{years}}
      </td>
      <td class="text-center">
        {{months}}
      </td>
    </tr>
  </template>
  <script>
    export default {
      props: ["value", "indice"],
      mounted() {
        this.calculate();
        this.showErrors();
      },
      data() {
        return {
          valid: true,
          valid_year: true,
          valid_month: true,
          months: null,
          years: null
        };
      },
      methods: {
        calculate() {
          let diff =
            moment(this.value.end).diff(
              moment(this.value.start),
              "months"
            ) + 1;
          this.years = parseInt(diff / 12);
          this.months = diff % 12;
        },
        showErrors() {
          if (
            moment(this.value.end).diff(
              moment(this.value.start),
              "years"
            ) >= 0
          ) {
            this.valid_year = true;
          } else {
            this.valid_year = false;
          }
          if (
            moment(this.value.end).diff(
              moment(this.value.start),
              "months"
            ) >= 0
          ) {
            this.valid_month = true;
          } else {
            this.valid_month = false;
          }
        },
        updateDate() {
          let start = this.value.start;
          let end = this.value.end;
          let diff = moment(end).diff(moment(start), "months") + 1;
          if (diff > 0) {
            this.valid = true;
            this.calculate();
            this.showErrors();
            this.$emit(
              "setDate",
              {
                start: this.value.start,
                end: this.value.end
              },
              this.indice
            );
          } else {
            this.valid = false;
            this.showErrors();
          }
        }
      }
    };
  </script>
  