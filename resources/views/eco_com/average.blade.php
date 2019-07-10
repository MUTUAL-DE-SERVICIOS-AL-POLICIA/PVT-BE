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
                                        <div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Grado">Correlativo</div>
                                    </th>
                                    <th class="text-center">
                                        <div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Grado">Codigo</div>
                                    </th>
                                    <th class="text-center">
                                        <div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Grado">Grados</div>
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