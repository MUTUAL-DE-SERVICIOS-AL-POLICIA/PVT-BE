@extends('layouts.app')

@section('content')

<div class="row m-t-lg">
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Datos Personales del Usuario</h3>
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary"><i class="fa" ></i> </button>
            </div>
        </div>
        <div class="ibox-content">
            <form action="/update/{{$user->id}}" method="POST">
                <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                    <label class="col-lg-2 control-label">Usuario</label>
                    <div class="col-lg-10">
                    <input type="text" class="form-control" name='username' id="formGroupExampleInput" placeholder="Usuario" value="{{$user->username}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Nombres</label>
                    <div class="col-lg-10">
                    <input type="text" class="form-control" name='first_name' id="formGroupExampleInput" placeholder="Nombre" value="{{$user->first_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Apellidos</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='last_name' id="formGroupExampleInput" placeholder="Apellidos" value="{{$user->last_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Celular</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='phone' id="formGroupExampleInput" placeholder="Celular" value="{{$user->phone}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Departamento</label>
                    <div class="col-lg-10">
                            {!! Form::select('city_id', $cities, $user->city_id, ['class' => 'col-md-2 combobox form-control','required' => 'required']) !!}

                       {{-- <select class="form-control m-b" name='city'>
                            @foreach( $cities as $city)
                        <option value="{{$city->name}}">{{$city->name}}</option>


                            @endforeach--}}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Cargo</label>
                    <div class="col-lg-10">
                            <input type="text" class="form-control" name='position' id="formGroupExampleInput" placeholder="Cargo" value="position">
                    </div>
                </div>


            {{--$modules->find(2)->name--}}
                <div class="panel panel-primary">
                        <div class="panel-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            @foreach( $modules as $module)
                                <li><a href="#tab_{{$module->id}}" data-toggle="tab" title="{{$module->name}}">&nbsp;<i class="{{Util::IconModule($module->id)}}" aria-hidden="true"></i>&nbsp;</a></li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($modules as $module)
                                <div class="tab-pane" id="tab_{{$module->id}}">
                                    <h3 class="box-title">{{$module->name}}</h3>
                                    @foreach($roles as $rol)
                                        @if($rol->module_id==$module->id)
                                        @if ($user->hasRole($rol->id))
                                        <div class="i-checks"><label> <input type="checkbox" name="rol[]" checked value="{{$rol->id}}"> <i></i> {{$rol->name}} </label></div>
                                        @else
                                        <div class="i-checks"><label> <input type="checkbox" name="rol[]"  value="{{$rol->id}}"> <i></i> {{$rol->name}} </label></div>
                                        @endif

                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                        </div>
                </div>
                <show-password inline-template>
                    <div>
                        <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group">
                                        <div class="togglebutton">
                                            <label> <input type="checkbox" class="i-checks" v-model="contra" name="contra"> Modificar Contraseña </label>
                                      </div>
                                    </div>
                                </div>
                        </div>
                        <div v-if="contra">
                        <div class="form-group">
                            <div class="col-lg-10">
                            <input type="password" name="password" placeholder="Password" class="form-control" value="password"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Repita la Contraseña</label>
                            <div class="col-lg-10">
                            <input type="password" name="remember_token" placeholder="Password" class="form-control" value="remember_token"></div>
                        </div>
                    </div>
                    </div>
                </show-password>


                <div>
                    <button class="btn btn-primary" type="submit">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>






@endsection
