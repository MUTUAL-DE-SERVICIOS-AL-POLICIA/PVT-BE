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
                
                <form class="form-horizontal" action="{{route('registrar')}}" method="POST">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">                   
                <div class="form-group">
                    <label class="col-lg-2 control-label">Modulos</label>             
                <nom-module :roles="{{ $roles }}" inline-template>
                    <div>
                        <div class="col-lg-4">        
                            {!! Form::select('module', $modulesL, '', ['class' => 'col-md-2 combobox form-control','required' => 'required', 'v-model'=> 'module_id', 'ref'=>'idtxt']) !!}           
                            
                        </div>
                        
                        @{{module_id}}
                        <div v-if="module_id">
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <div class="tabs-left">
                                        <ul class="nav nav-tabs">
                                        
                                           
                                                <li v-for="role in roles_list">
                                                   
                                                <a :href=tab_role(role) v-if="role.module_id == module_id"> @{{role.name}} </a>

                                                    {{--  <a href="#" data-toggle="tab" title="@{{role.name}}">&nbsp;<i class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></i>&nbsp;</a>  --}}
                                                </li>
                                                        
                                        </ul>                                                     
                                        <div class="tab-content">
                                            asdasdas
                                            <div v-for="role in roles_list">
                                            <div class="tab-pane" :id=tab_role_action(role) v-if="role.id == id_role(role)" >
                                                    
                                                <h3 class="box-title">@{{role.name}}</h3> 
                                                    {{--  @foreach($actions as $action)
                                                        <div class="i-checks"><label> <input type="checkbox" name="action[]" value="{{$action->id}}"> <i></i> {{$action->name}} </label></div>
                                                        @endforeach         --}}
                                                    </div>                                                    
                                            </div> 
                                        </div>                        
                                    </div>
                                </div>
                            </div>
                        </div>       
                    </div>   
                </nom-module>
              
                <div class="text-right" center>
                    <button class="btn btn-primary" type="submit">GUARDAR</button>        
                </div> 
                </form>
            </div>            
        </div>        
    </div>
    </div>
@endsection