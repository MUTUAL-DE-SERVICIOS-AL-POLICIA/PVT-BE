<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margings">
            <div class="ibox-title">
                <div class="pull-left">
                    <h2>Primer Nivel</h2>
                </div>
                @can('update', $complementary_factor)
                <div class="pull-right">
                    <span data-toggle="tooltip" title="Cargar sueldo">
                        <a href="" data-target="#first-level" class="btn btn-raised btn-primary dropdown-toggle"
                            data-toggle="modal">
                            <i class="fa fa-plus"></i>
                        </a>
                    </span>
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="row">
                    <table class="table table-bordered table-hover" id="first_level_base_wage-table">
                        <thead>
                            <tr class="success">
                                <th>AÑO</th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="00 00 - COMANDANTE GENERAL SUPERIOR">CMTE. GRAL.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="00 01 - GENERAL SUPERIOR">INSP. GRAL./SUPERVISOR</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="00 02 - GENERAL SUPERIOR">SBCMTE. GRAL./EXCMDTE</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="00 03 - GENERAL MAYOR">GRAL. SUP.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="00 04 - GENERAL PRIMERO">GRAL.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="01 01 - CORONEL Art.133">CNL. Art.133</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="01 02 - CORONEL">CNL.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="01 03 - TENIENTE CORONEL">TCNL.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="01 04 - MAYOR">MY.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="01 05 - CAPITAN">CAP.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="01 06 - TENIENTE">TTE.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="01 07 - SUBTENIENTE">SBTTE.</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="ibox float-e-margings">
            <div class="ibox-title">
                <div class="pull-left">
                    <h2>Segundo Nivel</h2>
                </div>
                @can('update', $complementary_factor)
                <div class="pull-right">
                    <span data-toggle="tooltip" title="Cargar sueldo">
                        <a href="" data-target="#second-level" class="btn btn-raised btn-primary dropdown-toggle"
                            data-toggle="modal">
                            <i class="fa fa-plus"></i>
                        </a>
                    </span>
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="row">
                    <table class="table table-bordered table-hover" id="second_level_base_wage-table">
                        <thead>
                            <tr class="success">
                                <th>AÑO</th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="02 02 - CORONEL DE SERVICIOS">CNL. SERV.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="02 03 - TENIENTE CORONEL DE SERVICIOS">TCNL. SERV.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="02 04 - MAYOR DE SERVICIOS">MY. SERV.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="02 05 - CAPITAN DE SERVICIOS">CAP. SERV.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="02 06 - TENIENTE DE SERVICIOS">TTE. SERV.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="02 07 - SUBTENIENTE DE SERVICIOS">SBTTE. SERV.</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="ibox float-e-margings">
            <div class="ibox-title">
                <div class="pull-left">
                    <h2>Tercer Nivel</h2>
                </div>
                @can('update', $complementary_factor)
                <div class="pull-right">
                    <span data-toggle="tooltip" title="Cargar sueldo">
                        <a href="" data-target="#third-level" class="btn btn-raised btn-primary dropdown-toggle"
                            data-toggle="modal">
                            <i class="fa fa-plus"></i>
                        </a>
                    </span>
                </div>
                @endcan
            </div>

            <div class="ibox-content">
                <div class="row">
                    <table class="table table-bordered table-hover" id="third_level_base_wage-table">
                        <thead>
                            <tr class="success">
                                <th>AÑO</th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="03 08 - SUBOFICIAL SUPERIOR">SOF. SUP.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="03 09 - SUBOFICIAL MAYOR ">SOF. MY.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="03 010 - SUBOFICIAL PRIMERO">SOF. 1RO.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="03 11 - SUBOFICIAL SEGUNDO">SOF. 2DO.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="03 12 - SARGENTO MAYOR">SGTO. MY.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="03 13 - SARGENTO PRIMERO">SGTO. 1RO.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="03 14 - SARGENTO SEGUNDO">SGTO. 2DO.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="03 15 - SARGENTO">SGTO.</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="ibox float-e-margings">
            <div class="ibox-title">
                <div class="pull-left">
                    <h2>Cuarto Nivel</h2>
                </div>
                @can('update', $complementary_factor)
                <div class="pull-right">
                    <span data-toggle="tooltip" title="Cargar sueldo">
                        <a href="" data-target="#fourth-level" class="btn btn-raised btn-primary dropdown-toggle"
                            data-toggle="modal">
                            <i class="fa fa-plus"></i>
                        </a>
                    </span>
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="row">
                    <table class="table table-bordered table-hover" id="fourth_level_base_wage-table">
                        <thead>
                            <tr class="success">
                                <th>AÑO</th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="04 08 - SUBOFICIAL SUPERIOR DE SERVICIOS">SOF. SUP. SERV.
                                    </div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="04 09 - SUBOFICIAL MAYOR DE SERVICIOS">SOF. MY. SERV.
                                    </div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="04 010 - SUBOFICIAL PRIMERO DE SERVICIOS">SOF. 1RO. SERV.
                                    </div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="04 11 - SUBOFICIAL SEGUNDO DE SERVICIOS">SOF. 2DO. SERV.
                                    </div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="04 12 - SARGENTO MAYOR DE SERVICIOS">SGTO. MY. SERV.
                                    </div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="04 13 - SARGENTO PRIMERO DE SERVICIOS">SGTO. 1RO. SERV.
                                    </div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="04 14 - SARGENTO SEGUNDO DE SERVICIOS">SGTO. 2DO. SERV.</div>
                                </th>
                                <th>
                                    <div data-toggle="tooltip" data-placement="top" data-container="body"
                                        data-original-title="04 16 - SARGENTO DE SERVICIOS">SGTO. SERV.</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
{{-- 
<div id="myModal-import" class="modal fade bs-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="ibox-title">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Importar Archivo con Sueldos</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'route' => ['base_wage.store'], 'class' => 'form-horizontal', 'files' => true ]) !!}
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('archive', 'Archivo', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-8">
                                <input type="file" id="inputFile" name="archive" required>
                                <input type="text" readonly="" class="form-control " placeholder="Formato Excel">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('month_year', 'Mes y Año', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="month_year" value="" required>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! url('base_wage') !!}" class="btn btn-raised btn-warning" data-toggle="tooltip" data-placement="bottom" data-original-title="Cancelar">&nbsp;<i class="glyphicon glyphicon-remove"></i>&nbsp;</a>                            &nbsp;&nbsp;
                            <button type="submit" class="btn btn-raised btn-success" data-toggle="tooltip" data-placement="bottom" data-original-title="Guardar">&nbsp;<i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div> --}}
{{--
@endsection

 --}}
 <!-- first level  -->
 <div id="first-level" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="ibox-title">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Añadir Sueldos</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'url' =>'/base_wage_create', 'class' =>
                'form-horizontal']) !!}
                <br>
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::hidden('level', '1')!!}
                            {!! Form::label('year', 'Año', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="year"
                                        value="{!! $year !!}" disabled>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('1', 'CMTE. GRAL.', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('1', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('2', 'INSP. GRAL./SUPERVISOR', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('2', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('3', 'SBCMTE. GRAL./EXCMDTE', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('3', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('4', 'GRAL. SUP.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('4', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('5', 'GRAL.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('5', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('6', 'CNL. Art.133', ['class' =>
                            'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('6', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('7', 'CNL.', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('7', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('8', 'TCNL.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('8', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('9', 'MY.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('9', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('10', 'CAP.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('10', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('11', 'TTE.', ['class' =>
                            'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('11', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('12', 'SBTTE.', ['class' =>
                            'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('12', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! url('/eco_com_qualification_parameters') !!}" class="btn btn-raised btn-danger"
                                data-toggle="tooltip" data-placement="bottom" data-original-title="Cancelar">&nbsp;<i
                                    class="glyphicon glyphicon-remove"></i>&nbsp;</a> &nbsp;&nbsp;
                            <button type="submit" class="btn btn-raised btn-primary" data-toggle="tooltip"
                                data-placement="bottom" data-original-title="Guardar">&nbsp;<i
                                    class="glyphicon glyphicon-floppy-disk"></i>&nbsp;</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Second level -->
 <div id="second-level" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="ibox-title">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Añadir Sueldos</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'url' =>'/base_wage_create', 'class' =>
                'form-horizontal']) !!}
                <br>
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::hidden('level', '2')!!}
                            {!! Form::label('year', 'Año', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="year"
                                        value="{!! $year !!}" disabled>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('1', 'CNL. SERV.', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('1', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('2', 'TCNL. SERV.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('2', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('3', 'MY. SERV.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('3', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('4', 'CAP. SERV.', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('4', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('5', 'TTE. SERV.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('5', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('6', 'SBTTE. SERV.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('6', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! url('eco_com_qualification_parameters') !!}" class="btn btn-raised btn-danger"
                                data-toggle="tooltip" data-placement="bottom" data-original-title="Cancelar">&nbsp;<i
                                    class="glyphicon glyphicon-remove"></i>&nbsp;</a> &nbsp;&nbsp;
                            <button type="submit" class="btn btn-raised btn-primary" data-toggle="tooltip"
                                data-placement="bottom" data-original-title="Guardar">&nbsp;<i
                                    class="glyphicon glyphicon-floppy-disk"></i>&nbsp;</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!--  third level -->
 <div id="third-level" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="ibox-title">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Añadir Sueldos</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'url' =>'/base_wage_create', 'class' =>
                'form-horizontal']) !!}
                <br>
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::hidden('level', '3')!!}
                            {!! Form::label('year', 'Año', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="year"
                                        value="{!! $year !!}" disabled>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('1', 'SOF. SUP.', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('1', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('2', 'SOF. MY.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('2', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('3', 'SOF. 1RO.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('3', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('4', 'SOF. 2DO.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('4', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('5', 'SGTO. MY.', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('5', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('6', 'SGTO. 1RO.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('6', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('7', 'SGTO. 2DO.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('7', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('8', 'SGTO.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('8', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! url('/eco_com_qualification_parameters') !!}" class="btn btn-raised btn-danger"
                                data-toggle="tooltip" data-placement="bottom" data-original-title="Cancelar">&nbsp;<i
                                    class="glyphicon glyphicon-remove"></i>&nbsp;</a> &nbsp;&nbsp;
                            <button type="submit" class="btn btn-raised btn-primary" data-toggle="tooltip"
                                data-placement="bottom" data-original-title="Guardar">&nbsp;<i
                                    class="glyphicon glyphicon-floppy-disk"></i>&nbsp;</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!--  fourth level -->
 <div id="fourth-level" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="ibox-title">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Añadir Sueldos</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'url' =>'/base_wage_create', 'class' =>
                'form-horizontal']) !!}
                <br>
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::hidden('level', '4')!!}
                            {!! Form::label('year', 'Año', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="year"
                                        value="{!! $year !!}" disabled>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('1', 'SOF. SUP. SERV.', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('1', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('2', 'SOF. MY. SERV.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('2', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('3', 'SOF. 1RO. SERV.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('3', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('4', 'SOF. 2DO. SERV.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('4', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('5', 'SGTO. MY. SERV.', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('5', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('6', 'SGTO. 14O. SERV.', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::number('6', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('7', 'SGTO. 2DO. SERV.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('7', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('8', 'SGTO. SERV.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::number('8', null, ['class'=> 'form-control', 'required' =>
                                'required', 'min' =>  '1', 'oninput' => "this.value = Math.max(this.value, 1)"]) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! url('/eco_com_qualification_parameters') !!}" class="btn btn-raised btn-danger"
                                data-toggle="tooltip" data-placement="bottom" data-original-title="Cancelar">&nbsp;<i
                                    class="glyphicon glyphicon-remove"></i>&nbsp;</a> &nbsp;&nbsp;
                            <button type="submit" class="btn btn-raised btn-primary" data-toggle="tooltip"
                                data-placement="bottom" data-original-title="Guardar">&nbsp;<i
                                    class="glyphicon glyphicon-floppy-disk"></i>&nbsp;</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
