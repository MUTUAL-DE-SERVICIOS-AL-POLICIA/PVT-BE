@extends('layouts.app')

@section('content')



<div class="row m-t-lg">
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Datos Personales del Usuario</h3>
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
            </div>            
        </div>
        <div class="ibox-content">
        <form class="form-horizontal" action="{{route('registrar')}}" method="POST">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Usuario</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='username' id="formGroupExampleInput" placeholder="Usuario"> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Nombres</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='first_name' id="formGroupExampleInput" placeholder="Nombre"> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Apellidos</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='last_name' id="formGroupExampleInput" placeholder="Apellidos"> 
                    </div>        
                </div>    
                <div class="form-group">
                    <label class="col-lg-2 control-label">Celular</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='phone' id="formGroupExampleInput" placeholder="Celular"> 
                    </div>
                </div>    
                <div class="form-group">
                    <label class="col-lg-2 control-label">Departamento</label>
                    <div class="col-lg-10">
                        <select class="form-control m-b" name='city'>
                            @foreach( $cities as $city)
                        <option value="{{$city->id}}">{{$city->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>    
                <div class="form-group">
                    <label class="col-lg-2 control-label">Cargo</label>
                    <div class="col-lg-10">
                            <input type="text" class="form-control" name='position' id="formGroupExampleInput" placeholder="Cargo"> 
                    </div>
                </div>

            {{--$modules->find(2)->name--}}
                <div class="panel panel-primary">
                        <div class="panel-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            @foreach( $modules as $module)
                                <li><a href="#tab_{{$module->id}}" data-toggle="tab" title="{{$module->name}}">&nbsp;<i class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></i>&nbsp;</a></li>
                            @endforeach                            
                        </ul>
                    
                        <div class="tab-content">
                            @foreach($modules as $module)         
                                <div class="tab-pane" id="tab_{{$module->id}}">
                                    <h3 class="box-title">{{$module->name}}</h3> 
                                    @foreach($roles as $rol)
                                        @if($rol->module_id==$module->id)                                        
                                            <div class="i-checks"><label> <input type="checkbox" value=""> <i></i> {{$rol->name}} </label></div>
                                        @endif    
                                    @endforeach                             
                                </div>
                            @endforeach 
                        </div>                        
                    </div>
                        </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Contraseña</label> 
                    <div class="col-lg-10">
                            
                    <input type="password" name="password" placeholder="Password" class="form-control"></div>
                
                </div>
                <div>
                        <button class="btn btn-primary" type="submit">REGISTRAR</button>
                        
                    </div>
            </form>
        </div>            
    </div>        
</div>


 

                        
  

@endsection