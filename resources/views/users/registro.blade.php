@extends('layouts.app')
@section('title', 'Registro')
@section('content')

<div class="row m-t-lg">
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Datos Personales del Usuario</h3>
            <div class="text-right">
               <br>
            </div>
        </div>
        <div class="ibox-content">
            
            <form class="form-horizontal" action="{{route('registrar')}}" method="POST">
                    
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @if(isset($user))
            <input type="hidden" name="user_id" value="{{$user->id}}">
            @endif
            <div class="form-group">
                <label class="col-lg-2 control-label">Usuario</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name='username' id="formGroupExampleInput" placeholder="Usuario" value=@if(isset($user)) "{{$user->username}}"@endif> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">Nombres</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name='first_name' id="formGroupExampleInput" placeholder="Nombre" value=@if(isset($user)) "{{$user->first_name}}"@endif> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">Apellidos</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name='last_name' id="formGroupExampleInput" placeholder="Apellidos" value=@if(isset($user)) "{{$user->last_name}}"@endif> 
                </div>        
            </div>    
            <div class="form-group">
                <label class="col-lg-2 control-label">Celular</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name='phone' id="formGroupExampleInput" placeholder="Celular" value=@if(isset($user)) "{{$user->phone}}"@endif> 
                </div>
            </div>     
            <div class="form-group">
                <label class="col-lg-2 control-label">Departamento</label>
                <div class="col-lg-10">        
                    @if(isset($user))
                        {!! Form::select('city', $cities, $user->city_id, ['class' => 'col-md-2 combobox form-control','required' => 'required']) !!}
                    @else
                        {!! Form::select('city', $citiesL, '', ['class' => 'col-md-2 combobox form-control','required' => 'required']) !!}
                    @endif       
                </div>
            </div>    
            <div class="form-group">
                <label class="col-lg-2 control-label">Cargo</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name='position' id="formGroupExampleInput" placeholder="Cargo" value=@if(isset($user)) "{{$user->position}}"@endif> 
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            @foreach( $modules as $module)
                                <li><a href="#tab_{{$module->id}}" data-toggle="tab" title="{{$module->name}}">&nbsp;<i class="{{Muserpol\Helpers\Util::IconModule($module->id)}}" aria-hidden="true"></i>&nbsp;</a></li>
                            @endforeach                            
                        </ul>
                        <div class="tab-content">
                            @foreach($modules as $module)         
                                <div class="tab-pane" id="tab_{{$module->id}}">
                                    <h3 class="box-title">{{$module->name}}</h3> 
                                    @foreach($roles as $rol)
                                        @if($rol->module_id==$module->id)
                                            @if(isset($user))
                                                @if ($user->hasRole($rol->id))
                                                    <div ><label> <input type="checkbox" class="i-checks" name="rol[]" checked value="{{$rol->id}}"> <i></i> {{$rol->name}} </label></div>
                                                @else
                                                    <div ><label> <input type="checkbox" class="i-checks" name="rol[]"  value="{{$rol->id}}"> <i></i> {{$rol->name}} </label></div>
                                                @endif 
                                            @else                                        
                                                <div ><label> <input type="checkbox" class="i-checks" name="rol[]" value="{{$rol->id}}"> <i></i> {{$rol->name}} </label></div>
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
                    @if(isset($user))
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
                                <label class="col-lg-2 control-label">Contraseña</label> 
                                <div class="col-lg-10">       
                                    <input type="password" name="password" placeholder="Password" class="form-control">
                                </div>               
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Repita la Contraseña</label> 
                                <div class="col-lg-10">       
                                    <input type="password" name="remember_token" placeholder="Password" class="form-control">
                                </div>               
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Contraseña</label> 
                            <div class="col-lg-10">       
                                <input type="password" name="password" placeholder="Password" class="form-control">
                            </div>               
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Repita la Contraseña</label> 
                            <div class="col-lg-10">       
                                <input type="password" name="remember_token" placeholder="Password" class="form-control">
                            </div>               
                        </div>
                    @endif
                </div>
            </show-password>
            @if(isset($user))
                <div class="text-right">
                    <button class="btn btn-primary" type="submit">GUARDAR</button>        
                </div>
            @else
                <div class="text-right" center>
                    <button class="btn btn-primary" type="submit">REGISTRAR</button>        
                </div>
            @endif
            </form>
        </div>            
    </div>        
</div>
</div>
@endsection