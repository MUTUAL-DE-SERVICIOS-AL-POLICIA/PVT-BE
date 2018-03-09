@extends('layouts.app')
@section('title', 'Registro')
@section('content')
<div class="row m-t-lg">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="pull-left">Asignacion de Permisos</h3> <br>          
            </div>
            <div class="ibox-content">
                @if(isset($user))
                    <form class="form-horizontal" action="/update/{{$user->id}}" method="POST">
                @else
                    <form class="form-horizontal" action="{{route('registrar')}}" method="POST">
                @endif
            
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                <div class="form-group">
                    <label class="col-lg-2 control-label">Modulos</label>
                
                    <show-password inline-template>
                    <div class="col-lg-4">        
                        {!! Form::select('module', $modulesL, '', ['class' => 'col-md-2 combobox form-control','required' => 'required' ]) !!}           
                    </div>
                    @{{contra}}
                   
                </show-password>
                </div>

            
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                @foreach( $roles as $role)
                                    <li><a href="#tab_{{$role->id}}" data-toggle="tab" title="{{$role->name}}">&nbsp;<i class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></i>&nbsp;</a></li>
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
                                                        <div class="i-checks"><label> <input type="checkbox" name="rol[]" checked value="{{$rol->id}}"> <i></i> {{$rol->name}} </label></div>
                                                    @else
                                                        <div class="i-checks"><label> <input type="checkbox" name="rol[]"  value="{{$rol->id}}"> <i></i> {{$rol->name}} </label></div>
                                                    @endif 
                                                @else                                        
                                                    <div class="i-checks"><label> <input type="checkbox" name="rol[]" value="{{$rol->id}}"> <i></i> {{$rol->name}} </label></div>
                                                @endif
                                            @endif    
                                        @endforeach                             
                                    </div>
                                @endforeach 
                            </div>                        
                        </div>
                    </div>
                </div>       
                
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