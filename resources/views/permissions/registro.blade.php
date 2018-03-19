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
                
               
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">                   
          {{--      <input type="checkbox" class="js-switch" checked /> --}}
                <nom-module :roles="{{ $roles }}" :permissions="{{$permissions}}" :operations="{{$operations}}" :role_permissions="{{$role_permissions}}" :actions="{{$actions}}" inline-template>
                    <div>
                        <div class="row">
                            <label class="col-lg-2 control-label">Modulos</label>             
                            <div class="col-lg-4">        
                                {!! Form::select('module', $modulesL, '', ['class' => 'col-md-2 combobox form-control','required' => 'required', 'v-model'=> 'module_id', 'ref'=>'idtxt']) !!}           
                                
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="list-group">
                                    <div v-for="role of roles_module">
                                        
                                     {{-- <a href="#" class="list-group-item" v-for="role in roles_module">@{{role.name}}</a> --}}
                                        <button type="button" class="list-group-item" @click="SelectRol(role)" >@{{role.name}}</button>
                                    </div>
                                </ul>
                            </div>
                            <div class="col-md-8" v-if="role && module_id">
             
                                <legend>@{{role.name}}</legend>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Permiso</th>    
                                            <th>Crear</th>    
                                            <th>Ver</th>    
                                            <th>Editar</th>         
                                            <th>Borrar</th>
                                            <th>Imprimir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="operation in getOperationPermissionList">
                                            <td>@{{operation.name}}</td>
                                            <td><input type="checkbox" v-model="operation.create" > </td>
                                            <td><input type="checkbox" v-model="operation.read" ></td>
                                            <td><input type="checkbox" v-model="operation.update" ></td>
                                            <td><input type="checkbox" v-model="operation.delete" ></td>
                                            <td><input type="checkbox" v-model="operation.print" ></td>
                                        </tr>                                        
                                    </tbody>    
                                </table>
                                <button type="button" class="btn btn-primary"  @click="update" ><i class="fa fa-save"></i></button>
                                <button type="button" class="btn btn-info"  @click="selectAll" ><i :class="sw?'fa fa-check-square-o':'fa fa-square-o'"></i></button>

                            </div>

                        </div>
                    </div>   
                </nom-module>
            
                
            </div>            
        </div>        
    </div>
</div>

@endsection
@section('scripts')
{{-- <script src="{!! asset('js/switchery.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
     $(document).ready(function(){
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
     });
</script> --}}
@endsection