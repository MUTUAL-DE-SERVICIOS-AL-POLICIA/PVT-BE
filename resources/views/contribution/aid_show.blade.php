@extends('layouts.app') 
@section('title', 'Aportes Auxilio Mortuorio') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">        
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row text-center">
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="pull-left">Aportes de Auxilio Mortuorio</h3>
                    <div class="text-right">
                        @can('update',new Muserpol\Models\Contribution\Contribution)
                        <button data-animation="flip" class="btn btn-primary" ><i class="fa" class="fa-lock" ></i> </button>
                        <a href="{{route('edit_aid_contribution', $affiliate->id)}}">
                            <button class="btn btn-info btn-sm dim" type="button" data-toggle="tooltip" data-placement="top" title="Gestionar" ><i class="fa fa-paste"></i></button>
                        </a>
                        @else
                        <br>
                        @endcan
                    </div>
                    <div class="text-right">
                        @can('update',new Muserpol\Models\Contribution\AidCommitment)
                        <button data-animation="flip" class="btn btn-primary" ><i class="fa" class="fa-lock" ></i> </button>
                        <a href="{{route('edit_contribution', $affiliate->id)}}">
                            <button class="btn btn-info btn-sm dim" type="button" data-toggle="tooltip" data-placement="top" title="Gestionar" ><i class="fa fa-paste"></i></button>
                        </a>
                        @else
                        <br>
                        @endcan
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover display" id="datatables-aid-contributions" cellspacing="0"
                        width="100%" style="font-size: 10px">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>GESTIÓN</th>
                                <th>MES</th>
                                <th>TIPO</th>
                                <th>RENTA</th>
                                <th>RENTA DIGNIDAD</th>
                                <th>COTIZABLE</th>
                                <th>APORTE AUXILIO MORTUORIO</th>
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
        var datatable_contri = $('#datatables-aid-contributions').DataTable({
            responsive: true,
            createdRow: function(row, data,dataIndex){                
                if(data['type'] == 'DIRECTO')                
                    $(row).addClass('yellow-row');
            },
            fixedHeader: {
                header: true,
                footer: true,
                headerOffset: $('#navbar-fixed-top').outerHeight()
            },
            
			ajax:"/get_aid_contributions/{{$affiliate->id}}",
            // ajax: "{{ url('affiliate_aid_contributions', $affiliate->id) }}",
            lengthMenu: [[15, 25, 50,100, -1], [15, 25, 50,100, "Todos"]],
            //dom:"<'row'<'col-sm-6'l><'col-sm-6'>><'row'<'col-sm-12't>><'row'<'col-sm-5'i>><'row'<'bottom'p>>",
            dom: '< "html5buttons"B>lTfgitp',
            buttons:[
                //{extend: 'colvis', columnText: function ( dt, idx, title ) { return (idx+1)+': '+title; }},
                { extend: 'copy'},
                { extend: 'excel', title: "{!! $affiliate->fullName() !!}"},
                    ],
            columns:[
                {data: 'DT_Row_Index'},
                {data: 'year'},
                {data: 'month'},
                {data: 'type'},
                {data: 'rent'},
                {data: 'dignity_rent'},
                {data: 'quotable'},
                {data: 'total'},
            ],
        });
         $('#datatables-aid-contributions tbody')
        .on( 'mouseenter', 'td', function () {
            var colIdx = datatable_contri.cell(this).index();
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