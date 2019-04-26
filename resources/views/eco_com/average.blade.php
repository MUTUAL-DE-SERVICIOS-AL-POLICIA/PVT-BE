@extends('layouts.app') 
@section('title', 'Promedios') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {{ Breadcrumbs::render('complementary_factor') }}
    </div>
    <div class="col-md-5 " style="margin-top:12px;">
        <div class="pull-right">
            <div>
                {{-- @can('update', $complementary_factor)
                <span data-toggle="tooltip" title="Editar el factor de complementacion">
                    <a href="" data-target="#myModal-edit" class="btn btn-raised btn-primary dropdown-toggle" data-toggle="modal">
                        <i class="fa fa-pencil"></i>
                    </a>
                </span> @endcan --}}
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="ibox ibox-e-margins">
            <div class="ibox-title">
                <h2>Promedios</h2>
            </div>
            <br/>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['method' => 'GET','route'=> ['print_average'], 'class' => 'form-horizontal', 'id'=>'form' ]) !!}
                    <div class="col-md-4 col-md-offset-2">
                        <div class="form-group">
                            {!! Form::label('year', 'Gestión', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select('year', $year_list, null, ['class' => 'form-control', 'required','id'=>'year' ]) !!}
                                <span class="help-block">Seleccione Gestión</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            {!! Form::label('semester', 'Semestre', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select('semester',$semester_list, null, ['class' => 'form-control', 'required', 'id'=>'semester']) !!}
                                <span class="help-block">Seleccione Semestre</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12">
                        <div class="row text-center">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {{--
                                    <div class="btn-group" data-toggle="tooltip" data-placement="top" data-original-title="Imprimir" style="margin:0px;">
                                        <a href="{!! url('print_average') !!}" class="btn btn-raised btn-success dropdown-toggle enabled" data-toggle="tooltip">
                                                    &nbsp;<span class="glyphicon glyphicon-print"></span>&nbsp;
                                                </a>
                                    </div> --}} {{-- &nbsp;&nbsp;<button type="submit" class="btn btn-raised btn-success"
                                        data-toggle="tooltip" data-placement="bottom" data-original-title="Imprimir">&nbsp;<span class="glyphicon glyphicon-refresh"></span>&nbsp;</button>                                    --}}

                                    <button type="button" id="refresh-average" class="btn btn-raised btn-success" data-toggle="tooltip" data-placement="bottom"
                                        data-original-title="Generar">&nbsp;<span class="glyphicon glyphicon-refresh"></span>&nbsp;</button>                                    {{-- <a data-bind="attr: { href: urlText }" class="btn btn-raised btn-success" data-toggle="tooltip"
                                        data-placement="bottom" data-original-title="Generar"><i class="glyphicon glyphicon-import glyphicon-lg"></i></a>                                    --}} {{-- <button class="btn btn-raised" type="button" id="pdf-button" data-toggle="tooltip"
                                        data-placement="bottom" title="Descargar en PDF">
                                                <img src="/img/file-pdf-download.svg" width="20px"> <span class="text-danger"> <strong>PDF</strong></span>
                                            </button>
                                    <button class="btn btn-raised" type="button" id="excel-button" data-toggle="tooltip" data-placement="bottom" title="Descargar en Excel">
                                                <img src="/img/file-xsl-download.svg" width="20px"> <span class="text-success"> <strong>EXCEL</strong></span>
                                            </button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <div class="col-md-12">
                        <table class="table table-bordered table-hover" id="average_table">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">
                                        <div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Grado">Grado</div>
                                    </th>
                                    <th class="text-left">
                                        <div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Renta">Renta</div>
                                    </th>
                                    <th class="text-left">
                                        <div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Renta Menor">Renta Menor</div>
                                    </th>
                                    <th class="text-left">
                                        <div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Renta Mayor">Renta Mayor</div>
                                    </th>
                                    <th class="text-left">
                                        <div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Promedio">Promedio</div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
    var oTable = $('#average_table').DataTable({
        "dom": '<"top">t<"bottom"p>',
        processing: true,
        serverSide: true,
        pageLength: 30,
        autoWidth: false,
        order: [0, "asc"],
        ajax: {
            url: '{!! route('get_averages') !!}',
            data: function (d) {
                d.year = $('#year').val();
                d.semester = $('#semester').val();
            }
        },
        columns: [
            { data: 'degree', sClass: "text-center" },
            { data: 'type', bSortable: false },
            { data: 'rmin', bSortable: false },
            { data: 'rmax', bSortable: false },
            { data: 'average', bSortable: false },
        ]
    });
    $('#refresh-average').on('click', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    });

</script>
@endsection