<template>
    <div >

        <div class="row" :id="`footerCreateBeneficiaries${index}`">
            <div class="col-md-12">
                <div  class="pull-left">
                    <legend >Beneficiario {{beneficiary.type=='S'?'Solicitante':''}}{{solicitante?'Solicitante':''}} </legend>
                </div>
                <div class="text-right" v-if="editable&&beneficiary.type!='S'?true:false">
                    <button class="btn btn-danger" type="button" v-on:click= "remove"> <i class="fa fa-trash" ></i> </button>
                </div>
            </div>
        </div>
        <br>
         <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Cédula de Identidad</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" v-model.trim="beneficiary.identity_card" ref="identitycard" name="beneficiary_identity_card[]" class="form-control" :disabled="!editable">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" @click="searchBeneficiary" role="button" :disabled="!editable"><i class="fa fa-search"></i></button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div>
        </div>
        <br>
        <div class="row" >
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Primer Nombre</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.first_name" name="beneficiary_first_name[]"  class="form-control" :disabled="!editable">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Segundo Nombre</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.second_name" name="beneficiary_second_name[]" class="form-control" :disabled="!editable">
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
                    <input type="text" v-model.trim="beneficiary.last_name" name="beneficiary_last_name[]" class="form-control" :disabled="!editable">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido Materno</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.mothers_last_name" name="beneficiary_mothers_last_name[]" class="form-control" :disabled="!editable">
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
                    <input type="text" v-model.trim="beneficiary.surname_husband" name="beneficiary_surname_husband[]" class="form-control" :disabled="!editable">
                </div>
            </div>
             <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Genero</label>
                </div>
                <div class="col-md-8">
                    <select name="beneficiary_gender[]" id="" v-model.trim="beneficiary.gender" class="form-control" :disabled="!editable">
                        <option :value="null"></option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
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
                    <input type="text" v-date v-model.trim="beneficiary.birth_date" name="beneficiary_birth_date[]" class="form-control" :disabled="!editable">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Parentesco</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control " v-model.trim="beneficiary.kinship_id" name="beneficiary_kinship[]" :disabled="!editable">
                        <option :value="null"></option>
                        <option v-for="kinship in kinships" :key="beneficiary.id + ''+kinship.id " :value="kinship.id">{{kinship.name}}</option>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <div class="row" v-if="beneficiary.type == 'S'">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Teléfono del Solicitante</label>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-2" v-if="editable">
                            <button class="btn btn-success" type="button" @click="addPhoneNumber"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-md-10">
                            <div v-for="(phone,index) in beneficiary.phone_number" :key="'phone-'+index">
                                <div class="input-group">
                                    <input type="text" name="beneficiary_phone_number[]" v-model.trim="beneficiary.phone_number[index]" :key="index" class="form-control" v-phone :disabled="!editable">
                                    <span class="input-group-btn" v-if="editable">
                                        <button class="btn btn-danger" v-show="beneficiary.phone_number.length > 1" @click="deletePhoneNumber(index)" type="button"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
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
                        <div class="col-md-2" v-if="editable">
                            <button class="btn btn-success" type="button" @click="addCellPhoneNumber"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-md-10">
                            <div v-for="(cell_phone,index) in beneficiary.cell_phone_number" :key="`cellphone-${index}`">
                                <div class="input-group">
                                    <input type="text" name="beneficiary_cell_phone_number[]" v-model.trim="beneficiary.cell_phone_number[index]" :key="index" class="form-control" v-cell-phone :disabled="!editable">
                                    <span class="input-group-btn" v-if="editable">
                                        <button class="btn btn-danger" v-show="beneficiary.cell_phone_number.length > 1" @click="deleteCellPhoneNumber(index)" type="button"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
        <div class="row" v-if="beneficiary.type == 'S'">
            <div class="col-md-3">
                <div class="col-md-4">
                    <label class="control-label">Ciudad</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" v-model.trim="beneficiary.address[0].city_address_id" name="beneficiary_city_address_id[]" :disabled="!editable">
                        <option :value="null"></option>
                        <option v-for="city in cities" :key="city.id" :value="city.id" >{{ city.name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-4">
                    <label class="control-label">Zona</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="beneficiary_zone[]" v-model.trim="beneficiary.address[0].zone" class="form-control" :disabled="!editable">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-4">
                    <label class="control-label">Calle</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="beneficiary_street[]" v-model.trim="beneficiary.address[0].street" class="form-control" :disabled="!editable">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-4">
                    <label class="control-label">Numero</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="beneficiary_number_address[]" v-model.trim="beneficiary.address[0].number_address" class="form-control" :disabled="!editable">
                    </div>
                </div>
            </div>
        </div>
        <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-4">
                        <strong>Documentos Completos:</strong>
                    </div>
                    <div class="col-md-2">
                        <input type="checkbox" v-model.trim="beneficiary.state" name="beneficiary_state[]" :value="beneficiary.state" :checked="beneficiary.state" class="form-control mediumCheckBox" :disabled="!editable">
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label class="control-label">Tutor/Apoderado: </label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <select name="beneficiary_legal_representative[]" v-model="beneficiary.legal_representative" class="form-control" :disabled="!editable">
                                <option :value="null"></option>
                                <option v-for="lr in legalRepresentatives" :value="lr.id" :key="`lg-${lr.id}`">{{lr.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row" v-if="beneficiary.legal_representative === 1" key="tutor">
                <div class="col-md-12">
                    <legend>Información del Tutor(a)</legend>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': errors.has('beneficiary_advisor_identity_card[]') }">
                            <div class="col-md-4">
                                <label class="control-label">Cédula de Identidad</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" name="beneficiary_advisor_identity_card[]" v-model.trim="beneficiary.advisor_identity_card" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                                    <span class="input-group-btn">
                                        <button class="btn" :class="errors.has('beneficiary_advisor_identity_card[]') ? 'btn-danger' : 'btn-primary' " type="button" @click="searchLegalRepresentative(1)" role="button" :disabled="!editable"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                                <i v-show="errors.has('beneficiary_advisor_identity_card[]')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('beneficiary_advisor_identity_card[]')" class="text-danger">{{ errors.first('beneficiary_advisor_identity_card[]') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Parentesco</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" name="kinship_beneficiary[]" v-model.trim="beneficiary.kinship_beneficiary_id" v-validate.initial="'required'" :disabled="!editable">
                                    <option :value="null"></option>
                                    <option v-for="kinship_beneficiary in kinship_beneficiaries" :key="beneficiary.id + ''+kinship_beneficiary.id " :value="kinship_beneficiary.id">{{kinship_beneficiary.name}}</option>
                                </select>
                                <i v-show="errors.has('kinship_beneficiary[]')" class="fa fa-warning text-danger"></i>
                                <span v-show="errors.has('kinship_beneficiary[]')" class="text-danger">{{ errors.first('kinship_beneficiary[]') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_advisor_first_name[]') }" >
                        <div class="col-md-4">
                            <label class="control-label">Primer Nombre</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_advisor_first_name[]" v-model.trim="beneficiary.advisor_first_name" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_advisor_first_name[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_advisor_first_name[]')" class="text-danger">{{ errors.first('beneficiary_advisor_first_name[]') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <label class="control-label">Segundo Nombre</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_advisor_second_name[]" v-model.trim="beneficiary.advisor_second_name" class="form-control" :disabled="!editable">
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
                            <input type="text" name="beneficiary_advisor_last_name[]" v-model.trim="beneficiary.advisor_last_name" class="form-control" :disabled="!editable">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <label class="control-label">Apellido Materno</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_advisor_mothers_last_name[]" v-model.trim="beneficiary.advisor_mothers_last_name" class="form-control" :disabled="!editable">
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
                            <input type="text" name="beneficiary_advisor_surname_husband[]" v-model.trim="beneficiary.advisor_surname_husband" class="form-control" :disabled="!editable">
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
                            <input type="text" v-date class="form-control" v-model.trim="beneficiary.advisor_birth_date" name="beneficiary_advisor_birth_date[]" :disabled="!editable">
                        </div>
                    </div>
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_advisor_gender[]') }">
                        <div class="col-md-4">
                            <label class="control-label">Genero</label>
                        </div>
                        <div class="col-md-8">
                            <select class="form-control m-b" name="beneficiary_advisor_gender[]" v-model.trim="beneficiary.advisor_gender" v-validate.initial="'required'" :disabled="!editable">
                                <option :value="null"></option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            <i v-show="errors.has('beneficiary_advisor_gender[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_advisor_gender[]')" class="text-danger">{{ errors.first('beneficiary_advisor_gender[]') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <!-- <div class="col-md-6">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-6">
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
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_advisor_name_court[]')}">
                        <div class="col-md-4">
                            <label class="control-label">Nombre de Juzgado</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_advisor_name_court[]" v-model.trim="beneficiary.advisor_name_court" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_advisor_name_court[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_advisor_name_court[]')" class="text-danger">{{ errors.first('beneficiary_advisor_name_court[]') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_advisor_resolution_number[]')}">
                        <div class="col-md-4">
                            <label class="control-label">Nro de Resolución</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_advisor_resolution_number[]" v-model.trim="beneficiary.advisor_resolution_number" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_advisor_resolution_number[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_advisor_resolution_number[]')" class="text-danger">{{ errors.first('beneficiary_advisor_resolution_number[]') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_advisor_resolution_date[]')}">
                        <div class="col-md-4">
                            <label class="control-label">Fecha de Resolución</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" v-date name="beneficiary_advisor_resolution_date[]" v-model.trim="beneficiary.advisor_resolution_date" class="form-control" v-validate.initial="'required|date_format:dd/MM/yyyy|max_current_date'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_advisor_resolution_date[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_advisor_resolution_date[]')" class="text-danger">{{ errors.first('beneficiary_advisor_resolution_date[]') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            
                        </div>
                        <div class="col-md-8">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="beneficiary.legal_representative === 2" key="apoderado">
                <div class="col-md-12">
                    <legend>Información del Apoderado(a)</legend>
                </div>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_legal_guardian_identity_card[]') }">
                        <div class="col-md-4">
                            <label class="control-label">Cédula de Identidad</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" name="beneficiary_legal_guardian_identity_card[]" v-model.trim="beneficiary.legal_guardian_identity_card" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                                <span class="input-group-btn">
                                    <button class="btn" :class="errors.has('beneficiary_legal_guardian_identity_card[]') ? 'btn-danger' : 'btn-primary'" type="button" @click="searchLegalRepresentative(2)" role="button" :disabled="!editable"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                            <i v-show="errors.has('beneficiary_legal_guardian_identity_card[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_legal_guardian_identity_card[]')" class="text-danger">{{ errors.first('beneficiary_legal_guardian_identity_card[]') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_legal_guardian_first_name[]') }" >
                        <div class="col-md-4">
                            <label class="control-label">Primer Nombre</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_legal_guardian_first_name[]" v-model.trim="beneficiary.legal_guardian_first_name" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_legal_guardian_first_name[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_legal_guardian_first_name[]')" class="text-danger">{{ errors.first('beneficiary_legal_guardian_first_name[]') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <label class="control-label">Segundo Nombre</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_legal_guardian_second_name[]" v-model.trim="beneficiary.legal_guardian_second_name" class="form-control" :disabled="!editable">
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
                            <input type="text" name="beneficiary_legal_guardian_last_name[]" v-model.trim="beneficiary.legal_guardian_last_name" class="form-control" :disabled="!editable">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <label class="control-label">Apellido Materno</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_legal_guardian_mothers_last_name[]" v-model.trim="beneficiary.legal_guardian_mothers_last_name" class="form-control" :disabled="!editable">
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
                            <input type="text" name="beneficiary_legal_guardian_surname_husband[]" v-model.trim="beneficiary.legal_guardian_surname_husband" class="form-control" :disabled="!editable">
                        </div>
                    </div>
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_legal_guardian_gender[]') }">
                        <div class="col-md-4">
                            <label class="control-label">Genero</label>
                        </div>
                        <div class="col-md-8">
                            <select class="form-control m-b" name="beneficiary_legal_guardian_gender[]" v-model.trim="beneficiary.legal_guardian_gender" v-validate.initial="'required'" :disabled="!editable">
                                <option :value="null"></option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            <i v-show="errors.has('beneficiary_legal_guardian_gender[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_legal_guardian_gender[]')" class="text-danger">{{ errors.first('beneficiary_legal_guardian_gender[]') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_legal_guardian_number_authority[]') }">
                        <div class="col-md-4">
                            <label class="control-label">Nro de Poder</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_legal_guardian_number_authority[]" v-model.trim="beneficiary.legal_guardian_number_authority" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_legal_guardian_number_authority[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_legal_guardian_number_authority[]')" class="text-danger">{{ errors.first('beneficiary_legal_guardian_number_authority[]') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_legal_guardian_notary_of_public_faith[]') }">
                        <div class="col-md-4">
                            <label class=" control-label">Notaria de Fe Publica Nro</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_legal_guardian_notary_of_public_faith[]" v-model.trim="beneficiary.legal_guardian_notary_of_public_faith" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_legal_guardian_notary_of_public_faith[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_legal_guardian_notary_of_public_faith[]')" class="text-danger">{{ errors.first('beneficiary_legal_guardian_notary_of_public_faith[]') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_legal_guardian_notary[]') }">
                        <div class="col-md-4">
                            <label class="control-label">Notario</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="beneficiary_legal_guardian_notary[]" v-model.trim="beneficiary.legal_guardian_notary" class="form-control" v-validate.initial="'required'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_legal_guardian_notary[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_legal_guardian_notary[]')" class="text-danger">{{ errors.first('beneficiary_legal_guardian_notary[]') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6" :class="{'has-error': errors.has('beneficiary_legal_guardian_date_authority[]') }">
                        <div class="col-md-4">
                            <label class="control-label">Fecha de Poder</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" v-date name="beneficiary_legal_guardian_date_authority[]" v-model.trim="beneficiary.legal_guardian_date_authority" class="form-control" v-validate.initial="'required|date_format:dd/MM/yyyy|max_current_date'" :disabled="!editable">
                            <i v-show="errors.has('beneficiary_legal_guardian_date_authority[]')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('beneficiary_legal_guardian_date_authority[]')" class="text-danger">{{ errors.first('beneficiary_legal_guardian_date_authority[]') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        <div class="hr-line-dashed"></div>
    </div>

</template>
<script>
import { getGender } from '../../helper.js';
export default {
  props: ["kinships", "cities", "beneficiary", "editable", "removable","solicitante", "index", "kinship_beneficiaries"],
  data() {
    return {
        // removable_beneficiary: true
        legalRepresentatives: [
            { id: 1, name:'Tutor(a)'},
            { id: 2, name:'Apoderado(a)'},
        ]

    };
  },
  created(){
    //  Parche
  },
  mounted() {
    //this.$refs.identity_card.focus();
    // dateInputMaskAll();
  },
  methods: {
    addPhoneNumber(){
      if (this.beneficiary.phone_number.length > 0) {
        let last_phone = this.beneficiary.phone_number[this.beneficiary.phone_number.length-1];
        if (last_phone && !last_phone.includes('_')) {
          this.beneficiary.phone_number.push(null);
        }
      }else{
          this.beneficiary.phone_number.push(null);
      }
    },
    deletePhoneNumber(index){
      this.beneficiary.phone_number.splice(index,1);
      if(this.beneficiary.phone_number.length < 1)
        this.addPhoneNumber()
    },
    addCellPhoneNumber(){
      if (this.beneficiary.cell_phone_number.length > 0) {
        let last_phone = this.beneficiary.cell_phone_number[this.beneficiary.cell_phone_number.length-1];
        if (last_phone && !last_phone.includes('_')) {
          this.beneficiary.cell_phone_number.push(null);
        }
      }else{
          this.beneficiary.cell_phone_number.push(null);
      };
    },
    deleteCellPhoneNumber(index){
      this.beneficiary.cell_phone_number.splice(index,1);
      if(this.beneficiary.cell_phone_number.length < 1)
        this.addCellPhoneNumber()
    },
    remove() {
      this.$emit("remove");
    },
    searchBeneficiary: function() {
      let ci = this.beneficiary.identity_card;
      axios
        .get("/search_ajax", {
          params: {
            ci
          }
        })
        .then(response => {
          let data = response.data;
          this.setDataBeneficiary(data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    setDataBeneficiary(data) {
      this.beneficiary.first_name = data.first_name;
      this.beneficiary.second_name = data.second_name;
      this.beneficiary.last_name = data.last_name;
      this.beneficiary.mothers_last_name = data.mothers_last_name;
      this.beneficiary.surname_husband = data.surname_husband;
      this.beneficiary.identity_card = data.identity_card;
    //   if(data.city_identity_card_id!=null){
    //     this.beneficiary.city_identity_card_id = data.city_identity_card_id;
    //   }
    //   else
      this.beneficiary.city_identity_card_id = data.city_identity_card_id;      
      this.beneficiary.birth_date = data.birth_date;
      this.beneficiary.kinship_id = data.kinship_id;
      this.beneficiary.gender = data.gender;
      this.beneficiary.state = !! data.state ? data.state : false;
    },
    getGenderBeneficiary(value){
        return getGender(value);
    },
    setAdvisorData(data){
        this.beneficiary.advisor_identity_card = data.identity_card;
        this.beneficiary.advisor_city_identity_card_id = data.city_identity_card_id;
        this.beneficiary.advisor_first_name = data.first_name;
        this.beneficiary.advisor_second_name = data.second_name;
        this.beneficiary.advisor_last_name = data.last_name;
        this.beneficiary.advisor_mothers_last_name = data.mothers_last_name;
        this.beneficiary.advisor_surname_husband = data.surname_husband;
        this.beneficiary.advisor_birth_date = data.birth_date;
        this.beneficiary.advisor_gender = data.gender;
        // phone.value
        // cell_phone.value
        this.beneficiary.advisor_name_court = data.name_court;
        this.beneficiary.advisor_resolution_number = data.resolution_number;
        this.beneficiary.advisor_resolution_date = data.resolution_date;
    },
    setLegalGuardianData(data){
        this.beneficiary.legal_guardian_identity_card = data.identity_card;
        this.beneficiary.legal_guardian_city_identity_card_id = data.city_identity_card_id;
        this.beneficiary.legal_guardian_first_name = data.first_name;
        this.beneficiary.legal_guardian_second_name = data.second_name;
        this.beneficiary.legal_guardian_last_name = data.last_name;
        this.beneficiary.legal_guardian_mothers_last_name = data.mothers_last_name;
        this.beneficiary.legal_guardian_surname_husband = data.surname_husband;
        this.beneficiary.legal_guardian_gender = data.gender;
        this.beneficiary.legal_guardian_number_authority = data.number_authority;
        this.beneficiary.legal_guardian_notary_of_public_faith = data.notary_of_public_faith;
        this.beneficiary.legal_guardian_notary = data.notary;
        this.beneficiary.legal_guardian_date_authority = data.date_authority;
    },
    searchLegalRepresentative(type ){
        // type:
        // 1 => tutor
        // 2 => apoderado
        console.log("searching legal representative")
        let ci;
        switch (type) {
            case 1:
                ci = this.beneficiary.advisor_identity_card;
                break;
            case 2:
                ci = this.beneficiary.legal_guardian_identity_card;
                break;
            default:
                alert('error al buscar legal representative');
                break;
        }
        axios.get("/search_ajax", {
            params: {
                ci
            }
        })
        .then(response => {
            let data = response.data;
            setTimeout(() => {
                switch (type) {
                    case 1:
                        this.setAdvisorData(data);
                        break;
                    case 2:
                        this.setLegalGuardianData(data);
                        break;
                    default:
                        alert('error al guardar datos legal representative');
                        break;
                }
            }, 300);
        })
        .catch(function(error) {
            console.log("Error searching legal guardian");
            console.log(error);
        });
    },
  },
  computed:{
      beneficiaryAge(){          
          if (this.beneficiary.birth_date) {
              return moment().diff(this.beneficiary.birth_date, 'years');
          }
          return null;
      }
  }
};
</script>
<style>
input.mediumCheckBox
{
width: 20px;
height: 20px;
}

</style>
