<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <legend>Datos del afiliado</legend>
            <div class="row">
                <div class="col-md-6" :class="{'has-error': errors.has('date_death') }" v-if="canAddDataAffiliate">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                                Fecha de Fallecimiento</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_death" v-model="date_death" v-date class="form-control">
                            <i v-show="errors.has('date_death')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('date_death')" class="text-danger">@{{ errors.first('date_death') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" :class="{'has-error': errors.has('degree') }">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                                Grado</label>
                        <div class="col-sm-8">
                            <select name="degree" class="form-control" v-model="affiliate.degree_id" v-validate.initial="'required'" disabled>
                                <option :value="null"></option>
                                <option v-for="(degree, index) in degrees" :value="degree.id">@{{ degree.name }}</option>
                            </select>
                            <i v-show="errors.has('degree')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('degree')" class="text-danger">@{{ errors.first('degree') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox float-e-margins">    
        <div class="ibox-content">            
            <form method="get" class="form-horizontal">
                <div class="col-md-12">
                    <legend>Datos del Solicitante</legend>
                    <div class="row">
                        <div class="col-md-6" :class="{'has-error': errors.has('accountType') }">
                            <div class="col-md-4">
                                <label class="control-label">Tipo de Solicitante</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" autofocus name="accountType" @change="change_applicant()" v-model.trim="applicant_type" v-validate.initial="'required'">
                                    <option :value="null"></option>
                                    <option v-for="(type,index) in applicant_types" :value="index+1">@{{type}}</option>
                                </select>
                                <i v-show="errors.has('accountType')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('accountType')" class="text-danger">@{{ errors.first('accountType') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6" :class="{'has-error': errors.has('applicant_kinship') }">
                            <div class="col-md-4">
                                <label class="control-label">Parentesco</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" name="applicant_kinship" v-model.trim="applicant_kinship_id" v-validate.initial="'required'">
                                    <option :value="null"></option>
                                    <option v-for="(kinship, index) in kinshipsFilter" :value="kinship.id" :key="index">@{{ kinship.name }}</option>
                                </select>
                                <i v-show="errors.has('applicant_kinship')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('applicant_kinship')" class="text-danger">@{{ errors.first('applicant_kinship') }}</span>
                            </div>
                        </div>
                    </div> 
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-error': errors.has('applicant_identity_card') }">
                                <div class="col-md-4">
                                    <label class="control-label">cédula de identidad</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="applicant_identity_card" v-model.trim="applicant_identity_card" class="form-control" v-validate.initial="'required'">
                                        <span class="input-group-btn">
                                            <button class="btn" :class="errors.has('applicant_identity_card') ? 'btn-danger' : 'btn-primary' " type="button" @click="searchApplicant" type="button" role="button"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                    <i v-show="errors.has('applicant_identity_card')" class="fa fa-warning text-danger"></i>
                                    <span v-show="errors.has('applicant_identity_card')" class="text-danger">@{{ errors.first('applicant_identity_card') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Primer Nombre</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="applicant_first_name" v-model.trim="applicant_first_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Segundo Nombre</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="applicant_second_name" v-model.trim="applicant_second_name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Apellido Paterno</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="applicant_last_name" v-model.trim="applicant_last_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Apellido Materno</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="applicant_mothers_last_name" v-model.trim="applicant_mothers_last_name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Apellido de Casada</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" :disabled="applicantIsMale" name="applicant_surname_husband" v-model.trim="applicant_surname_husband" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-8">
                                   
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6" :class="{'has-error': errors.has('applicant_birth_date') }">
                            <div class="col-md-4">
                                <label class="control-label">Fecha de Nacimiento</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" v-date name="applicant_birth_date" v-model.trim="applicant_birth_date" v-validate.initial="'required|date_format:dd/MM/yyyy'">
                                <i v-show="errors.has('applicant_birth_date')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('applicant_birth_date')" class="text-danger">@{{ errors.first('applicant_birth_date') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6" :class="{'has-error': errors.has('applicant_gender') }">
                            <div class="col-md-4">
                                <label class="control-label">Genero</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control m-b" name="applicant_gender" v-model.trim="applicant_gender" v-validate.initial="'required'">
                                    <option :value="null"></option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                <i v-show="errors.has('applicant_gender')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('applicant_gender')" class="text-danger">@{{ errors.first('applicant_gender') }}</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Teléfono del Solicitante</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-2">
                                        <button class="btn btn-success" type="button" @click="addPhoneNumber"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-md-10">
                                        <div v-for="(phone,index) in applicant_phone_numbers">
                                            <div class="input-group">
                                                <input type="text" name="applicant_phone_number[]" v-model.trim="phone.value" :key="index" class="form-control" data-phone="true">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" v-show="applicant_phone_numbers.length > 1" @click="deletePhoneNumber(index)" type="button"><i class="fa fa-trash"></i></button>
                                                </span>
                                            </div><!-- /input-group -->
                                        </div>
                                    </div>
                                </div>
                               
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                    <label class="control-label">Celular del Solicitante</label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-2">
                                        <button class="btn btn-success" type="button" @click="addCellPhoneNumber"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-md-10">
                                        <div v-for="(cell_phone,index) in applicant_cell_phone_numbers">
                                            <div class="input-group">
                                                <input type="text" name="applicant_cell_phone_number[]" v-model.trim="cell_phone.value" :key="index" class="form-control" data-cell-phone="true">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" v-show="applicant_cell_phone_numbers.length > 1" @click="deleteCellPhoneNumber(index)" type="button"><i class="fa fa-trash"></i></button>
                                                </span>
                                            </div><!-- /input-group -->
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
                    <br>
                    <legend v-if="show_advisor_form" >Datos del Tutor Legal</legend>
                    <div class="row"  v-if="show_advisor_form">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Nombre de Juzgado</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="advisor_name_court" v-model.trim="advisor_name_court" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Nro de Resolucion</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="advisor_resolution_number" v-model.trim="advisor_resolution_number" class="form-control">  
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if="show_advisor_form" >
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Fecha de Resolucion</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="advisor_resolution_date" v-model.trim="advisor_resolution_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                              
                            </div>
                            <div class="col-md-8">
                              
                            </div>
                        </div>
                    </div>
                    <br>

                    <legend v-if=" show_apoderado_form " >Datos del Apoderado</legend>
                    <div class="row" v-if="show_apoderado_form ">
                        <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_identity_card') }">
                            <div class="col-md-4">
                                <label class="control-label">cédula de identidad</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" name="legal_guardian_identity_card" v-model.trim="legal_guardian_identity_card" class="form-control" v-validate.initial="'required'">
                                    <span class="input-group-btn">
                                        <button class="btn" :class="errors.has('legal_guardian_identity_card') ? 'btn-danger' : 'btn-primary'" type="button" @click="searchLegalGuardian" type="button" role="button"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                                <i v-show="errors.has('legal_guardian_identity_card')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('legal_guardian_identity_card')" class="text-danger">@{{ errors.first('legal_guardian_identity_card') }}</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if="show_apoderado_form">
                        <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_first_name') }" >
                            <div class="col-md-4">
                                <label class="control-label">Primer Nombre</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_first_name" v-model.trim="legal_guardian_first_name" class="form-control" v-validate.initial="'required'">
                                <i v-show="errors.has('legal_guardian_first_name')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('legal_guardian_first_name')" class="text-danger">@{{ errors.first('legal_guardian_first_name') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Segundo Nombre</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_second_name" v-model.trim="legal_guardian_second_name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if=" show_apoderado_form ">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Apellido Paterno</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_last_name" v-model.trim="legal_guardian_last_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Apellido Materno</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_mothers_last_name" v-model.trim="legal_guardian_mothers_last_name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if=" show_apoderado_form ">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Apellido de Casada</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_surname_husband" v-model.trim="legal_guardian_surname_husband" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                           
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if=" show_apoderado_form ">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Nro de Poder</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_number_authority" v-model.trim="legal_guardian_number_authority" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class=" control-label">Notaria de Fe Publica Nro</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_notary_of_public_faith" v-model.trim="legal_guardian_notary_of_public_faith" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if=" show_apoderado_form ">
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Notario</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_notary" v-model.trim="legal_guardian_notary" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-4"></div>
                            <div class="col-md-8"></div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
            </form>
        </div>
    </div>
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <legend>Dirección del Solicitante (@{{ applicant_type }})</legend>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Ciudad</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="beneficiary_city_address_id" v-model.trim="beneficiary_city_address_id">
                                <option :value="null"></option>
                                <option v-for="city in cities" :value="city.id">@{{ city.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group"><label class="col-sm-4 control-label">Zona</label>
                        <div class="col-sm-8"><input type="text" name="beneficiary_zone" v-model.trim="beneficiary_zone" class="form-control"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group"><label class="col-sm-4 control-label">Calle</label>
                        <div class="col-sm-8"><input type="text" name="beneficiary_street" v-model.trim="beneficiary_street" class="form-control"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group"><label class="col-sm-4 control-label">Nro</label>
                        <div class="col-sm-8"><input type="text" name="beneficiary_number_address" v-model.trim="beneficiary_number_address" class="form-control"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox float-e-margins" v-if="canAddDataSpouse">
        <div class="ibox-content">
            <legend>Datos del o de la Cónyuge</legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('spouse_identity_card')  }">
                        <div class="col-md-4"><label class="control-label">Cédula de identidad:</label></div>
                        <div class="col-md-8"><input name="spouse_identity_card" type="text" v-model="spouse_identity_card" class="form-control" 
                                v-validate.initial="'required'">
                            <div v-show="errors.has('spouse_identity_card')">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_identity_card') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('spouse_city_identity_card_id')  }">
                        <div class="col-md-4"><label class="control-label">Lugar de expedición:</label></div>
                        <div class="col-md-8">
                            <select class="form-control" name="spouse_city_identity_card_id" v-model.trim="spouse_city_identity_card_id" v-validate.initial="'required'">
                                <!-- <option :value="null"></option> -->
                                <option v-for="city in cities" :value="city.id">@{{ city.name }}</option>
                            </select>
                            <div v-show="errors.has('spouse_city_identity_card_id')">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_city_identity_card_id') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('spouse_first_name')  }">
                        <div class="col-md-4"><label class="control-label">Primer Nombre:</label></div>
                        <div class="col-md-8"><input type="text" name="spouse_first_name" v-model="spouse_first_name" class="form-control"  v-validate.initial="'required|alpha_space_quote'">
                            <div v-show="errors.has('spouse_first_name')">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_first_name') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('spouse_second_name')  }">
                        <div class="col-md-4"><label class="control-label">Segundo Nombre:</label></div>
                        <div class="col-md-8"><input type="text" name="spouse_second_name" v-model="spouse_second_name" class="form-control"  v-validate.initial="'alpha_space_quote'">
                            <div v-show="errors.has('spouse_second_name')">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_second_name') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('spouse_last_name')  }">
                        <div class="col-md-4"><label class="control-label">Apellido Paterno:</label></div>
                        <div class="col-md-8"><input type="text" name="spouse_last_name" v-model="spouse_last_name" class="form-control"  v-validate.initial="'alpha_space_quote'">
                            <div v-show="errors.has('spouse_last_name') ">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_last_name') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('spouse_mothers_last_name')  }">
                        <div class="col-md-4"><label class="control-label">Apellido Materno:</label></div>
                        <div class="col-md-8"><input name="spouse_mothers_last_name" type="text" v-model="spouse_mothers_last_name" class="form-control" 
                                v-validate.initial="'alpha_space_quote'">
                            <div v-show="errors.has('spouse_mothers_last_name') ">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_mothers_last_name') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('spouse_surname_husband')  }">
                        <div class="col-md-4"><label class="control-label">Apellido de Casada:</label></div>
                        <div class="col-md-8"><input name="spouse_surname_husband" type="text" class="form-control" v-model="spouse_surname_husband" 
                                v-validate.initial="'alpha_space_quote'">
                            <div v-show="errors.has('spouse_surname_husband') ">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_surname_husband') }}</span>
                            </div>
                        </div>
                    </div>
                    {{--
                    <div class="row m-b-md" :class="{ 'has-error': errors.has('gender')  }">
                        <div class="col-md-4"><label class="control-label">Genero:</label></div>
                        <div class="col-md-8">{!! Form::select('gender', ['F'=>'Femenino','M'=>'Masculino'], null, ['placeholder' => 'Seleccione genero', 'class'
                            => 'form-control','v-model' => 'spouse_gender',':disabled' => '!editing', 'v-validate.initial'=>"'required'"])
                            !!}
                            <div v-show="errors.has('gender') ">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('gender') }}</span>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-6">
                    <div class="form-group row m-b-md" :class="{ 'has-error': errors.has('spouse_birth_date')  }">
                        <div class="col-sm-3 col-form-label"><label class="control-label">Fecha de Nacimiento:</label></div>
                        <div class="col-md-5"><input name="spouse_birth_date" v-model="spouse_birth_date" v-date type="text" class="form-control" v-validate.initial="'required|date_format:dd/MM/yyyy'">
                            <div v-show="errors.has('spouse_birth_date') ">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_birth_date') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4"><input v-model="age" type="text" class="form-control" disabled></div>
                        {{--
                        <div class="col-md-1">Años</div> --}}
                    </div>
                    <div class="row m-b-md">
                        <div class="col-md-3" :class="{'has-error': errors.has('spouse_city_birth_id') }"><label class="control-label">Lugar de Nacimiento:</label></div>
                        <div class="col-md-9">
                            <select class="form-control" name="spouse_city_birth_id" v-model.trim="spouse_city_birth_id" v-validate.initial="'required'">
                                <option :value="null"></option>
                                <option v-for="city in cities" :value="city.id">@{{ city.name }}</option>
                            </select>
                            <i v-show="errors.has('spouse_city_birth_id')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('spouse_city_birth_id')" class="text-danger">@{{ errors.first('spouse_city_birth_id') }}</span>
                        </div>
                    </div>
                    <div class="row m-b-md">
                        <div class="col-md-3"><label class="control-label">Estado Civil:</label></div>
                        <div class="col-md-9"> {!! Form::select('spouse_civil_status', ['C'=>'Casado (a)','S'=>'Soltero (a)','V'=>'Viudo (a)','D'=>'Divorciado (a)'],
                            null, ['placeholder'=> 'Seleccione estado civil', 'class' => 'form-control','v-model' => 'spouse_civil_status'
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group row m-b-md" :class="{ 'has-error': errors.has('spouse_date_death')  }">
                        <div class="col-sm-3 col-form-label"><label class="control-label">Fecha de Fallecimiento:</label></div>
                        <div class="col-md-9"><input name="spouse_date_death" v-model="spouse_date_death" v-date type="text" class="form-control" v-validate.initial="'required|date_format:dd/MM/yyyy'">
                            <div v-show="errors.has('spouse_date_death') ">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_date_death') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-b-md" :class="{ 'has-error': errors.has('spouse_reason_death')  }">
                        <div class="col-sm-3 col-form-label"><label class="control-label">Causa de Fallecimiento:</label></div>
                        <div class="col-md-9"><input name="spouse_reason_death" v-model="spouse_reason_death" data-reason="true" type="text" class="form-control">
                            <div v-show="errors.has('spouse_reason_death') ">
                                <i class="fa fa-warning text-danger"></i>
                                <span class="text-danger">@{{ errors.first('spouse_reason_death') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
