@extends('layouts.app') 
@section('title', 'Aportes') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('direct_contribution') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center m-t-lg table-responsive">
                <table class="table table-striped table-bordered table-hover display" id="datatables-retirement-funds" cellspacing="0" width="100%"
                    style="font-size: 12px">
                    <tfoot>
                        <tr>
                            <th style="padding:5px; width:100px;"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:100px;"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:100px;"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:20px;"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:20px;"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:20px;"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <thead>
                        <tr>
                            <th># de Tramite</th>
                            <th>Fecha de Recepción</th>
                            <th>C.I</th>
                            <th>Exp</th>
                            <th>Primer Nombre</th>
                            <th>Segundo Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Apellido de Casada</th>
                            <th>Modalidad</th>
                            <th>Regional</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
<style>
    td.highlight {
        background-color: #e3eaef !important;
    }

    .table-hover tbody tr:hover td,
    .table-hover tbody tr:hover th {
        background-color: #e3eaef;
    }

    .yellow-row {
        background-color: #ffe6b3 !important;
    }

    thead,
    tfoot {
        display: table-header-group;
    }
</style>
@endsection
@section('scripts')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
    (function() {
        var datatable_ret_fun = $('#datatables-retirement-funds').DataTable({
          language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            responsive: true,
            order: [],
            ajax: "{{ url('get_all_direct_contribution') }}",
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todos"]],
            dom: '< "html5buttons"B>Tgitp',
            buttons:[
                {extend: 'colvis', text: 'Columnas Visibles', columnText: function ( dt, idx, title ) { return (idx+1)+': '+title; }},
                { extend: 'copy', text: 'Copiar'},
                { extend: 'csv'},
                { extend: 'excel', text:"Descargar en Excel", title: "Trámites de Fondo de Retiro "+moment().format('L_Hmm'), exportOptions: { columns: ':visible' }},
            ],
            columns:[
                // { data: 'id' },
                { data: 'code' },
                { data: 'date' },
                { data: 'affiliate.identity_card' },
                { data: 'affiliate.city_identity_card_id' },
                { data: 'affiliate.first_name' },
                { data: 'affiliate.second_name' },
                { data: 'affiliate.last_name' },
                { data: 'affiliate.mothers_last_name' },
                { data: 'affiliate.surname_husband' },
                { data: 'procedure_modality.name' },
                { data: 'city.name' },
                { data: 'action' },
            ],
        });
        $('.btn.btn-default.buttons-collection.buttons-colvis').on('click', function () {
            $('div.dt-button-background').remove()
        });
        datatable_ret_fun.columns().every( function () {
            var that = this;
            $('input',this.footer()).on('keyup change', function () {
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
            });
        });
    })();
</script>
@endsection