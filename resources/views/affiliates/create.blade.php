@extends('layouts.app')

@section('title', 'Afiliados')

@section('content')
<form method="POST" action="{{ route('affiliate.store') }}">
        {{ csrf_field() }}
        <div class="col-md-12" style="padding-left: 6px">
                <div class="tab-content">
                        <div id="tab-affiliate" class="tab-pane active">
                            <div class="col-lg-12">
                                    <div class="ibox">
                                        <div class="ibox-content">
                                                    <div class="row">
                                                        <div class="pull-left"> <legend > Crear nuevo afiliado</legend></div>
                                                    </div>
                                                    <div class="row">
                                                        
                                                        {{-- left --}}
                                                        <div class="col-md-6">
                                                            <div class="row m-b-md">
                                                                <div class="col-md-4"><label class="control-label">Cédula de identidad:</label></div>
                                                                <div class="col-md-8"><input name="identity_card" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-4"><label class="control-label">Apellido Paterno:</label></div>
                                                                <div class="col-md-8"><input type="text" name="last_name" class="form-control"  >
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-4"><label class="control-label">Apellido Materno:</label></div>
                                                                <div class="col-md-8"><input name="mothers_last_name" type="text" class="form-control" >
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-4"><label class="control-label">Primer Nombre:</label></div>
                                                                <div class="col-md-8"><input type="text" name="first_name" class="form-control"  >
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-4"><label class="control-label">Segundo Nombre:</label></div>
                                                                <div class="col-md-8"><input type="text" name="second_name" class="form-control"  >
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-4"><label class="control-label">Lugar de expedición:</label></div>
                                                                <div class="col-md-8">
                                                                    {!! Form::select('city_identity_card_id', $cities, null, ['placeholder' => 'Seleccione la expedición del ci', 'class' => 'form-control']) !!}
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-4"><label class="control-label">Apellido de Esposo:</label></div>
                                                                <div class="col-md-8"><input name="surname_husband" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-4"><label class="control-label">CUA/NUA:</label></div>
                                                                <div class="col-md-8"><input name="nua" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Teléfono:</label></div>
                                                                <div class="col-md-9">
                                                                    <div class="col-md-10">
                                                                        <div class="input-group">
                                                                            <input type="text" name="phone_number" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Celular:</label></div>
                                                                <div class="col-md-9">
                                                                    <div class="col-md-10">
                                                                        <div class="input-group">
                                                                            <input type="text" name="cell_phone_number" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- right --}}
                                                        <div class="col-md-6">
                                                            <div class="form-group row m-b-md">
                                                                <div class="col-sm-3 col-form-label"><label class="control-label">Fecha de vencimiento del CI:</label></div>
                                                                <div class="col-md-5"><input name="due_date" v-date type="text" class="form-control">
                                                                </div>                                                                
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Género:</label></div>
                                                                <div class="col-md-9"> {!! Form::select('gender', ['M'=>'Masculino','F'=>'Femenino'], null, ['placeholder'=> 'Seleccione el género', 'class' => 'form-control']) !!}</div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-sm-3 col-form-label"><label class="control-label">Fecha de Nacimiento:</label></div>
                                                                <div class="col-md-9"><input name="birth_date" v-date type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Estado Civil:</label></div>
                                                                <div class="col-md-9"> {!! Form::select('civil_status', ['C'=>'Casado(a)','S'=>'Soltero(a)','V'=>'Viuido(a)','D'=>'Divorciado(a)'], null, ['placeholder'=> 'Seleccione estado civil', 'class' => 'form-control']) !!}</div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Lugar de Nacimiento:</label></div>
                                                                <div class="col-md-9">{!! Form::select('city_birth_id', $birth_cities, null , ['placeholder' => 'Seleccione la expedición del ci', 'class' => 'form-control'])
                                                                    !!} </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Tipo de afiliado:</label></div>
                                                                <div class="col-md-9">{!! Form::select('type', ['Comando'=>'COMANDO','Batallón'=>'BATALLON'], null , ['placeholder' => 'Seleccione el tipo de afiliado', 'class' => 'form-control'])
                                                                    !!} </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Categoría:</label></div>
                                                                <div class="col-md-9">{!! Form::select('category_id', ['1'=>'0%','2'=>'35%','3'=>'45%','4'=>'55%','5'=>'65%','6'=>'75%','7'=>'85%','8'=>'100%'], null , ['placeholder' => 'Seleccione la categoría', 'class' => 'form-control'])
                                                                    !!} </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Ente Gestor:</label></div>
                                                                <div class="col-md-9">{!! Form::select('pension_entities_id', ['1'=>'AFP FUTURO','2'=>'AFP PREVISIÓN','3'=>'LA VITALICIA','4'=>'PROVIDA','5'=>'SENASIR'], null , ['placeholder' => 'Seleccione el ente gestor', 'class' => 'form-control'])
                                                                    !!} </div>
                                                            </div>
                                                            <div class="row m-b-md">
                                                                <div class="col-md-3"><label class="control-label">Grado:</label></div>
                                                                <div class="col-md-9">{!! Form::select('degree_id', $degrees, null , ['placeholder' => 'Seleccione el grado', 'class' => 'form-control'])
                                                                    !!} </div>
                                                            </div>  
                                                        </div>
                                                    
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="text-center">
                                                            <input class="ladda-button ladda-button-demo btn btn-primary" type="submit" data-style="expand-left" value="Crear">
                                                            <a href="{{ route('affiliate.index') }}"><button class="btn btn-danger" type="button"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button></a>
                                                        </div>
                                                    </div>
                                                    <br>
                                        </div>
                                    </div>
                                </div>
                        </div>
                </div>
            </div>
            </form>
@endsection