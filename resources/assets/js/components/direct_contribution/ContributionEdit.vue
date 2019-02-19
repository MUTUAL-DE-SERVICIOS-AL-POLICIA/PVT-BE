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
        <tr v-for="(con, index) in contributionsAndReimbursements"
            :key="index" :class="{'warning' : con.is_reimbursement}">
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
  
    <div class="row text-center">                
        <button class="btn btn-danger" type="button" title="Anular" @click="deleteProcess()">
          <i class="fa fa-trash"></i>
            Anular
        </button>
    </div>
  </div>

</template>

<script>
export default {
    props:[
        'disabled',
        'directContributionId',
        'affiliateId',
        'contributions',
        'reimbursements',
        'total',
        'contributionProcessId'
    ],
    mounted() {
        console.log("HOLA");
    },
    methods: {
      deleteProcess() {
        this.$swal({
            title: 'Está seguro de anular la liquidación?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                  var id = this.contributionProcessId;
                  axios.delete('/contribution_process/'+id)
                  .then(response => {
                    console.log(response.data);
                    location.reload();
                  })
                  .catch(error => {
                  this.show_spinner = false;                    
                      var resp = error.response.data;
                      $.each(resp, function(index, value)
                      {
                          flash(value,'error',6000);
                      });
                  })
                }
            })
      }
    },
    computed: {
      contributionsAndReimbursements(){
        let temp = JSON.parse(JSON.stringify(this.contributions)) ;
        temp.forEach(x => {
            x['is_contribution'] = true;
        });
        let temp1 = JSON.parse(JSON.stringify(this.reimbursements));
        temp1.forEach(x => {
            x['is_reimbursement'] = true;
        });
        Array.prototype.push.apply(temp,temp1);
        temp.sort((a,b) => (a.month_year > b.month_year) ? 1 : ((b.month_year > a.month_year) ? -1 : 0));
        return temp;
      }
    }
}
</script>
