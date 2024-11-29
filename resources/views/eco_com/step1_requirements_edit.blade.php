<div>
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
                <div class="pull-left">
                    <legend> Documentos Presentados</legend>
                </div>
                <div class="pull-right">
                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''"
                        @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'"></i>
                        Editar</button>
                </div>
            </div>
            <form id="brianytati">
                <div class="row">
                    <div v-for="(requirement, index) in requirementList" :key="index">
                        <div class="vote-item" @click="checked(index, i)" v-for="(rq, i) in requirement"
                            :class="rq.background" style="cursor:pointer" :key="i" v-if="isVisible(rq)">
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
                                            <input type="text" :name="'comment'+rq.id" class="form-control" maxlength="80"
                                                :disabled="!editing" v-model="rq.comment">
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <div class="col-md-2 ">
                                    <div class="vote-icon">
                                        <span style="color:#3c3c3c"><i class="fa "
                                                :class="rq.status ? 'fa-check-square' :'fa-square-o'  "></i></span>
                                        <div style="opacity:0" v-if="rol!=11">
                                            <input type="checkbox" v-model="rq.status" value="checked"
                                                :name="'document'+rq.id" class="largerCheckbox">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div v-else>
                    <div v-if="editing" class="alert alert-warning">No hay requisitos por editar</div>
                </div> --}}
                <br>
                <div style="margin-bottom:180px">
                    <h4>Documentos adicionales</h4>
                    <select data-placeholder="Documentos adicionales..." class="chosen-select"
                        id="aditional_requirements" name="aditional_requirements[]" multiple=""
                        style="width: 350px; display: none; border: 2px solid red;" tabindex="-1" v-bind:disabled="!editing">
                        <option v-for="(requirement, index) in aditionalRequirements" :value="requirement.id"
                            :key="`nonselected-${index}`">@{{ requirement.document }} </option>
                        <option v-for="(requirement, index) in aditionalRequirementsSelected" :value="requirement.id"
                            :key="`selected-${index}`" selected>@{{ requirement.document }} </option>
                    </select>
                </div>
                <br>
                <br>
                <div class="text-center" v-if="editing">
                    <button class="btn btn-danger" type="button" @click="toggle_editing"><i
                            class="fa fa-times-circle"></i>&nbsp;&nbsp;<span cla ss="bold">Cancelar</span></button>
                    <button type="button" class="btn btn-primary" type="button" @click="store()"><i
                            class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>