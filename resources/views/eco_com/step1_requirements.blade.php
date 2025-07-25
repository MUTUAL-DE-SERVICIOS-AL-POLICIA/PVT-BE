<div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group" >
                <label class="col-sm-3 control-label">Modalidad</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" v-model="procedure_modality_name" disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" :class="{'has-error': errors.has('reception_type_id') }">
                <label class="col-sm-4 control-label">Tipo de Recepcion</label>
                <div class="col-sm-8">
                    <select class="form-control m-b" ref="reception_type_id" name="reception_type_id" v-model="reception_type_id"
                        v-validate.initial="'required'" disabled>
                        <option v-for="rt in reception_types" :value="rt.id" :key="rt.id">@{{ rt.name }}</option>
                    </select>
                    <i v-show="errors.has('reception_type_id')" class="fa fa-warning text-danger"></i>
                    <span v-show="errors.has('reception_type_id')" class="text-danger">@{{ errors.first('reception_type_id') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-4 control-label">Ente Gestor</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" disabled v-model="pension_entity_name">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" :class="{'has-error': errors.has('city_id') }">
                <label class="col-sm-4 control-label">Regional</label>
                <div class="col-sm-8">
                    <select class="form-control m-b" ref="city" name="city_id" v-model="city_id" v-validate.initial="'required'">
                        <option :value="null"></option>
                        <option v-for="city in cities" :value="city.id" :key="city.id">@{{ city.name }}</option>
                    </select>
                    <i v-show="errors.has('city_id')" class="fa fa-warning text-danger"></i>
                    <span v-show="errors.has('city_id')" class="text-danger">@{{ errors.first('city_id') }}</span>
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
                            <div style="display: none;">
                                <input type="checkbox" v-model="rq.status" value="checked" :name="'document'+rq.id">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div v-if="aditionalRequirements.length > 0">
            <h4>Documentos adicionales</h4>
            <select data-placeholder="Documentos adicionales..." class="chosen-select" name="aditional_requirements[]" multiple="" style="width: 350px; display: none;"
                tabindex="-1">
                <option v-for="(requirement, index) in aditionalRequirements"  :value="requirement.id" :key="index">@{{ requirement.document }} </option>
            </select>
        </div>
        <transition name="show-requirements-error" enter-active-class="animated bounceInLeft">
            <div class="alert alert-danger" v-if="showRequirementsError">
                <h2>Debe seleccionar los requisitos</h2>
            </div>
        </transition>
    </div>
</div>