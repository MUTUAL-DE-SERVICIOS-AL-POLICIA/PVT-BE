<div class="col-md-12">
    <div class="ibox">
        <div class="ibox-content">
            <div class="pull-left">
                <legend>Complemento Economico @{{ ecoCom.code }}</legend>
            </div>
            {{-- @can('update',new Muserpol\Models\RetirementFund\RetirementFund) --}}
            <div class="text-right">
                <button v-if="editing" class="btn btn-danger" @click="deleteEcoCom()" ><i class="fa fa-trash-o"></i></button>
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggleEditing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
            </div>
            {{-- @else
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" disabled><i class="fa fa-edit" ></i> Editar </button>
            </div>
            @endcan --}}
            <br>
            <div class="row">
                <div class="col-md-2">
                    <strong>Gestion:</strong>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" v-model="ecoComProcedure.year" disabled="">
                </div>
                <div class="col-md-2">
                    <strong> Semestre:</strong>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" v-model="ecoComProcedure.semester" disabled="">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2">
                    <strong> Fecha de Recepcion:</strong>&nbsp;
                </div>
                <div class="col-md-4">
                    {{-- @if(Session::get('rol_id') == 28) --}}
                        <input type="date" v-model="form.reception_date" class="form-control" :disabled='!editing'>
                    {{-- @else --}}
                    {{-- <inpust type="date" v-model="form.reception_date" class="form-control" disabled> @endif --}}
                </div>
                <div class="col-md-2">
                    <strong>Regional:</strong>
                </div>
                <div class="col-md-4">
                    <select class="form-control" v-model="form.city_id" name="city_id" :disabled='!editing'>
                        <option v-for="(c, index) in cities" :value="c.id" :key="index">@{{c.name}}</option>
                    </select>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-md-2">
                    <strong>Estado:</strong>
                </div>
                <div class="col-md-4">
                    <select class="form-control" v-model="form.procedure_state_id" name="procedure_state_id" :disabled='!editing'>
                        <option v-for="(state, index) in states" :value="state.id" :key="index">@{{state.name}}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <strong>Ente Gestor:</strong>&nbsp;
                </div>
                <div class="col-md-4">
                    <select class="form-control" v-model="form.pension_entity_id" name="pension_entity_id" disabled>
                        <option v-for="(p, index) in pensionEntities" :value="p.id" :key="index">@{{p.name}}</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2">
                    <strong>Grado:</strong>
                </div>
                <div class="col-md-4">
                    <select class="form-control" v-model="form.degree_id" name="degree_id" :disabled='!editing'>
                        <option v-for="(d, index) in degrees" :value="d.id" :key="index">@{{d.name}}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <strong>Categoria:</strong>
                </div>
                <div class="col-md-4">
                    <select class="form-control" v-model="form.category_id" name="category_id" :disabled='!editing'>
                        <option v-for="(c, index) in categories" :value="c.id" :key="index">@{{c.name}}</option>
                    </select>
                </div>
            </div> --}}
            <br>
            <div class="row">
                <div class="col-md-2">
                    <strong>Tipo de Prestacion:</strong>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" :value="ecoCom.eco_com_modality.shortened" disabled>
                </div>
                <div class="col-md-2">
                    <strong>Mes Renta:</strong>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" :value="ecoComProcedure.rent_month" disabled>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2">
                    <strong>Tipo de Trámite:</strong>
                </div>
                <div class="col-md-4">
                    <input type="text" :value="ecoCom.reception_type" disabled class="form-control">
                </div>
                <div class="col-md-2">
                    <strong>Ubicacion:</strong>
                </div>
                <div class="col-md-4">
                    <span>
                        @{{ecoCom.wf_state.name}}
                    </span>
                </div>
                {{-- <div class="col-md-2">
                    <strong>Flujo:</strong>
                </div>
                <div class="col-md-4">
                    <span>
                        @{{ecoCom.workflow.name}}
                    </span>
                </div> --}}
            </div>
            <br>
            <div v-show="editing">
                <div class="text-center">
                    <button class="btn btn-danger" type="button" @click="cancel()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                    <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>