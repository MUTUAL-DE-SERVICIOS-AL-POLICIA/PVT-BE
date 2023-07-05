
<div class="col-lg-12">

    <div class="ibox">

        <div class="ibox-content">
                    <div class="row">
                        <div class="pull-left"> <legend > Información Personal</legend></div>
                        @can('update',$affiliate)
                            <div class="text-right">
                            @if(isset($affiliatedevice))
                                <button data-animation="flip" class="btn btn-danger" v-if="editing" @click="deleteDevice" data-placement="top" title="Desvincular móvil"><i class="fa fa-mobile" ></i> Desvincular móvil </button>
                                <button data-animation="flip" class="btn btn-danger" v-if="editing" @click="deleteEnrolled" data-placement="top" title="Desenrolar"><i class="fa fa-id-badge" aria-hidden="true"></i> Desenrolar</button>
                            @endif
                                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing" @if($is_editable == 0)disabled="disabled"@endif ><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
                            </div>
                        @endcan
                    </div>
                    <div class="row">
                        {{-- left --}}
                        <div class="col-md-6">
                            <div class="row m-b-md" :class="{ 'has-error': errors.has('identity_card') && editing }">
                                <div class="col-md-4"><label class="control-label">Cédula de identidad:</label></div>
                                <div class="col-md-8"><input name="identity_card" type="text" v-model="form.identity_card" class="form-control" :disabled="!editing" v-validate.initial="'required'">
                                    <div v-show="errors.has('identity_card') && editing">
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('identity_card') }}</span>
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
                                <div class="col-md-8"><input name="mothers_last_name" type="text" v-model="form.mothers_last_name" class="form-control" :disabled="!editing" v-validate.initial="'alpha_space_quote'">
                                    <div v-show="errors.has('mothers_last_name') && editing">
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('mothers_last_name') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-b-md" v-show="form.gender === 'F'" :class="{ 'has-error': errors.has('surname_husband') && editing }">
                                <div class="col-md-4"><label class="control-label">Apellido de Casada:</label></div>
                                <div class="col-md-8"><input name="surname_husband" type="text" class="form-control" v-model="form.surname_husband" :disabled="!editing" v-validate.initial="'alpha_space_quote'">
                                    <div v-show="errors.has('surname_husband') && editing">
                                        <i class="fa fa-warning text-danger"></i>
                                        <span class="text-danger">@{{ errors.first('surname_husband') }}</span>
                                    </div>
                                </div>
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
                            <div class="row m-b-md" :class="{'has-error': errors.has('due_date') && !form.is_duedate_undefined }">
                                <div class="col-md-4"><label class="control-label">Fecha de Vencimiento del CI</label></div>
                                  <div class="col-md-8">
                                    <input
                                      type="text"
                                      :disabled="form.is_duedate_undefined || !editing"
                                      name="due_date"
                                      v-model.trim="form.due_date"
                                      class="form-control"
                                      v-date
                                      v-validate="'required|date_format:dd/MM/yyyy'"
                                    >
                                    <br>
                                    <input
                                        class="mediumCheckbox"
                                        type="checkbox"
                                        :disabled="!editing"
                                        name="is_duedate_undefined"
                                        v-model="form.is_duedate_undefined"
                                        id="is_duedate_undefined"
                                    >
                                    <label for="is_duedate_undefined" class="pointer v-middle">
                                        Indefinido
                                    </label>
                                    <div
                                      v-show="errors.has('form.due_date') && !form.is_duedate_undefined "
                                    >
                                      <i class="fa fa-warning text-danger"></i>
                                      <span class="text-danger">@{{ errors.first('due_date') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- right --}}
                        <div class="col-md-6">
                            <div class="form-group row m-b-md" :class="{ 'has-error': errors.has('birth_date') && editing }">
                                <div class="col-sm-3 col-form-label"><label class="control-label">Fecha de Nacimiento:</label></div>
                                <div class="col-md-5"><input name="birth_date" v-model="form.birth_date" v-date type="text" class="form-control" :disabled="!editing" v-validate="'required|date_format:dd/MM/yyyy|max_date'">
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
                                <div class="col-md-9">{!! Form::select('city_birth_id', $birth_cities, null , ['placeholder' => 'Seleccione la expedición del ci', 'class' => 'form-control','v-model'=>'form.city_birth_id',':disabled'=>'!editing'])
                                    !!} </div>
                            </div>
                            <div class="row m-b-md">
                                <div class="col-md-3"><label class="control-label">Estado Civil:</label></div>
                                <div class="col-md-9"> {!! Form::select('civil_status', ['C'=>'Casado (a)','S'=>'Soltero (a)','V'=>'Viudo (a)','D'=>'Divorciado (a)'], null, ['placeholder'=> 'Seleccione estado civil', 'class' => 'form-control','v-model' => 'form.civil_status',':disabled'=>'!editing' ]) !!}</div>
                            </div>

                            <div class="row m-b-md">
                            <div class="col-sm-3 col-form-label"><label class="control-label">Fecha de Fallecimiento:</label></div>
                            <div class="col-md-9"><input name="date_death" v-model="form.date_death" v-date type="text" class="form-control" :disabled="!editing" v-validate="'date_format:dd/MM/yyyy|max_current_date'">
                                <div v-show="errors.has('date_death') && editing">
                                    <i class="fa fa-warning text-danger"></i>
                                    <span class="text-danger">@{{ errors.first('date_death') }}</span>
                                </div>
                            </div>
                            </div>
                            <div class="row m-b-md">
                            <div class="col-sm-3 col-form-label"><label class="control-label">Causa de Fallecimiento:</label></div>
                            <div class="col-md-9"><input name="reason_death" v-model="form.reason_death" type="text" class="form-control" :disabled="!editing">
                                <div v-show="errors.has('reason_death') && editing">
                                    <i class="fa fa-warning text-danger"></i>
                                    <span class="text-danger">@{{ errors.first('reason_death') }}</span>
                                </div>
                            </div>
                            </div>
                            <div class="row m-b-md">
                            <div class="col-sm-3 col-form-label"><label class="control-label">Nro de certificado de defunción::</label></div>
                            <div class="col-md-9"><input name="death_certificate_number" v-model="form.death_certificate_number" type="text" class="form-control" :disabled="!editing">
                                <div v-show="errors.has('death_certificate_number') && editing">
                                    <i class="fa fa-warning text-danger"></i>
                                    <span class="text-danger">@{{ errors.first('death_certificate_number') }}</span>
                                </div>
                            </div>
                            </div>

                            <div class="row m-b-md">
                                <div class="col-md-3"><label class="control-label">Celular:</label></div>
                                <div class="col-md-9">
                                    <div class="col-md-2">
                                        <button v-if="editing" class="btn btn-success" type="button" @click="addCellPhoneNumber"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-md-10">
                                        <div v-for="(cell_phone,index) in form.cell_phone_number" :key=`cell_phone-${index}`>
                                            <div class="input-group">
                                                <input type="text" name="cell_phone_number[]" v-model.trim="form.cell_phone_number[index]" class="form-control" v-cell-phone :disabled="!editing">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" v-if="form.cell_phone_number.length > 1 && editing" @click="deleteCellPhoneNumber(index)" type="button"><i class="fa fa-trash"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-b-md">
                                <div class="col-md-3"><label class="control-label">Teléfono:</label></div>
                                <div class="col-md-9">
                                    <div class="col-md-2">
                                        <button v-if="editing" class="btn btn-success" type="button" @click="addPhoneNumber"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-md-10">
                                        <div v-for="(phone,index) in form.phone_number" :key=`phone-${index}`>
                                            <div class="input-group">
                                                <input type="text" name="phone_number" v-model.trim="form.phone_number[index]" class="form-control" v-phone :disabled="!editing">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" v-if="form.phone_number.length > 1 && editing" @click="deletePhoneNumber(index)" type="button"><i class="fa fa-trash"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                <label class="control-label">N&uacute;mero</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" v-model.trim="form.address[0].number_address" class="form-control" :disabled="!editing">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-4"><strong>Número de cuenta:</strong></div>
                            <div class="col-md-8"><input name="account_number" type="text" v-model="form.account_number" class="form-control" :disabled="!editing">
                                <div v-show="errors.has('account_number') && editing">
                                    <i class="fa fa-warning text-danger"></i>
                                    <span class="text-danger">@{{ errors.first('account_number') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Entidad Financiera:</label>
                            </div>
                            <div class="col-md-8">
                                {!! Form::select('financial_entity_id', $financial_entities, null, ['placeholder' => 'Seleccione Entidad Financiera', 'class' => 'form-control','v-model'=> 'form.financial_entity_id',':disabled'=>'!editing' ]) !!}
                                <div v-show="errors.has('financial_entity_id') && editing" >
                                    <i class="fa fa-warning text-danger"></i>
                                    <span class="text-danger">@{{ errors.first('financial_entity_id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Estado Sigep:</label>
                            </div>
                            <div class="col-md-8">{!! Form::select('sigep_status', ['SIN REGISTRO'=>'SIN REGISTRO','REGISTRO OBSERVADO'=>'REGISTRO OBSERVADO','ACTIVO'=>'ACTIVO','ACTIVO-PAGO-VENTANILLA'=>'ACTIVO-PAGO-VENTANILLA'], null, ['placeholder' => 'Seleccione Estado', 'class' => 'form-control','v-model' => 'form.sigep_status',':disabled' => '!editing']) !!}
                                <div v-show="errors.has('sigep_status') && editing">
                                    <i class="fa fa-warning text-danger"></i>
                                    <span class="text-danger">@{{ errors.first('sigep_status') }}</span>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-show="editing">
                        <div class="text-center">
                            <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                            <button class="ladda-button ladda-button-demo btn btn-primary" type="button" @click="update" :disabled="validAll || loadingButton" data-style="expand-left">
                                <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
                                <i v-else class="fa fa-check-circle"></i>
                                &nbsp;
                                @{{ loadingButton ? 'Guardando...' : 'Guardar' }}
                            </button>
                        </div>
                    </div>
                    <br>
        </div>
    </div>
</div>