
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
                        
                        {{-- left --}}
                        <div class="col-md-6">
                            <div class="row m-b-md" :class="{ 'has-error': errors.has('identity_card') && editing }">
                                <div class="col-md-4"><label class="control-label">Cedula de identidad:</label></div>
                                <div class="col-md-8"><input name="identity_card" type="text" v-model="form.identity_card" class="form-control" :disabled="!editing" v-validate.initial="'required'">
                                    <div v-show="errors.has('identity_card') && editing">
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('identity_card') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-b-md" :class="{ 'has-error': errors.has('city_identity_card_id') && editing }">
                                <div class="col-md-4"><label class="control-label">Lugar de expedición:</label></div>
                                <div class="col-md-8">
                                    {!! Form::select('city_identity_card_id', $cities, null, ['placeholder' => 'Seleccione la expedición del ci', 'class' => 'form-control','v-model'=> 'form.city_identity_card_id',':disabled'=>'!editing', 'v-validate.initial'=>"'required'" ]) !!}
                                    <div v-show="errors.has('city_identity_card_id') && editing" >
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('city_identity_card_id') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-b-md" :class="{ 'has-error': errors.has('first_name') && editing }">
                                <div class="col-md-4"><label class="control-label">Primer Nombre:</label></div>
                                <div class="col-md-8"><input type="text" name="first_name" v-model="form.first_name" class="form-control" :disabled="!editing" v-validate.initial="'required|alpha'">
                                    <div v-show="errors.has('first_name') && editing">
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('first_name') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-b-md">
                                <div class="col-md-4"><label class="control-label">Segundo Nombre:</label></div>
                                <div class="col-md-8"><input type="text" v-model="form.second_name" class="form-control" :disabled="!editing"></div>
                            </div>
                            <div class="row m-b-md" :class="{ 'has-error': errors.has('last_name') && editing }">
                                <div class="col-md-4"><label class="control-label">Apellido Paterno:</label></div>
                                <div class="col-md-8"><input type="text" name="last_name" v-model="form.last_name" class="form-control" :disabled="!editing" v-validate.initial="'required'">
                                    <div v-show="errors.has('last_name') && editing">
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('last_name') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-b-md">
                                <div class="col-md-4"><label class="control-label">Apellido Materno:</label></div>
                                <div class="col-md-8"><input type="text" v-model="form.mothers_last_name" class="form-control" :disabled="!editing"></div>
                            </div>
                            <div class="row m-b-md" v-show="form.gender === 'F'">
                                <div class="col-md-4"><label class="control-label">Apellido de Casada:</label></div>
                                <div class="col-md-8"><input type="text" class="form-control" v-model="form.surname_husband" :disabled="!editing"></div>
                            </div>
                            <div class="row m-b-md" :class="{ 'has-error': errors.has('gender') && editing }">
                                <div class="col-md-4"><label class="control-label">Genero:</label></div>
                                <div class="col-md-8">{!! Form::select('gender', ['F'=>'Femenino','M'=>'Masculino'], null, ['placeholder' => 'Seleccione genero', 'class' => 'form-control','v-model' => 'form.gender',':disabled' => '!editing', 'v-validate.initial'=>"'required'"]) !!}
                                    <div v-show="errors.has('gender') && editing">
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('gender') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- right --}}
                        <div class="col-md-6">
                            <div class="form-group row m-b-md" :class="{ 'has-error': errors.has('birth_date') && editing }">
                                <div class="col-sm-3 col-form-label"><label class="control-label">Fecha de Nacimiento:</label></div>
                                <div class="col-md-5"><input name="birth_date" v-model="form.birth_date" data-date="true" type="text" class="form-control" :disabled="!editing" v-validate="'required|date_format:DD/MM/YYYY|max_date'">
                                    <div v-show="errors.has('birth_date') && editing">
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('birth_date') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4"><input v-model="age" type="text" class="form-control" disabled></div>
                                {{-- <div class="col-md-1">Años</div> --}}
                            </div>
                            <div class="row m-b-md">
                                <div class="col-md-3"><label class="control-label">Lugar de Nacimiento:</label></div>
                                <div class="col-md-9">{!! Form::select('city_birth_id', $birth_cities, null , ['placeholder' => 'Seleccione la expedición del ci', 'class' => 'form-control','v-model'=>'form.city_birth_id',':disabled'=>'!editing', 'v-validate.initial'=> "'required'"])
                                    !!} </div>
                            </div>
                            <div class="row m-b-md">
                                <div class="col-md-3"><label class="control-label">Estado Civil:</label></div>
                                <div class="col-md-9"> {!! Form::select('civil_status', ['C'=>'Casado(a)','S'=>'Soltero(a)','V'=>'Viuido(a)','D'=>'Divorciado(a)'], null, ['placeholder'=> 'Seleccione estado civil', 'class' => 'form-control','v-model' => 'form.civil_status',':disabled'=>'!editing' ]) !!}</div>
                            </div>
                            <div class="row m-b-md">
                                <div class="col-md-3"><label class="control-label">Celular:</label></div>
                                <div class="col-md-9"><input type="text" v-model="form.cell_phone_number" class="form-control" :disabled="!editing"></div>
                            </div>
                            <div class="row m-b-md">
                                <div class="col-md-3"><label class="control-label">Telefono:</label></div>
                                <div class="col-md-9"><input type="text" v-model="form.phone_number" class="form-control" :disabled="!editing"></div>
                            </div>
                        </div>

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
                            <button class="btn btn-primary" type="button" @click="update" :disabled="validAll"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                        </div>
                    </div> 
                    <br>
        </div>
    </div>
    
     
</div>