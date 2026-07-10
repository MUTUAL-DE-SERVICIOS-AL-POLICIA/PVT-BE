<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <legend>Datos del afiliado</legend>
            <div class="row">
                <div class="col-md-3" :class="{'has-error': errors.has('date_entry') }">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Fecha de Ingreso</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_entry" v-model="date_entry" v-month-year class="form-control" v-validate.initial="'required|max_current_date_month_year'">
                            <i v-show="errors.has('date_entry')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('date_entry')" class="text-danger">@{{ errors.first('date_entry') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" :class="{'has-error': errors.has('date_derelict') }" v-if="!validDateDerelict">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Fecha de desvinculaci&oacute;n</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_derelict" v-model="date_derelict" v-month-year class="form-control" v-validate.initial="'required|max_current_date_month_year'">
                            <i v-show="errors.has('date_derelict')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('date_derelict')" class="text-danger">@{{ errors.first('date_derelict') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" v-if="validDateDerelict" >
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Fecha de desvinculaci&oacute;n</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_derelict" v-model="date_derelict" v-month-year class="form-control" v-validate.initial="'required|max_current_date_month_year'">
                        </div>
                    </div>
                </div>
                <template v-if="has_ret_fun">
                    <div class="col-md-3" :class="{'has-error': errors.has('date_entry_reinstatement') }">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                Fecha de Ingreso (Reincorporación)</label>
                            <div class="col-sm-8">
                                <input type="text" name="date_entry_reinstatement" v-model="date_entry_reinstatement" v-month-year class="form-control" v-validate.initial="'required|max_current_date_month_year'">
                                <i v-show="errors.has('date_entry_reinstatement')" class="fa fa-warning text-danger"></i>
                                    <span v-show="errors.has('date_entry_reinstatement')" class="text-danger">@{{ errors.first('date_entry_reinstatement') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" :class="{'has-error': errors.has('date_derelict_reinstatement') }" v-if="!validDateDerelict">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                Fecha de desvinculaci&oacute;n (Reincorporación)</label>
                            <div class="col-sm-8">
                                <input type="text" name="date_derelict_reinstatement" v-model="date_derelict_reinstatement" v-month-year class="form-control" v-validate.initial="'required|max_current_date_month_year'">
                                <i v-show="errors.has('date_derelict_reinstatement')" class="fa fa-warning text-danger"></i>
                                    <span v-show="errors.has('date_derelict_reinstatement')" class="text-danger">@{{ errors.first('date_derelict_reinstatement') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="validDateDerelict" >
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                Fecha de desvinculaci&oacute;n</label>
                            <div class="col-sm-8">
                                <input type="text" name="date_derelict_reinstatement" v-model="date_derelict_reinstatement" v-month-year class="form-control" v-validate.initial="'required|max_current_date_month_year'">
                            </div>
                        </div>
                    </div>
                </template>
                <div class="col-md-3" :class="{'has-error': errors.has('date_death') }" v-if="isDeathMode">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Fecha de Fallecimiento</label>
                        <div class="col-sm-8">
                            <input type="text" v-date name="date_death" v-model="date_death" class="form-control" placeholder="DD/MM/YYYY" v-validate.initial="'required|date_format:dd/MM/yyyy|max_current_date'">
                            <i v-show="errors.has('date_death')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('date_death')" class="text-danger">@{{ errors.first('date_death') }}</span>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-3" :class="{'has-error': errors.has('reason_death') }" v-if="isDeathMode"> --}}
                <div class="col-md-3" v-if="isDeathMode">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Causa de Fallecimiento</label>
                        <div class="col-sm-8">
                            <input type="text" name="reason_death" v-model="reason_death" class="form-control">
                            {{-- <i v-show="errors.has('reason_death')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('reason_death')" class="text-danger">@{{ errors.first('reason_death') }}</span> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox float-e-margins">
        {{-- <div class="ibox-title">
            <h5>Datos del Solicitante <small> @{{modality}}</small></h5>
            <div class="ibox-tools">
            </div>
        </div> --}}
        {{-- <div class="ibox-content" style="padding:0;border:0px;height:450px;overflow-y:auto"> en caso de que se quiera hacer statico el panel hdp --}}
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
                                <select class="form-control" name="accountType" @change="change_applicant()" v-model.trim="applicant_type" v-validate.initial="'required'">
                                    <option :value="null"></option>
                                    <option v-for="(type,index) in applicant_types_filter" :value="type.id">@{{type.name}}</option>
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
                                    <label class="control-label">Cédula de Identidad</label>
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
                        <div class="col-md-6">
                            <div class="col-md-4">
                                <label class="control-label">Fecha de Nacimiento</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" v-date class="form-control" v-model.trim="applicant_birth_date" name="applicant_birth_date">
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
                                                <input type="text" name="applicant_phone_number[]" v-model.trim="phone.value" :key="index" class="form-control" v-phone>
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
                                                <input type="text" name="applicant_cell_phone_number[]" v-model.trim="cell_phone.value" :key="index" class="form-control" v-cell-phone>
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
                                <input type="date" name="advisor_resolution_date" v-model.trim="advisor_resolution_date" class="form-control">
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
                                <label class="control-label">Cédula de Identidad</label>
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
                        <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_gender') }">
                            <div class="col-md-4">
                                <label class="control-label">Genero</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control m-b" name="legal_guardian_gender" v-model.trim="legal_guardian_gender" v-validate.initial="'required'">
                                    <option :value="null"></option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                <i v-show="errors.has('legal_guardian_gender')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('legal_guardian_gender')" class="text-danger">@{{ errors.first('legal_guardian_gender') }}</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if=" show_apoderado_form ">
                        <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_number_authority') }">
                            <div class="col-md-4">
                                <label class="control-label">Nro de Poder</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_number_authority" v-model.trim="legal_guardian_number_authority" class="form-control" v-validate.initial="'required'">
                                <i v-show="errors.has('legal_guardian_number_authority')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('legal_guardian_number_authority')" class="text-danger">@{{ errors.first('legal_guardian_number_authority') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_notary_of_public_faith') }">
                            <div class="col-md-4">
                                <label class=" control-label">Notaria de Fe Publica Nro</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_notary_of_public_faith" v-model.trim="legal_guardian_notary_of_public_faith" class="form-control" v-validate.initial="'required'">
                                <i v-show="errors.has('legal_guardian_notary_of_public_faith')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('legal_guardian_notary_of_public_faith')" class="text-danger">@{{ errors.first('legal_guardian_notary_of_public_faith') }}</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if=" show_apoderado_form ">
                        <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_notary') }">
                            <div class="col-md-4">
                                <label class="control-label">Notario</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="legal_guardian_notary" v-model.trim="legal_guardian_notary" class="form-control" v-validate.initial="'required'">
                                <i v-show="errors.has('legal_guardian_notary')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('legal_guardian_notary')" class="text-danger">@{{ errors.first('legal_guardian_notary') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_date_authority') }">
                            <div class="col-md-4">
                                <label class="control-label">Fecha de Poder</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" v-date name="legal_guardian_date_authority" v-model.trim="legal_guardian_date_authority" class="form-control" v-validate.initial="'required|max_current_date'">
                                <i v-show="errors.has('legal_guardian_date_authority')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('legal_guardian_date_authority')" class="text-danger">@{{ errors.first('legal_guardian_date_authority') }}</span>
                            </div>
                        </div>
                    </div>
                </div><!-- /div principal cyk -->
                <div class="row"></div>
            </form>
        </div>
    </div>
    <div class="ibox float-e-margins">
        {{-- <div class="ibox-title">
            <h5>Direccion del Solicitante (@{{ applicant_type }}) <small class="m-l-sm">  </small></h5>
            <div class="ibox-tools">
            </div>
        </div> --}}
        <div class="ibox-content">
            <legend>Direccion del Solicitante (@{{ applicant_type }})</legend>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group"><label class="col-sm-4 control-label">Ciudad</label>
                        <div class="col-md-8">
                            <select name="beneficiary_city_address_id" v-model="beneficiary_city_address_id" class="form-control">
                                <option :value="null"></option>
                                <option v-for="(city, index) in cities" :value="city.id" >@{{ city.name }}</option>
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
</div>
