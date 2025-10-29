@extends('layouts.app')
@section('title', 'Afiliados')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('show_affiliate_contributions', $affiliate) }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row text-center">
    </div>
    <div class="row">
        @if(Session::has('message'))
            <br>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                {{Session::get('message')}}
            </div>
        @endif
        <div class="col-md-12 no-padding no-margins">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="pull-left">Aportes </h3>
                    <div class="text-right">
                        @can('update',new Muserpol\Models\Contribution\Contribution)
                            {{-- <a href="{{route('direct_contribution', $affiliate->id)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Aportes directos" ><i class="fa fa-paste"> </i> Aportes Directos </a>
                            <a href="{{route('edit_contribution', $affiliate->id)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Gestionar Aportes" ><i class="fa fa-paste"></i> Gestionar Aportes </a> --}}
                            <br>
                        </a>
                        @else
                        <br>
                        @endcan
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover display" id="datatables-affiliate-contributions" cellspacing="0"
                        width="100%" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th>Gestión</th>
                                <th>Gestión-1</th>
                                <th>Grado</th>
                                <th>Unidad</th>
                                <th>Sueldo</th>
                                <th>Antigüedad</th>
                                <th>Categoria</th>
                                <th>Estudio</th>
                                <th>Cargo</th>
                                <th>Frontera</th>
                                <th>Oriente</th>
                                <th>Seguridad</th>
                                <th>Ganado</th>
                                <th>Cotizable</th>
                                <th>F.R.</th>
                                <th>C.M.</th>
                                <th>Aporte</th>
                                <th>Desg.</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        {{-- {!! $dataTable->table() !!}s --}}

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
        background-color:#ffe6b3 !important;

    }
</style>
@endsection

@section('scripts')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
    $(document).ready(function () {
        $('body').addClass("mini-navbar");
        var datatable_contri = $('#datatables-affiliate-contributions').DataTable({
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
            createdRow: function(row, data,dataIndex){
                if(data['type'] == 'Directo')
                    $(row).addClass('yellow-row');
            },
            fixedHeader: {
                header: true,
                footer: true,
                headerOffset: $('#navbar-fixed-top').outerHeight()
            },

            order: [],
            /* para personalizar el orden
             columnDefs: [
               { type: 'monthYear', targets: 0 }
            ],
            */
			// ajax:"/get_affiliate_contributions/{{$affiliate->id}}",
            ajax: "{{ url('get_affiliate_contributions', $affiliate->id) }}",
            lengthMenu: [[25, 50,100, -1], [25, 50,100, "Todos"]],
            //dom:"<'row'<'col-sm-6'l><'col-sm-6'>><'row'<'col-sm-12't>><'row'<'col-sm-5'i>><'row'<'bottom'p>>",
            dom: '< "html5buttons"B>lTfgitp',
            buttons:[
                {extend: 'colvis', columnText: function ( dt, idx, title ) { return (idx+1)+': '+title; }},
                { extend: 'copy'},
                { extend: 'csv'},
                { extend: 'excel', title: "{!! $affiliate->fullName() !!}", exportOptions: { columns: ':visible' }},
            ],
            columns:[
                {data: 'month_year_concat' },
                {data: 'month_year', },
                {data: 'degree_id'},
                {data: 'unit_id'},
                {data: 'base_wage'},
                {data: 'seniority_bonus'},
                {data: 'category_id',},
                {data: 'study_bonus', visible:false},
                {data: 'position_bonus', visible:false},
                {data: 'border_bonus',visible:false},
                {data: 'east_bonus',visible:false},
                {data: 'public_security_bonus', visible:false},
                {data: 'gain'},
                {data: 'quotable'},
                {data: 'retirement_fund'},
                {data: 'mortuary_quota'},
                {data: 'total'},
                {data: 'breakdown_id', "sClass": "text-right"},
                {data: 'type', visible:false},
            ],
            columnDefs: [
                { targets: 0, orderData: 1 },
                { targets: 1, visible: false }
            ],

            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if ( aData.type == " RE " )
                {
                    $('td', nRow).addClass('warning');
                }
            }
        });
        /*  para personalizar el orden
        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            "monthYear-pre": function(s) {
                var a = s.split('-');
                // Date uses the American "MM DD YY" format
                return new Date(a[0] + ' 01 ' + a[1]);
            },
            "monthYear-asc": function(a, b) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },
            "monthYear-desc": function(a, b) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        });
        */
         $('#datatables-affiliate-contributions tbody')
        .on( 'mouseenter', 'td', function () {
            var colIdx = datatable_contri.cell(this).index().column;
            $( datatable_contri.cells().nodes() ).removeClass( 'highlight' );
            $( datatable_contri.column( colIdx ).nodes() ).addClass( 'highlight' );
        } );
        $('[data-toggle="tooltip"]').tooltip();
        $('.btn.btn-default.buttons-collection.buttons-colvis').on('click', function () {
            $('div.dt-button-background').remove()
        });

    })

</script>

@endsection
