<template>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="footable-visible footable-first-column footable-sortable">Mes/Año</th>
                    <th class="footable-visible footable-sortable">Renta Bs.</th>
                    <th class="footable-visible footable-sortable">Renta Dignidad Bs.</th>
                    <th class="footable-visible footable-sortable">Cotizable Bs.</th>
                    <th class="footable-visible footable-sortable">Auxilio Mortuorio (2.03 %)</th>
                    <th class="footable-visible footable-sortable">Ajuste UFV Bs.</th>
                    <th class="footable-visible footable-sortable">Subtotal Aporte</th>
                    <!-- <th>Opciones</th> -->
                </tr>
            </thead>
            <tbody>
                <!-- v-bind:style="getStyleColor(index)"
                    :class="{'danger': error(ac.subtotal)}" -->
                <tr v-for="(ac, index) in aidContributionsAndReimbursements"
                    :key="index" :class="{'warning' : ac.is_reimbursement}">
                    <td>
                        <input type="text"
                               v-model="ac.month_year"
                               disabled
                               class="form-control">
                    </td>
                    <td>
                        <input type="text"
                               v-model="ac.rent"
                               v-money
                               disabled
                               class="form-control">
                    </td>
                    <td>
                        <input type="text"
                               v-model="ac.dignity_rent"
                               v-money
                               disabled
                               class="form-control">
                    </td>
                    <td>
                        <input type="text"
                               v-model="ac.quotable"
                               v-money
                               disabled
                               class="form-control">
                    </td>
                    <td>
                        <input type="text"
                               v-model="ac.mortuary_aid"
                               disabled
                               v-money
                               class="form-control">
                    </td>
                    <td>
                        <input type="text"
                               v-model="ac.interest"
                               disabled
                               v-money
                               class="form-control">
                    </td>
                    <td>
                        <input type="text"
                               v-model="ac.total"
                               disabled
                               v-money
                               class="form-control">
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><label for="total">Total a Pagar por Concepto de Aportes de Auxilio Mortuorio:</label></td>
                    <td colspan="3"><input type="text"
                               v-money
                               v-model="total"
                               disabled
                               class="form-control">
                    </td>
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
      props: [
        "disable",
        "directContributionId",
        "affiliateId",
        "aidContributions",
        "aidReimbursements",
        "total",
        "contributionProcessId",
      ],
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
            aidContributionsAndReimbursements(){
                let temp = JSON.parse(JSON.stringify(this.aidContributions));
                temp.forEach(x => {
                    x['is_contribution'] = true;
                });
                let temp1 = JSON.parse(JSON.stringify(this.aidReimbursements));
                temp1.forEach(x => {
                    x['is_reimbursement'] = true;
                });
                Array.prototype.push.apply(temp,temp1);
                temp.sort((a,b) => (a.month_year > b.month_year) ? 1 : ((b.month_year > a.month_year) ? -1 : 0));
                return temp;
            }
        }
    };
</script>

<style>
</style>
