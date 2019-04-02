<div class="col-md-12">
    <div class="ibox">
        <div class="ibox-content">
            <div class="pull-left">
                <legend>Complemento Economico Padre</legend>
            </div>
            {{-- @can('update',new Muserpol\Models\RetirementFund\RetirementFund) --}}
            <div class="text-right" v-if="!read">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
            </div>
            {{-- @else
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" disabled><i class="fa fa-edit" ></i> Editar </button>
            </div>
            @endcan --}}
            <br>
            <div class="row">
                <div class="col-md-2">
                    <strong> Modalidad:</strong>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" v-model="form.procedure_modality.name" disabled="">
                </div>
                <div class="col-md-2">
                    <strong> Tipo de Recepcion:</strong>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="Inclusion" disabled="">
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
                    <strong>Ente Gestor:</strong>&nbsp;
                </div>
                <div class="col-md-4">
                    <select class="form-control" v-model="form.pension_entity_id" name="pension_entity_id" :disabled='!editing'>
                        <option v-for="(p, index) in pensionEntities" :value="p.id" :key="index">@{{p.name}}</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2">
                    <strong>Estado:</strong>
                </div>
                <div class="col-md-4">
                    <select class="form-control" v-model="form.procedure_state_id" name="procedure_state_id" :disabled='!editing'>
                        <option v-for="(state, index) in states" :value="state.id" :key="index">@{{state.name}}</option>
                    </select>
                </div>
                <div class="col-md-6">

                </div>
            </div>
            <br>

            <div v-show="editing">
                <div class="text-center">
                    {{-- <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button> --}}
                    <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>