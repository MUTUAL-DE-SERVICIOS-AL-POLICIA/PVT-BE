<div class="row">
    <div class="col-md-12">
        @if ($errors->any())
            <div class="alert alert-danger">
                <h3>Se encontraron los siguientes errores. ({{ count($errors->all()) }})</h3>
                <ol class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ol>
            </div>
        @endif
        <div class="ibox ibox-e-margings">
            <div class="ibox-title">
                <div class="pull-left">
                    <h2>Caso Vejez</h2>
                </div>
                @can('update', $complementary_factor)
                <div class="pull-right">
                    <span data-toggle="tooltip" title="Editar el factor de complementacion">
                        <a href="" data-target="#myModal-edit" class="btn btn-raised btn-primary dropdown-toggle"
                            data-toggle="modal">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </span>
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                <table class="table table-bordered table-hover" id="complementary_factor_old_age-table">
                    <thead>
                        <tr class="primary">
                            <th>AÑO</th>
                            <th>Semestre</th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="00 - GENERALES">GENERALES</div>
                            </th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="01 - JEFES Y OFICIALES">JEFES Y OFICIALES</div>
                            </th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="02 - JEFES Y OFICIALES ADMINISTRATIVOS">JEFES Y OFICIALES
                                    ADMTVOS.</div>
                            </th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="03 - JEFES Y OFICIALES ADMINISTRATIVOS">SUBOFICIALES, CLASES Y
                                    POLICIAS</div>
                            </th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="04 - SUBOFICIALES, CLASES Y POLICIAS ADMINSTRATIVOS">
                                    SUBOFICIALES, CLASES Y POLICIAS ADMTVOS.</div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="ibox ibox-e-margings">
            <div class="ibox-title">
                <h2>Caso Viudez</h2>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered table-hover" id="complementary_factor_widowhood-table">
                    <thead>
                        <tr class="primary">
                            <th>AÑO</th>
                            <th>Semestre</th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="00 - GENERALES">GENERALES</div>
                            </th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="01 - JEFES Y OFICIALES">JEFES Y OFICIALES</div>
                            </th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="02 - JEFES Y OFICIALES ADMINISTRATIVOS">JEFES Y OFICIALES
                                    ADMTVOS.</div>
                            </th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="03 - JEFES Y OFICIALES ADMINISTRATIVOS">SUBOFICIALES, CLASES Y
                                    POLICIAS</div>
                            </th>
                            <th>
                                <div data-toggle="tooltip" data-placement="top" data-container="body"
                                    data-original-title="04 - SUBOFICIALES, CLASES Y POLICIAS ADMINSTRATIVOS">
                                    SUBOFICIALES, CLASES Y POLICIAS ADMTVOS.</div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@if($cf1_old_age)
<div id="myModal-edit" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="ibox-title">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar Factores de Complemantación</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'route' => ['complementary_factor.store'], 'class' =>
                'form-horizontal']) !!}
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('year', 'Año', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="year"
                                        value="{!! $year !!}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            @if($semester == "Primer")
                            <div class="">
                                <label>
                                    <input type="radio" name="semester" value='Primer' checked="">Primer Semestre
                                </label>
                            </div>
                            <div class="">
                                <label>
                                    <input type="radio" name="semester" value='Segundo'>Segundo Semestre
                                </label>
                            </div>
                            @else
                            <div class="">
                                <label>
                                    <input type="radio" name="semester" value='Primer'>Primer Semestre
                                </label>
                            </div>
                            <div class="">
                                <label>
                                    <input type="radio" name="semester" value='Segundo' checked="">Segundo Semestre
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="modal-title">Caso Vejez</h4>
                        <div class="form-group">
                            {!! Form::label('cf1_old_age', 'GENERALES', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf1_old_age', $cf1_old_age, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf2_old_age', 'JEFES Y OFICIALES', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::text('cf2_old_age', $cf2_old_age, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf3_old_age', 'JEFES Y OFICIALES ADMTVOS.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf3_old_age', $cf3_old_age, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf4_old_age', 'SUBOFICIALES, CLASES Y POLICIAS', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf4_old_age', $cf4_old_age, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf5_old_age', 'SUBOFICIALES, CLASES Y POLICIAS ADMTVOS.', ['class' =>
                            'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf5_old_age', $cf5_old_age, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="modal-title">Caso Viudez</h4>
                        <div class="form-group">
                            {!! Form::label('cf1_widowhood', 'GENERALES', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf1_widowhood', $cf1_widowhood, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf2_widowhood', 'JEFES Y OFICIALES', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::text('cf2_widowhood', $cf2_widowhood, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf3_widowhood', 'JEFES Y OFICIALES ADMTVOS.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf3_widowhood', $cf3_widowhood, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf4_widowhood', 'SUBOFICIALES, CLASES Y POLICIAS', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf4_widowhood', $cf4_widowhood, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf5_widowhood', 'SUBOFICIALES, CLASES Y POLICIAS ADMTVOS.', ['class' =>
                            'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf5_widowhood', $cf5_widowhood, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-raised btn-danger"
                                data-toggle="tooltip" data-dismiss="modal" data-placement="bottom" data-original-title="Cancelar">&nbsp;<i
                                    class="glyphicon glyphicon-remove"></i>&nbsp;</button> &nbsp;&nbsp;
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
@else
<div id="myModal-edit" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="ibox-title">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Añadir Factores de Complemantación</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'url' =>'/complementary_factor', 'class' =>
                'form-horizontal']) !!}
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('year', 'Año', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="year"
                                        value="{!! $year !!}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="">
                                <label>
                                    <input type="radio" name="semester" value='Primer'>Primer Semestre
                                </label>
                            </div>
                            <div class="">
                                <label>
                                    <input type="radio" name="semester" value='Segundo'>Segundo Semestre
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="modal-title">Caso Vejez</h4>
                        <div class="form-group">
                            {!! Form::label('cf1_old_age', 'GENERALES', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf1_old_age', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf2_old_age', 'JEFES Y OFICIALES', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::text('cf2_old_age', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf3_old_age', 'JEFES Y OFICIALES ADMTVOS.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf3_old_age', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf4_old_age', 'SUBOFICIALES, CLASES Y POLICIAS', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf4_old_age', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf5_old_age', 'SUBOFICIALES, CLASES Y POLICIAS ADMTVOS.', ['class' =>
                            'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf5_old_age', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="modal-title">Caso Viudez</h4>
                        <div class="form-group">
                            {!! Form::label('cf1_widowhood', 'GENERALES', ['class' => 'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf1_widowhood', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf2_widowhood', 'JEFES Y OFICIALES', ['class' => 'col-md-8 control-label'])
                            !!}
                            <div class="col-md-3">
                                {!! Form::text('cf2_widowhood', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf3_widowhood', 'JEFES Y OFICIALES ADMTVOS.', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf3_widowhood', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf4_widowhood', 'SUBOFICIALES, CLASES Y POLICIAS', ['class' => 'col-md-8
                            control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf4_widowhood', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cf5_widowhood', 'SUBOFICIALES, CLASES Y POLICIAS ADMTVOS.', ['class' =>
                            'col-md-8 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('cf5_widowhood', null, ['class'=> 'form-control', 'required' =>
                                'required']) !!}
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-raised btn-danger"
                                data-toggle="tooltip" data-dismiss="modal" data-placement="bottom" data-original-title="Cancelar">&nbsp;<i
                                    class="glyphicon glyphicon-remove"></i>&nbsp;</button> &nbsp;&nbsp;
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
@endif