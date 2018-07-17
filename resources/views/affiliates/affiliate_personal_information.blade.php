
<div class="col-lg-12">
   
    <div class="ibox">
    
        <div class="ibox-content">

                    <div class="pull-left"> <legend > Información Personal</legend></div>
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
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="col-md-4"><strong>Ciudad:</strong></div>
                            <div class="col-md-8">{!! Form::select('city_address_id', $birth_cities, null , ['placeholder' => 'Seleccione departamento', 'class' => 'form-control','v-model'=>'form.address[0].city_address_id',':disabled'=>'!editing'])!!} </div>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-4">
                                <label class="control-label">Zona</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" v-model.trim="form.address[0].zone" class="form-control" :disabled="!editing">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-4">
                                <label class="control-label">Calle</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" v-model.trim="form.address[0].street" class="form-control" :disabled="!editing">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-4">
                                <label class="control-label">Numero</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" v-model.trim="form.address[0].number_address" class="form-control" :disabled="!editing">
                                </div>
                            </div>
                        </div>
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