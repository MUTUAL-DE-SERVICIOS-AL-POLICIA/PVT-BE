@extends('layouts.app')
@section('title', 'Fondo de Retiro')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('show_qualification_certification_retirement_fund', $retirement_fund, $number_contributions)!!}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>SALARIO PROMEDIO COTIZABLE</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped" id="datatables-certification">
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
                    </table>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td>Total Aportes Fondo de Retiro Policial Solidario</td>
                                <td>{{ $total_retirement_fund }}</td>
                            </tr>
                            <tr>
                                <td>Salario Total</td>
                                <td>{{ $sub_total_average_salary_quotable }}</td>
                            </tr>
                            <tr>
                                <td>Salario Promedio</td>
                                <td>{{ $total_average_salary_quotable }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        {!! Form::open(array('route' => ['save_average_quotable', $retirement_fund->id],'method'=>'PATCH')) !!}
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
@endsection
@section('scripts')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
    $(document).ready(function () {
        var datatable_contri = $('#datatables-certification').DataTable({
            responsive: true,
            fixedHeader: {
            header: true,
            footer: true,
                headerOffset: $('#navbar-fixed-top').outerHeight()
            },
            order: [],
            ajax: "{{ url('get_data_certification', $retirement_fund->id) }}",
            lengthMenu: [[15, 30, 60, -1], [15, 30, 60, "Todos"]],
            dom: '< "html5buttons"B>lTfgitp',
            buttons:[
                {extend: 'colvis', columnText: function ( dt, idx, title ) { return (idx+1)+': '+title; }},
                { extend: 'copy'},
                { extend: 'csv'},
                { extend: 'excel', title: "{!! $retirement_fund->id.'-'.date('Y-m-d') !!}"},
            ],
            columns:[
                {data: 'DT_Row_Index' },
                {data: 'month_year' },
                {data: 'base_wage'},
                {data: 'seniority_bonus'},
                {data: 'quotable_salary'},
                {data: 'total'},
                {data: 'retirement_fund'},
            ],
        });
    });
</script>
@endsection
