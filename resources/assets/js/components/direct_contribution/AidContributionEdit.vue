<template>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="footable-visible footable-first-column footable-sortable">Mes/Año</th>
                    <th class="footable-visible footable-sortable">Renta Bs.</th>
                    <th class="footable-visible footable-sortable">Renta Dignidad Bs.</th>
                    <th class="footable-visible footable-sortable">Auxilio Mortuorio (2.03 %)</th>
                    <th class="footable-visible footable-sortable">Ajuste UFV Bs.</th>
                    <th class="footable-visible footable-sortable">Subtotal Aporte</th>
                    <!-- <th>Opciones</th> -->
                </tr>
            </thead>
            <tbody>
                <!-- v-bind:style="getStyleColor(index)"
                    :class="{'danger': error(ac.subtotal)}" -->
                <tr v-for="(ac, index) in aidContributions"
                    :key="index">
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
                    <td colspan="3"><label for="total">Total a Pagar por Concepto de Aportes de Auxilio Mortuorio:</label></td>
                    <td colspan="3"><input type="text"
                               v-money
                               v-model="total"
                               disabled
                               class="form-control">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
      props: [
        "disable",
        "directContributionId",
        "affiliateId",
        "aidContributions",
        "total"
      ]
    };
</script>

<style>
</style>
