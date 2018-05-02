<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Datos del Solicitante <small>something... @{{modality}}</small></h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content">
            <form method="get" class="form-horizontal">
                <div class="hr-line-dashed"></div>
                <div class="form-group"><label class="col-sm-4 control-label">Tipo de Solicitante</label>
                    <div class="col-sm-4">
                        <select class="form-control m-b" name="accountType" @change="change_applicant()" v-model.trim="applicant_type">
                            <option v-for="(type,index) in applicant_types"   :value="index+1">@{{type}} </option>
                        </select>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-4 control-label">Primer Nombre</label>
                            <div class="col-sm-8"><input type="text" name="applicant_first_name" v-model.trim="applicant_first_name" class="form-control"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-4 control-label">Segundo Nombre</label>
                            <div class="col-sm-8"><input type="text" name="applicant_second_name" v-model.trim="applicant_second_name" class="form-control"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-6 control-label">Apellido Paterno</label>
                            <div class="col-sm-6"><input type="text" name="applicant_last_name" v-model.trim="applicant_last_name" class="form-control"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-6 control-label">Apellido Materno</label>
                            <div class="col-sm-6"><input type="text" name="applicant_mothers_last_name" v-model.trim="applicant_mothers_last_name" class="form-control"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-6 control-label">Apellido de Casada</label>
                            <div class="col-sm-6"><input type="text" :disabled="applicantIsMale" name="applicant_surname_husband" v-model.trim="applicant_surname_husband" class="form-control"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-error': error.applicant_identity_card }">
                            <label class="col-sm-6 control-label">Carnet de Identidad</label>
                            <div class="col-sm-6">
                                <input type="text" name="applicant_identity_card" v-model.trim="applicant_identity_card" class="form-control">
                                <button @click="searchApplicant" type="button" role="button"><i class="fa fa-search"></i></button>
                                <i v-show="error.applicant_identity_card" class="fa fa-warning text-danger"></i>
                                <span v-show="error.applicant_identity_card" class="text-danger">@{{ errors.applicant_identity_card }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group"><label class="col-sm-4 control-label">Ciudad de Expedicion</label>
                            <div class="col-sm-8">
                                <select class="form-control m-b" name="applicant_city_identity_card" v-model.trim="applicant_city_identity_card_id">
                                    <option v-for="city in cities" :value="city.id">
                                        @{{ city.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label class="col-sm-4 control-label">Fechad de Nacimiento</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" v-model.trim="applicant_birth_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label class="col-sm-4 control-label">Genero</label>
                            <div class="col-sm-8">
                                <select class="form-control m-b" name="applicant_gender" v-model.trim="applicant_gender">
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-6 control-label">Parentesco</label>
                            <div class="col-sm-6">
                                <select class="form-control m-b" name="applicant_kinship" v-model.trim="applicant_kinship_id">
                                    <option v-for="kinship in kinships" :value="kinship.id">
                                        @{{ kinship.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-3 control-label">Telefono del Solicitante</label>
                            <div v-for="(phone,index) in applicant_phone_numbers">
                                <div class="col-sm-6">
                                    <input type="text" name="applicant_phone_number[]" v-model.trim="phone.value" :key="index" class="form-control" data-phone="true">
                                </div>
                                <div class="col-sm-1 no-padding">
                                    <button class="btn btn-danger" v-show="applicant_phone_numbers.length > 1" @click="deletePhoneNumber(index)" type="button"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn btn-success" type="button" @click="addPhoneNumber"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-3 control-label">Celular del Solicitante</label>
                            <div v-for="(cell_phone,index) in applicant_cell_phone_numbers">
                                <div class="col-sm-6 no-padding">
                                    <input type="text" name="applicant_cell_phone_number[]" v-model.trim="cell_phone.value" :key="index" class="form-control" data-cell-phone="true">
                                </div>
                                <div class="col-sm-1 no-padding">
                                    <button class="btn btn-danger" v-show="applicant_cell_phone_numbers.length > 1" @click="deleteCellPhoneNumber(index)" type="button"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn btn-success" type="button" @click="addCellPhoneNumber"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="row" v-if=" show_advisor_form ">
                    <h3 class="m-t-none m-b">Datos del Tutor Legal</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-4 control-label">Nombre de Juzgado</label>
                                <div class="col-sm-8"><input type="text" name="advisor_name_court" v-model.trim="advisor_name_court" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-4 control-label">Nro de Resolucion</label>
                                <div class="col-sm-8"><input type="text" name="advisor_resolution_number" v-model.trim="advisor_resolution_number" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-4 control-label">Fecha de Resolucion</label>
                                <div class="col-sm-8"><input type="text" name="advisor_resolution_date" v-model.trim="advisor_resolution_date" class="form-control"></div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                </div>
                <div class="row" v-if=" show_apoderado_form ">
                    <h3 class="m-t-none m-b">Datos del Apoderado</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label class="col-sm-4 control-label">Primer Nombre</label>
                                <div class="col-sm-8"><input type="text" name="legal_guardian_first_name" v-model.trim="legal_guardian_first_name" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label class="col-sm-4 control-label">Segundo Nombre</label>
                                <div class="col-sm-8"><input type="text" name="legal_guardian_second_name" v-model.trim="legal_guardian_second_name" class="form-control"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-6 control-label">Apellido Paterno</label>
                                <div class="col-sm-6"><input type="text" name="legal_guardian_last_name" v-model.trim="legal_guardian_last_name" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-6 control-label">Apellido Materno</label>
                                <div class="col-sm-6"><input type="text" name="legal_guardian_mothers_last_name" v-model.trim="legal_guardian_mothers_last_name" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-6 control-label">Apellido de Casada</label>
                                <div class="col-sm-6"><input type="text" name="legal_guardian_surname_husband" v-model.trim="legal_guardian_surname_husband" class="form-control"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group"><label class="col-sm-6 control-label">Carnet de Identidad</label>
                                <div class="col-sm-6"><input type="text" name="legal_guardian_identity_card" v-model.trim="legal_guardian_identity_card" class="form-control">
                                    <button @click="searchLegalGuardian" type="button" role="button"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group"><label class="col-sm-4 control-label">Ciudad de Expedicion</label>
                                <div class="col-sm-8">
                                    <select class="form-control m-b" name="legal_guardian_city_identity_card" v-model.trim="legal_guardian_city_identity_card">
                                        <option v-for="city in cities" :value="city.id">@{{ city.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-6 control-label">Nro de Poder</label>
                                <div class="col-sm-6"><input type="text" name="legal_guardian_number_authority" v-model.trim="legal_guardian_number_authority" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-6 control-label">Notaria de Fe Publica Nro</label>
                                <div class="col-sm-6"><input type="text" name="legal_guardian_notary_of_public_faith" v-model.trim="legal_guardian_notary_of_public_faith" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label class="col-sm-6 control-label">Notario</label>
                                <div class="col-sm-6"><input type="text" name="legal_guardian_notary" v-model.trim="legal_guardian_notary" class="form-control"></div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                </div>
            </form>
        </div>
    </div>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Direccion del Solicitante (@{{ applicant_type }}) <small class="m-l-sm">something //// </small></h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group"><label class="col-sm-4 control-label">Zona</label>
                        <div class="col-sm-8"><input type="text" name="beneficiary_zone" v-model.trim="beneficiary_zone" class="form-control"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label class="col-sm-4 control-label">Calle</label>
                        <div class="col-sm-8"><input type="text" name="beneficiary_street" v-model.trim="beneficiary_street" class="form-control"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label class="col-sm-4 control-label">Nro</label>
                        <div class="col-sm-8"><input type="text" name="beneficiary_number_address" v-model.trim="beneficiary_number_address" class="form-control"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
