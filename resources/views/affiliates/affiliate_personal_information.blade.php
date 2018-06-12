
<div class="col-lg-12">
   
    <div class="ibox">
    
        <div class="ibox-content">

                <h3 data-toggle="tooltip" data-placement="top" title="Ver Información del Affiliado - {{ $affiliate->fullName('capitalize') }}"
                        class="pull-left"><a href="{{route('affiliate.show', $affiliate->id)}}" style="color: #fff">Información Personal</a></h3>
                    @can('update',$affiliate)
                    <div class="text-right">
                        <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
                    </div>
                    @else
                    <br>
                    @endcan
                    
                    <br>
                    <div class="row">
                        
                        <div class="col-md-2"><strong>Cedula de identidad:</strong></div>
                        <div class="col-md-4"><input type="text" v-model="form.identity_card" class="form-control" :disabled="!editing"></div>
                        <div class="col-md-2"><strong>Lugar de expedición:</strong></div>
                        <div class="col-md-4">{!! Form::select('city_identity_card_id',
                                $cities, null, ['placeholder' => 'Seleccione la expedición del ci', 'class' => 'form-control','v-model'=> 'form.city_identity_card_id',':disabled'=>'!editing' ]) !!}</div>
                        
                    </div>
                    <br>
                    <div class="row">
                        
                        <div class="col-md-2"><strong>Primer Nombre:</strong></div>
                        <div class="col-md-4"><input type="text" v-model="form.first_name" class="form-control" :disabled="!editing"></div>
                        <div class="col-md-2"><strong>Segundo Nombre:</strong></div>
                        <div class="col-md-4"><input type="text" v-model="form.second_name" class="form-control" :disabled="!editing"></div>
                        
                    </div>
                    <br>
                    <div class="row">
                        
                        <div class="col-md-2"><strong>Apellido Paterno:</strong></div>
                        <div class="col-md-4"><input type="text" v-model="form.last_name" class="form-control" :disabled="!editing"></div>
                        <div class="col-md-2"><strong>Apellido Materno:</strong></div>
                        <div class="col-md-4"><input type="text" v-model="form.mothers_last_name" class="form-control" :disabled="!editing"></div>
                        
                    </div>
                    <br>
                    <div class="row" v-show="affiliate.gender === 'F'">
                        
                        <div class="col-md-2"><strong>Apellido de Casada:</strong></div>
                        <div class="col-md-4"><input type="text" class="form-control" :disabled="!editing"></div>
                        <div class="col-md-2"></div>
                        <div class="col-md-4"></div>
                        
                    </div>
                    <br>
                    <div class="row">
                        
                        <div class="col-md-2"><strong>Genero:</strong></div>
                        <div class="col-md-4">{!! Form::select('gender', ['F'=>'Femenino','M'=>'Masculino'], null, ['placeholder' => 'Seleccione genero', 'class' => 'form-control','v-model'=> 'form.gender',':disabled'=>'!editing']) !!}</div>
                        <div class="col-md-2"><strong>Estado Civil:</strong></div>
                        <div class="col-md-4"> {!! Form::select('civil_status', ['C'=>'Casado(a)','S'=>'Soltero(a)','V'=>'Viuido(a)','D'=>'Divorciado(a)'], null, ['placeholder'=> 'Seleccione estado civil', 'class' => 'form-control','v-model' => 'form.civil_status',':disabled'=>'!editing' ]) !!}</div>
                        
                    </div>
                    <br>
                    <div class="row">
                        
                        <div class="col-md-2"><strong>Fecha de Nacimiento:</strong></div>
                        <div class="col-md-4">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input v-model="form.birth_date" type="text" class="form-control" :disabled="!editing">
                            </div>
                        </div>
                        <div class="col-md-2"><strong>Lugar de Nacimiento:</strong></div>
                        <div class="col-md-4">{!! Form::select('city_birth_id', $birth_cities, null , ['placeholder' => 'Seleccione la expedición del ci', 'class' => 'form-control','v-model'=>'form.city_birth_id',':disabled'=>'!editing']) !!} </div>
                        
                    </div>
                    <br>
                    <div class="row">
                        
                        <div class="col-md-2"><strong>Edad:</strong></div>
                        <div class="col-md-4"><input v-model="age" type="text" class="form-control" disabled></div>
                        <div class="col-md-2"><strong>Telefono:</strong></div>
                        <div class="col-md-4"><input type="text" v-model="form.phone_number" class="form-control" :disabled="!editing"></div>
                        
                    </div>
                    <br>
                    <div class="row">
                        
                        <div class="col-md-2"><strong>Celular:</strong></div>
                        <div class="col-md-4"><input type="text" v-model="form.cell_phone_number" class="form-control" :disabled="!editing"></div>
                        <div class="col-md-2"></div>
                        <div class="col-md-4"></div>
                        
                    </div>
                    <br>
                    <div class="row" v-show="editing">
                        <div class="text-center">
                            <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                            <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                        </div>
                    </div> 
                    <br>
            
        </div>
    </div>
    
     
</div>