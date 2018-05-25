<div>
    <form id="myform">
    <div class="row" style="display: none;">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group" :class="{'has-error': errors.has('procedure_type_id') }">
                <label class="col-sm-3 control-label">Tipo de Pago</label>
                <div class="col-sm-8">
                    <select class="form-control m-b" ref="procedure_type_id" name="procedure_type_id" @change="onChooseProcedureType" v-model="procedure_type_id" v-validate.initial="'required'">
                        <option :value="null"></option>
                        <option v-for="type in procedureTypes" :value="type.id" :key="type.id">@{{ type.name }}</option>
                    </select>
                    <i v-show="errors.has('procedure_type_id')" class="fa fa-warning text-danger"></i>
                    <span v-show="errors.has('procedure_type_id')" class="text-danger">@{{ errors.first('procedure_type_id') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="display: none;">
        <div class="col-md-6">
            <div class="form-group" :class="{'has-error': errors.has('city_end_id') }">
                <label class="col-sm-4 control-label">Regional</label>
                <div class="col-sm-8">
                    <select class="form-control m-b" ref="city_end" name="city_end_id" @change="onChooseCity" v-model="city_end_id" v-validate.initial="'required'">
                        <option :value="null"></option>
                        <option v-for="city in cities" :value="city.id" :key="city.id">@{{ city.name }}</option>
                    </select>
                    <i v-show="errors.has('city_end_id')" class="fa fa-warning text-danger"></i>
                    <span v-show="errors.has('city_end_id')" class="text-danger">@{{ errors.first('city_end_id') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group" :class="{'has-error': errors.has('ret_fun_modality') }">
                <label class="col-sm-4 control-label">Modalidad</label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="modality" v-on:change="onChooseModality" ref="modality" name="ret_fun_modality" id="ret_fun_modality"
                        v-validate.initial="'required'">
                        <option :value="null"></option>
                        <option v-for="(modality, index) in modalitiesFilter" :value="modality.id" :key="index">@{{modality.name}}</option>
                    </select>
                    <i v-show="errors.has('ret_fun_modality')" class="fa fa-warning text-danger"></i>
                    <span v-show="errors.has('ret_fun_modality')" class="text-danger">@{{ errors.first('ret_fun_modality') }}</span>
                </div>
            </div>
        </div>
    </div>
    <h2>Lista de Requisitos</h2>
    <div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div v-for="(requirement, index) in requirementList" :key="index">
            <div class="vote-item" @click="checked(index, i)" v-for="(rq, i) in requirement" :class="rq.background" style="cursor:pointer"
                :key="i">
                <div class="row">
                    <div class="col-md-10">
                        <div class="vote-actions">
                            <h1>
                                @{{rq.number}}
                            </h1>
                        </div>
                        <span class="vote-title">@{{rq.document}}</span>
                        <div class="vote-info">
                            <div class="col-md-2 no-margins no-padding">
                                <i class="fa fa-comments-o"></i> Comentario:
                            </div>
                            <div class="col-md-6 no-margins no-padding">
                                <input type="text" :name="'comment'+rq.id" class="form-control">
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-2 ">
                        <div class="vote-icon">
                            <span style="color:#3c3c3c"><i class="fa " :class="rq.status ? 'fa-check-square' :'fa-square-o'  "></i></span>
                            <div style="opacity:0">
                                <input type="checkbox" v-model="rq.status" value="checked"  :name="'document'+rq.id" class="largerCheckbox">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <transition
            name="show-requirements-error"
            enter-active-class="animated bounceInLeft">
            <div class="alert alert-danger" v-if="showRequirementsError">
                <h2>Debe seleccionar los requisitos</h2>
            </div>
        </transition> --}}
        <div class="text-center">                
                <button class="btn btn-primary" type="button" @click="store(ret_fun_id)"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
            </div>
    </div>
</form>
</div>