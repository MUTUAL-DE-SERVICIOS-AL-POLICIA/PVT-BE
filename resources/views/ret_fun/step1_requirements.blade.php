<div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group"><label class="col-sm-4 control-label">Regional</label>
                <div class="col-sm-8">
                    <select class="form-control m-b" ref="city_end" name="city_end_id" @change="onChooseCity" v-model="city_end_id">
                        <option v-for="city in cities" :value="city.id" :key="city.id">
                            @{{ city.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group"><label class="col-sm-4 control-label">Modalidad</label>
                <div class="col-sm-8">
                    <select v-model="modality" v-on:change="onChooseModality" ref="modality" name="ret_fun_modality" id="ret_fun_modality">
                        <option v-for="(modality, index) in modalities" :value="modality.id" :key="index">@{{modality.name}}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-success" v-for="(requirement, index) in requirementsList" :key="index">
        <span v-if="requirement.number != actualTarget(requirement.number)">
           <div class="panel-heading">@{{requirement.number}}</div>
       </span>
        <div class="panel-body">
            <div class="col-md-12">
                <div class="col-md-10">
                    <span class="m-l-xs">@{{requirement.document}}</span>
                </div>
                <div class="col-md-2">
                    <input type="checkbox" value="checked" :name="'document'+requirement.id" class="i-checks" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-2">
                    Comentarios
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" :name="'comment'+requirement.id">
                </div>
            </div>
        </div>
    </div>
</div>