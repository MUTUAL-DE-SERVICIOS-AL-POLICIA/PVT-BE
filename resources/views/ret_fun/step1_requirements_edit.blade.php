<div>
  
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
   
    <div class="ibox">
            <div class="ibox-content">
                    <div class="row">
                        <div class="pull-left"> <legend > Documentos Presentados</legend></div>
                        <div class="pull-right">
                            <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editay</button>
                        </div>
                    </div>
                <form id="brianytati">
                    <div class="row">
                        <div v-for="(requirement, index) in requirementList" :key="index">
                            <div class="vote-item" @click="checked(index, i)" v-for="(rq, i) in requirement" :class="rq.background" style="cursor:pointer" :key="i" v-if="isVisible(rq)">
                                <div class="row">
                                    <div :class="editing?'col-md-10':'col-md-10'">
                                        <div class="vote-actions">
                                            <h1 v-if="rq.number < 1000">
                                                @{{rq.number}}                                                
                                            </h1>
                                            <h1 v-else>
                                                A
                                            </h1>
                                        </div> 
                                        <span class="vote-title">@{{rq.document}}</span>
                                        <div class="vote-info">
                                            <div class="col-md-2 no-margins no-padding">
                                                <i class="fa fa-comments-o"></i> Comentario:
                                            </div>
                                            <div class="col-md-6 no-margins no-padding">
                                                <input type="text" :name="'comment'+rq.id" class="form-control" :disabled="!editing" v-model="rq.comment" >
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="col-md-2 ">
                                        <div class="vote-icon" >
                                            <span style="color:#3c3c3c"  ><i class="fa " :class="rq.status ? 'fa-check-square' :'fa-square-o'  "></i></span>
                                            <div style="opacity:0" v-if="rol!=11">
                                                <input type="checkbox" v-model="rq.status" value="checked"  :name="'document'+rq.id" class="largerCheckbox">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div v-if='rol != 11'> 
                        <h4>Documentos adicionales</h4>
                        <select data-placeholder="Documentos adicionales..." class="chosen-select" id="aditional_requirements" name="aditional_requirements[]" multiple="" style="width: 350px; display: none;" tabindex="-1" v-bind:disabled="!editing">
                            <option v-for="(requirement, index) in aditionalRequirements"  :value="requirement.id" :key="`nonselected-${index}`">@{{ requirement.document }} </option>
                            <option v-for="(requirement, index) in aditionalRequirementsSelected"  :value="requirement.id" :key="`selected-${index}`" selected>@{{ requirement.document }} </option>
                        </select>
                    </div>
                    <br>
                    <br>
                    <div class="text-center" v-if="editing" >     
                        <button class="btn btn-danger" type="button" @click="toggle_editing"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span cla   ss="bold">Cancelar</span></button>           
                        <button type="button" class="btn btn-primary" type="button" @click="store(ret_fun_id)"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                    </div>                    
                </form>
            </div>            
    </div>
    

</div>