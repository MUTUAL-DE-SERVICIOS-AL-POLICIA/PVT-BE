<template>
    <modal name="contribution-table" height="auto" width="70%" :clickToClose="true" :focusTrap="true" :scrollable="true"
        style="z-index: 1000;" @opened="getData" @closed="closeModal">

        <div style="padding: 10px;">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">{{ title }}</h4>
            <table class="table table-striped" id="datatables">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periodo</th>
                        <th>Haber Basico</th>
                        <th>Categoria</th>
                        <th>Salario Cotizable</th>
                        <th>Total Aporte</th>
                        <th>Aporte FRPS</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ totalFRPS }}</td>
                    </tr>
                </tfoot>
            </table>
            <button type="button" class="btn btn-white">Cerrar</button>
        </div>
    </modal>
</template>
<script>
import $ from 'jquery';
import 'datatables.net';
import 'datatables.net-bs';
export default {
    name: 'ContributionsTable',
    props: {
        dataUrl: {
            type: String,
            required: true
        },
        fileName: {
            type: String
        },
        title: {
            type: String
        }
    },
    data() {
        return {
            table: null,
            data: [],
        }
    },
    computed: {
        totalFRPS() {
            const total = this.data.reduce((acc, i) => acc + parseFloat(i.retirement_fund), 0);
            return Math.round(total * 100) / 100;
        }
    },
    methods: {
        async getData() {
            const response = await axios.get(this.dataUrl);
            this.data = response.data.data;
            this.$nextTick(() => {
                this.table = jQuery('#datatables').DataTable({
                    data: this.data,
                    responsive: true,
                    fixedHeader: {
                        header: true,
                        footer: true,
                        headerOffset: $('#navbar-fixed-top').outerHeight()
                    },
                    order: [],
                    lengthMenu: [[60, -1], [60, "Todos"]],
                    dom: '< "html5buttons"B>lTfgitp',
                    buttons: [
                        { extend: 'copy' },
                        { extend: 'csv' },
                        { extend: 'excel', title: this.fileName + '-' + new Date().toISOString().split('T')[0] },
                    ],
                    columns: [
                        { title: '#', data: 'DT_Row_Index' },
                        { title: 'Periodo', data: 'month_year' },
                        { title: 'Haber Basico', data: 'base_wage' },
                        { title: 'Categoria', data: 'seniority_bonus' },
                        { title: 'Salario Cotizable', data: 'quotable_salary' },
                        { title: 'Total Aporte', data: 'total' },
                        { title: 'Aporte FRPS', data: 'retirement_fund' },
                    ],
                });
            });
        },
        closeModal() {
            if (this.table) {
                this.table.destroy(true);  // elimina todo lo generado para evitar bugs de visualización
                this.table = null;
            }
            this.data = [];
        }
    }
}
</script>