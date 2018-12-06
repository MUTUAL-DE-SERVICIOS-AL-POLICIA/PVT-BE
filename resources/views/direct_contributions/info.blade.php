<div class="col-md-12">
    <div class="ibox">
        <div class="ibox-content">
                <div  class="pull-left">
                    <legend>Aportes Directos</legend>
                </div>
                {{-- @can('update',new Muserpol\Models\Contribution\DirectContribution) --}}

                {{-- @can('update',new Muserpol\Models\RetirementFund\RetirementFund) --}}
                <div class="text-right" v-if="!read">
                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
                </div>
                <div class="text-right" v-if="read">
                <a href="{{ url('direct_contribution/'.$direct_contribution->id)}}" class="btn btn-primary"> <i class="fa fa-eye"></i> Ver</a>                
                </div>

                {{-- @else --}}
                {{-- <br> --}}
                    {{-- @endcan --}}
                <br>                       
                <div class="row">                
                    <div class="col-md-2">
                        <strong> Modalidad:</strong>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" v-model="procedure_modality_name" disabled="">
                    </div>
                    <div class="col-md-2">
                        <strong> Fecha de carta de compromiso:</strong>&nbsp;
                    </div>
                    <div class="col-md-4">                        
                        <input type="date" v-model="form.commitment_date" class="form-control" :disabled='!editing'>
                    </div>                    
                </div>
                <br>
                <div class="row">                
                    <div class="col-md-2">
                        <strong> Fecha:</strong>&nbsp;
                    </div>
                    <div class="col-md-4">                        
                        <input type="date" v-model="form.date" class="form-control" :disabled='!editing'>                         
                    </div>
                    <div class="col-md-2">
                        <strong> Nro de Documento:</strong>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" v-model="form.document_number" :disabled='!editing'>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2">
                        <strong>Ciudad de Recepci&oacute;n:</strong>
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('city_id', $cities, null , ['placeholder' => 'Seleccione cuidad', 'class' => 'form-control','v-model'=>'form.city_id',':disabled'=>'!editing']) !!}
                    </div>
                    <div class="col-md-2">
                            <strong> Fecha de documento:</strong>&nbsp;
                        </div>
                    <div class="col-md-4">
                        @if(Session::get('rol_id') == 28)
                            <input type="date" v-model="form.document_date" class="form-control" > 
                        @else
                            <input type="date" v-model="form.document_date" class="form-control" :disabled='!editing'> 
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">                
                    <div class="col-md-2">
                        <strong>Estado:</strong>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" v-model="form.procedure_state_id" ref="modality" name="procedure_state_id" :disabled='!editing' >
                            <option v-for="(state, index) in states" :value="state.id" :key="index">@{{state.name}}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                            <strong> Periodo primer aporte:</strong>&nbsp;
                        </div>
                    <div class="col-md-4">                        
                        <input type="date" v-model="form.start_contribution_date" class="form-control" :disabled='!editing'>
                    </div>               
                </div>
                <br>
            
                <div v-show="editing">
                    <div class="text-center">
                        <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                        <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                    </div>
                </div>
                <br>
        </div>        
    </div>
</div>