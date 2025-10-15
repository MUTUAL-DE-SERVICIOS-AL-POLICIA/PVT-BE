<template>
    <modal name="contribution-table" height="auto" width="60%" :clickToClose="true" :focusTrap="true"
        :scrollable="true" style="z-index: 1000;" @opened="getData">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Devolución de Aportes en Disponibilidad Test</h4>
        </div>
        <div class="modal-body">
            <div class="col-lg-12">
                <table class="table table-striped" id="datatables-availability2">
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
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
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
        }
    },
    data() {
        return {

        }
    },
    mounted() {
        console.log(this.dataUrl);


    },
    computed: {

    },
    methods: {
        getData(event) {
            console.log(event)
            const table = jQuery('#datatables-availability2').DataTable({
                responsive: true,
                fixedHeader: {
                    header: true,
                    footer: true,
                    headerOffset: $('#navbar-fixed-top').outerHeight()
                },
                order: [],
                ajax: this.dataUrl,
                lengthMenu: [[60, -1], [60, "Todos"]],
                dom: '< "html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy' },
                    { extend: 'csv' },
                    { extend: 'excel', title: "Dispobiblidad" },
                ],
                columns: [
                    { data: 'DT_Row_Index' },
                    { data: 'month_year' },
                    { data: 'base_wage' },
                    { data: 'seniority_bonus' },
                    { data: 'quotable_salary' },
                    { data: 'total' },
                    { data: 'retirement_fund' },
                ],
            });
            console.log(table);
            
        }
    }
}
</script>