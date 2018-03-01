@extends('layouts.app') 
@section('title', 'Afiliados') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{--  {{ Breadcrumbs::render('show_affiliate', $affiliate) }}  --}}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row text-center">

    </div>
    {{--  <div class="row">
        <div class="col-md-6">
            <affiliate-show :affiliate="{{ $affiliate }}" inline-template>
                @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities,'birth_cities'=>$birth_cities])
            </affiliate-show>
        </div>
        <div class="col-md-6">
            <affiliate-police :affiliate="{{ $affiliate }}" inline-template>
                @include('affiliates.affiliate_police_information', ['affiliate'=>$affiliate])
            </affiliate-police>
        </div>
    </div>  --}}
    <div class="row">
        <div class="col-md-12 no-padding no-margins">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="pull-left">Aportes</h3>
                    <div class="text-right">
                        <button data-animation="flip" class="btn btn-primary" ><i class="fa" class="fa-lock" ></i> </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover display" id="datatables-affiliate-contributions"  cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Gestión</th>
                                <th>Grado</th>
                                <th>Unidad</th>
                                <th>Ítem</th>
                                <th>Sueldo</th>
                                <th>B Antigüedad</th>
                                <th>B Estudio</th>
                                <th>B al Cargo</th>
                                <th>B Frontera</th>
                                <th>B Oriente</th>
                                <th>B Seguridad</th>
                                <th>Ganado</th>
                                <th>Cotizable</th>
                                <th>F.R.</th>
                                <th>C.A.M.</th>
                                <th>Aporte</th>
                                <th>Desg.</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
            {{--  {!! $dataTable->table() !!}s  --}}
 

    </div>
</div>
@endsection
{{--  @section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
@endsection  --}}
{{--  @section('scripts')
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="/vendor/datatables/buttons.server-side.js"></script>
{{--  {!! $dataTable->scripts() !!}
@endsection  --}}
@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css">
@endsection

@section('scripts')

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
{{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>  --}}
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>

<script>
    
    $(document).ready(function () {
        
        alert("hla");
        
        var datatable_contri = $('#datatables-affiliate-contributions').DataTable({
            fixedHeader: {
                header: true,
                footer: false
            },
            ajax:"/get_affiliate_contributions/{{$affiliate->id}}",
            // ajax: "{{ url('affiliate_contributions', $affiliate->id) }}",
            lengthMenu: [[15, 25, 50,100, -1], [15, 25, 50,100, "Todos"]],
            //dom:"<'row'<'col-sm-6'l><'col-sm-6'>><'row'<'col-sm-12't>><'row'<'col-sm-5'i>><'row'<'bottom'p>>",
            dom: '< "html5buttons"B>lTfgitp',
            buttons:[
                {extend: 'colvis', columnText: function ( dt, idx, title ) { return (idx+1)+': '+title; }},
                { extend: 'copy'},
                { extend: 'csv'},
                { extend: 'excel', title: 'ExampleFile'},
                { extend: 'pdf', title: 'ExampleFile'},
            ],
            columns:[
                {data: 'month_year'},
                {data: 'degree_id'},
                {data: 'unit_id'},
                {data: 'item'},
                {data: 'base_wage'},
                {data: 'seniority_bonus'},
                {data: 'study_bonus'},
                {data: 'position_bonus'},
                {data: 'border_bonus'},
                {data: 'east_bonus'},
                {data: 'public_security_bonus'},
                {data: 'gain'},
                {data: 'quotable'},
                {data: 'retirement_fund'},
                {data: 'mortuary_quota'},
                {data: 'total'},
                {data: 'breakdown_id', "sClass": "text-right", bSortable: false},
            ]
        });
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
@endsection
