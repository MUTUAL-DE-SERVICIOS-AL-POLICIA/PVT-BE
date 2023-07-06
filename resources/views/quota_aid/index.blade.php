@extends('layouts.app')


@section('title', 'Quota y Auxilio Mortuoria')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('quota_aid_mortuary') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center m-t-lg table-responsive">
                <table class="table table-striped table-bordered table-hover display" id="datatables-quota-aids" cellspacing="0" width="100%"
                    style="font-size: 12px">
                    <tfoot>
                        <tr>
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
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                            <th style="padding:5px; width:280px"><input type="text" class="form-control" style="width:100%"></th>
                        </tr>
                        </tr>
                    </tfoot>
                    <thead>
                        <tr>
                            <th># de Trámite</th>
                            <th>Fecha de Recepción</th>
                            <th>C.I</th>
                            <th>Primer Nombre</th>
                            <th>Segundo Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Apellido de Casada</th>
                            <th>Fecha de Fallecimiento</th>
                            <th>Fecha de Ingreso</th>
                            <th>Fecha de Desvinculación</th>
                            <th>Teléfono</th>
                            <th>Celular</th>
                            <th>Tipo de Trámite</th>
                            <th>Modalidad</th>
                            <th>Ubicación</th>
                            <th>Regional</th>
                            <th>Estado de Bandeja</th>
                            <th>Total</th>
                            <th># de Archivo</th>
                            <th>Fecha Archivo</th>
                            <th># de Revision Legal</th>
                            <th>Fecha Revision Legal</th>
                            <th># de Cuentas individuales</th>
                            <th>Fecha Cuentas individuales</th>
                            <th># de Calificación</th>
                            <th>Fecha Calificación</th>
                            <th># de Dictamen</th>
                            <th>Fecha Dictamen</th>
                            <th># de Jefatura</th>
                            <th>Fecha Jefatura</th>
                            <th># de Resolución</th>
                            <th>Fecha Resolución</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
                {{-- <quota-aid-mortuary-index></quota-aid-mortuary-index>          --}}
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
        thead, tfoot { display: table-header-group; }
    </style>
@endsection
@section('scripts')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
    (function() {
        //added responsive table affiliate
        // document.getElementsByName('SimpleTable')[0].className+='table-responsive';
        var datatable_ret_fun = $('#datatables-quota-aids').DataTable({
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
            // fixedHeader: {
            //     header: true,
            //     footer: true,
            //     headerOffset: $('#navbar-fixed-top').outerHeight()
            // },

            order: [],
            /* para personalizar el orden
             columnDefs: [
               { type: 'monthYear', targets: 0 }
            ],
            */
            ajax: "{{ url('get_all_quota_aid') }}",
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todos"]],
            //dom:"<'row'<'col-sm-6'l><'col-sm-6'>><'row'<'col-sm-12't>><'row'<'col-sm-5'i>><'row'<'bottom'p>>",
            dom: '< "html5buttons"B>Tgitp',
            buttons:[
                {extend: 'colvis', text: 'Columnas Visibles', columnText: function ( dt, idx, title ) { return (idx+1)+': '+title; }},
                { extend: 'copy', text: 'Copiar'},
                { extend: 'csv'},
                { extend: 'excel', text:"Descargar en Excel", title: "Trámites de Cuota y Auxilio Mortuorio "+moment().format('L_Hmm'), exportOptions: { columns: ':visible' }},
            ],
            columns:[
                // { data: 'id' },
                { data: 'code' },
                { data: 'reception_date' },
                { data: 'affiliate.identity_card' },
                { data: 'affiliate.first_name' },
                { data: 'affiliate.second_name' },
                { data: 'affiliate.last_name' },
                { data: 'affiliate.mothers_last_name' },
                { data: 'affiliate.surname_husband' },
                // { data: 'affiliate.gender' },
                { data: 'affiliate.date_death', visible: false },
                { data: 'affiliate.date_entry', visible: false },
                { data: 'affiliate.date_derelict', visible: false },
                { data: 'phone_number', visible: false },
                { data: 'cell_phone_number', visible: false },
                { data: 'type' },
                { data: 'procedure_modality.name' },
                { data: 'wf_state.first_shortened' },
                { data: 'city_start.name' },
                { data: 'inbox_state' },
                { data: 'total' },      
                { data: 'file_code',  visible: false },          
                { data: 'file_date', visible: false },
                { data: 'review_code', visible: false },
                { data: 'review_date', visible: false },
                { data: 'individuals_account_code', visible: false },
                { data: 'individuals_account_date', visible: false },
                { data: 'qualification_code', visible: false },
                { data: 'qualification_date', visible: false },
                { data: 'dictum_code', visible: false },
                { data: 'dictum_date', visible: false },
                { data: 'headship_code', visible: false },
                { data: 'headship_date', visible: false },
                { data: 'resolution_code', visible: false },
                { data: 'resolution_date', visible: false },
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
