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
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Usuario</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='username' id="formGroupExampleInput" placeholder="Usuario"> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Nombres</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='username' id="formGroupExampleInput" placeholder="Nombre"> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Apellidos</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='username' id="formGroupExampleInput" placeholder="Apellidos"> 
                    </div>        
                </div>    
                <div class="form-group">
                    <label class="col-lg-2 control-label">Celular</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name='username' id="formGroupExampleInput" placeholder="Celular"> 
                    </div>
                </div>    
                <div class="form-group">
                    <label class="col-lg-2 control-label">Departamento</label>
                    <div class="col-lg-10">
                         
                    </div>
                </div>    
                <div class="form-group">
                    <label class="col-lg-2 control-label">Cargo</label>
                    <div class="col-lg-10">
                         
                    </div>
                </div>

            {{$modules->find(2)->name}}
                <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            @foreach( $modules as $module)
                        <li><a href="#tab_.{{$module->id}}" data-toggle="tab" title="{{$module->name}}">&nbsp;<i class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></i>&nbsp;</a></li>
                            
                            @endforeach
                                                    
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <h3 class="box-title">Fondo de Retiro</h3>    
                                                      
                            </div>
                           
                        </div>
                    </div>


            </form>

           


        </div>            
    </div>        
</div>


 

                        
  

@endsection