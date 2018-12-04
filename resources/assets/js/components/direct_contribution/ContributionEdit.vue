<template>
  <div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th class="footable-visible footable-first-column footable-sortable">Mes/Año</th>
          <th class="footable-visible footable-sortable">Total Ganado Bs.</th>
          <th class="footable-visible footable-sortable">F.R.P. (4.77 %)</th>
          <th class="footable-visible footable-sortable">Cuota Mortuoria (1.09 %)</th>
          <th class="footable-visible footable-sortable">Ajuste UFV Bs.</th>
          <th class="footable-visible footable-sortable">Subtotal Aporte</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(con, index) in contributions"
            :key="index">
          <td><input type="text"
                   v-model="con.month_year"
                   disabled
                   class="form-control"></td>
          <td><input type="text"
                   v-model="con.quotable"
                   v-money
                   disabled
                   class="form-control"></td>
          <td><input type="text"
                   v-model="con.retirement_fund"
                   v-money
                   disabled
                   class="form-control"></td>
          <td><input type="text"
                   v-model="con.mortuary_quota"
                   v-money
                   disabled
                   class="form-control"></td>
          <td><input type="text"
                   v-model="con.interest"
                   disabled
                   v-money
                   class="form-control"></td>
          <td><input type="text"
                   v-model="con.total"
                   v-money
                   disabled
                   class="form-control"></td>
        </tr>
        <tr>
          <td colspan="2"><label for="total">Total a Pagar por Concepto de Aportes:</label></td>
          <td colspan="4"><input type="text"
                   v-model="total"
                   v-money
                   disabled
                   class="form-control"></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
    props:[
        'disabled',
        'directContributionId',
        'affiliateId',
        'contributions',
        'total'
    ],
    mounted() {
        console.log("HOLA");
    }
}
</script>
