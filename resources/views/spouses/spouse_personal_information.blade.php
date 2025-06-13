<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
                <div class="pull-left">
                    <legend> Información Cónyuge</legend>
                </div>
                @can('update',$spouse)
                <div class="text-right">
                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing_ci()"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'"></i> Editar</button>
                </div>
                @endcan
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('identity_card') && editing }">
                        <div class="col-md-4"><label class="control-label">Cédula de identidad:</label></div>
                        <div class="col-md-8">
                          <div class="input-group">
                            <input name="identity_card" type="text" v-model="form.identity_card" class="form-control" :disabled="!editingIdentityCard" v-validate.initial="'required'">
                            <span class="input-group-btn">
                              <button class="btn btn-default" type="button" @click="getDataSpouse" :disabled="!editingIdentityCard">
                                <i class="fa" :class="isLoading ? 'fa-spinner fa-spin' : 'fa-search'"></i>
                              </button>
                            </span>
                            <div v-show="errors.has('identity_card') && editing">
                                  <i class="fa fa-warning text-danger"></i>
                                  <span class="text-danger">@{{ errors.first('identity_card') }}</span>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('first_name') && editing }">
                        <div class="col-md-4"><label class="control-label">Primer Nombre:</label></div>
                        <div class="col-md-8"><input type="text" name="first_name" v-model="form.first_name" class="form-control" :disabled="!editing" v-validate.initial="'required|alpha_space_quote'">
                            <div v-show="errors.has('first_name') && editing">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('first_name') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('second_name') && editing }">
                        <div class="col-md-4"><label class="control-label">Segundo Nombre:</label></div>
                        <div class="col-md-8"><input type="text" name="second_name" v-model="form.second_name" class="form-control" :disabled="!editing" v-validate.initial="'alpha_space_quote'">
                            <div v-show="errors.has('second_name') && editing">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('second_name') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('last_name') && editing }">
                        <div class="col-md-4"><label class="control-label">Apellido Paterno:</label></div>
                        <div class="col-md-8"><input type="text" name="last_name" v-model="form.last_name" class="form-control" :disabled="!editing" v-validate.initial="'alpha_space_quote'">
                            <div v-show="errors.has('last_name') && editing">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('last_name') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('mothers_last_name') && editing }">
                        <div class="col-md-4"><label class="control-label">Apellido Materno:</label></div>
                        <div class="col-md-8"><input name="mothers_last_name" type="text" v-model="form.mothers_last_name" class="form-control" :disabled="!editing"
                                v-validate.initial="'alpha_space_quote'">
                            <div v-show="errors.has('mothers_last_name') && editing">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('mothers_last_name') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('surname_husband') && editing }">
                        <div class="col-md-4"><label class="control-label">Apellido de Casada:</label></div>
                        <div class="col-md-8"><input name="surname_husband" type="text" class="form-control" v-model="form.surname_husband" :disabled="!editing" v-validate.initial="'alpha_space_quote'">
                            <div v-show="errors.has('surname_husband') && editing">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('surname_husband') }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row m-b-md" :class="{ 'has-error': errors.has('gender') && editing }">
                        <div class="col-md-4"><label class="control-label">Genero:</label></div>
                        <div class="col-md-8">{!! Form::select('gender', ['F'=>'Femenino','M'=>'Masculino'], null, ['placeholder' => 'Seleccione genero', 'class' =>
                            'form-control','v-model' => 'form.gender',':disabled' => '!editing', 'v-validate.initial'=>"'required'"]) !!}
                            <div v-show="errors.has('gender') && editing">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('gender') }}</span>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-6">
                    <div class="form-group row m-b-md" :class="{ 'has-error': errors.has('birth_date') && editing }">
                        <div class="col-sm-3 col-form-label"><label class="control-label">Fecha de Nacimiento:</label></div>
                        <div class="col-md-5"><input name="birth_date" v-model="form.birth_date" v-date type="text" class="form-control" :disabled="!editing"
                                v-validate="'required|date_format:dd/MM/yyyy|max_date'">
                            <div v-show="errors.has('birth_date') && editing">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('birth_date') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4"><input v-model="age" type="text" class="form-control" disabled></div>
                        {{--
                        <div class="col-md-1">Años</div> --}}
                    </div>
                    <div class="row m-b-md">
                        <div class="col-md-3"><label class="control-label">Lugar de Nacimiento:</label></div>
                        <div class="col-md-9">{!! Form::select('city_birth_id', $birth_cities, null , ['placeholder' => 'Seleccione la expedición del ci', 'class'
                            => 'form-control','v-model'=>'form.city_birth_id',':disabled'=>'!editing']) !!} </div>
                    </div>
                    <div class="row m-b-md">
                        <div class="col-md-3"><label class="control-label">Estado Civil:</label></div>
                        <div class="col-md-9"> {!! Form::select('civil_status', ['C'=>'Casado (a)','S'=>'Soltero (a)','V'=>'Viudo (a)','D'=>'Divorciado (a)'], null, ['placeholder'=>
                            'Seleccione estado civil', 'class' => 'form-control','v-model' => 'form.civil_status',':disabled'=>'!editing' ])
                            !!}</div>
                    </div>
                    <div class="row m-b-md">
                        <div class="col-md-3"><label class="control-label">Fecha de Fallecimiento:</label></div>
                        <div class="col-md-9"><input name="date_death" v-date v-model="form.date_death"  type="text" class="form-control" :disabled="!editing"></div>
                    </div>
                    <div class="row m-b-md">
                        <div class="col-md-3"><label class="control-label">Causa de Fallecimiento:</label></div>
                        <div class="col-md-9"><input name="reason_death" v-model="form.reason_death"  type="text" class="form-control" :disabled="!editing"></div>
                    </div>
                    <div class="row m-b-md">
                        <div class="col-md-3"><label class="control-label">Nro de certificado de defunción:</label></div>
                        <div class="col-md-9"><input name="death_certificate_number" v-model="form.death_certificate_number"  type="text" class="form-control" :disabled="!editing"></div>
                    </div>
                </div>
            </div>
            <div>
                <div class="hr-line-dashed"></div>
                <h3>Información de SERECI:</h3>
                <div class="row">
                  <div class="col-md-6" :class="{'has-error': errors.has('official') && editing }">
                    <div class="col-md-4">
                      <label class="control-label">Oficialia</label>
                    </div>
                    <div class="col-md-8">
                      <input
                        type="text"
                        name="official"
                        v-model.trim="form.official"
                        class="form-control"
                        :disabled="!editing"
                      >
                      <div v-show="errors.has('official') && editing">
                        <i class="fa fa-warning text-danger"></i>
                        <span class="text-danger">@{{ errors.first('official') }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6" :class="{'has-error': errors.has('book') && editing }">
                    <div class="col-md-4">
                      <label class="control-label">Libro</label>
                    </div>
                    <div class="col-md-8">
                      <input
                        type="text"
                        name="book"
                        v-model.trim="form.book"
                        class="form-control"
                        :disabled="!editing"
                      >
                      <div v-show="errors.has('book') && editing">
                        <i class="fa fa-warning text-danger"></i>
                        <span class="text-danger">@{{ errors.first('book') }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-6" :class="{'has-error': errors.has('departure') && editing }">
                    <div class="col-md-4">
                      <label class="control-label">Partida</label>
                    </div>
                    <div class="col-md-8">
                      <input
                        type="text"
                        name="departure"
                        v-model.trim="form.departure"
                        class="form-control"
                        :disabled="!editing"
                      >
                      <div v-show="errors.has('departure') && editing">
                        <i class="fa fa-warning text-danger"></i>
                        <span class="text-danger">@{{ errors.first('departure') }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6" :class="{'has-error': errors.has('marriage_date') && editing}">
                    <div class="col-md-4">
                      <label class="control-label">Fecha Matrimonio</label>
                    </div>
                    <div class="col-md-8">
                      <input
                        type="text"
                        name="marriage_date"
                        v-model.trim="form.marriage_date"
                        class="form-control"
                        v-date
                        v-validate="'date_format:dd/MM/yyyy|max_current_date'"
                        :disabled="!editing"
                      >
                      <div v-show="errors.has('marriage_date') && editing">
                        <i class="fa fa-warning text-danger"></i>
                        <span class="text-danger">@{{ errors.first('marriage_date') }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
              </div>
            <div class="row" v-if="editing">
                <div class="text-center">
                    <button class="btn btn-danger" type="button" @click="cancel_editing_ci()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                    <button class="btn btn-primary" type="button" @click="update" :disabled="validAll"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>